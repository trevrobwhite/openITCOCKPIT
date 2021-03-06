<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

use itnovum\openITCOCKPIT\Core\HostNotificationConditions;
use itnovum\openITCOCKPIT\Core\NotificationsControllerRequest;
use itnovum\openITCOCKPIT\Core\ServiceNotificationConditions;
use itnovum\openITCOCKPIT\Core\ValueObjects\HostStates;

class NotificationsController extends AppController {

    /*
     * Attention! In this case we load an external Model from the monitoring plugin! The Controller
     * use this external model to fetch the required data out of the database
     */
    public $uses = [
        MONITORING_NOTIFICATION_HOST,
        MONITORING_NOTIFICATION_SERVICE,
        'Host',
        MONITORING_HOSTSTATUS,
        'Service',
        MONITORING_SERVICESTATUS,
        'Documentation'
    ];

    public $components = ['Paginator', 'ListFilter.ListFilter', 'RequestHandler'];
    public $helpers = ['ListFilter.ListFilter', 'Status', 'Monitoring', 'CustomValidationErrors', 'Uuid'];
    public $layout = 'Admin.default';

    public $listFilters = [
        'index' => [
            'fields' => [
                'NotificationHost.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
                'Host.name' => ['label' => 'Host name', 'searchType' => 'wildcard'],
                'Contact.name' => ['label' => 'Contact name', 'searchType' => 'wildcard'],
                'Command.name' => ['label' => 'Notification method', 'searchType' => 'wildcard'],

            ],
        ],
        'services' => [
            'fields' => [
                'NotificationService.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
                'Host.name' => ['label' => 'Host name', 'searchType' => 'wildcard'],
                'Contact.name' => ['label' => 'Contact name', 'searchType' => 'wildcard'],
                'Command.name' => ['label' => 'Notification method', 'searchType' => 'wildcard'],

            ],
        ],
        'hostNotification' => [
            'fields' => [
                'NotificationHost.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
            ],
        ],
        'serviceNotification' => [
            'fields' => [
                'NotificationService.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function index(){
        if (!isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = [];
        }
        //Process request and set request settings back to front end
        $NotificationsControllerRequest = new NotificationsControllerRequest(
            $this->request,
            new HostStates(),
            $this->userLimit
        );

        //Process conditions
        $Conditions = new HostNotificationConditions();
        $Conditions->setContainerIds($this->MY_RIGHTS);
        $Conditions->setLimit($NotificationsControllerRequest->getLimit());
        $Conditions->setFrom($NotificationsControllerRequest->getFrom());
        $Conditions->setTo($NotificationsControllerRequest->getTo());
        $Conditions->setOrder($NotificationsControllerRequest->getOrder());

        //Query notification records
        $query = $this->NotificationHost->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_notification = $this->Paginator->paginate(
            $this->NotificationHost->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );
        $this->set('all_notification', $all_notification);
        //Data for json and xml view /notifications.json or .xml
        $this->set('_serialize', ['all_notification']);
        $this->set('NotificationListsettings', $NotificationsControllerRequest->getRequestSettingsForListSettings());
    }

    public function services(){
        if (!isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = [];
        }
        //Process request and set request settings back to front end
        $NotificationsControllerRequest = new NotificationsControllerRequest(
            $this->request,
            new HostStates(),
            $this->userLimit
        );

        //Process conditions
        $Conditions = new ServiceNotificationConditions();
        $Conditions->setContainerIds($this->MY_RIGHTS);
        $Conditions->setLimit($NotificationsControllerRequest->getLimit());
        $Conditions->setFrom($NotificationsControllerRequest->getFrom());
        $Conditions->setTo($NotificationsControllerRequest->getTo());
        $Conditions->setOrder($NotificationsControllerRequest->getOrder());
        $NotificationsControllerRequest->setShowServiceNotifications(true);

        //Query notification records
        $query = $this->NotificationService->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_notification = $this->Paginator->paginate(
            $this->NotificationService->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );
        $this->set('all_notification', $all_notification);
        //Data for json and xml view /notifications.json or .xml
        $this->set('_serialize', ['all_notification']);
        $this->set('NotificationListsettings', $NotificationsControllerRequest->getRequestSettingsForListSettings());
    }

    public function hostNotification($host_id){
        if (!$this->Host->exists($host_id)) {
            throw new NotFoundException(__('invalid host'));
        }

        //Process request and set request settings back to front end
        $NotificationsControllerRequest = new NotificationsControllerRequest(
            $this->request,
            new HostStates(),
            $this->userLimit
        );

        $host = $this->Host->find('first', [
            'fields' => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
                'Host.host_url',
                'Host.host_type',
                'Host.container_id'
            ],
            'conditions' => [
                'Host.id' => $host_id,
            ],
            'contain' => [
                'Container',
            ],
        ]);

        //Check if user is permitted to see this object
        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        //Process conditions
        $Conditions = new HostNotificationConditions();
        $Conditions->setLimit($NotificationsControllerRequest->getLimit());
        $Conditions->setFrom($NotificationsControllerRequest->getFrom());
        $Conditions->setTo($NotificationsControllerRequest->getTo());
        $Conditions->setOrder($NotificationsControllerRequest->getOrder());
        $Conditions->setHostUuid($host['Host']['uuid']);

        //Query host notification records
        $query = $this->NotificationHost->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_notification = $this->Paginator->paginate(
            $this->NotificationHost->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );
        $docuExists = $this->Documentation->existsForUuid($host['Host']['uuid']);

        //Get meta data and push to front end
        $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], [
            'fields' => [
                'Hoststatus.current_state',
                'Hoststatus.is_flapping'
            ],
        ]);
        $this->set(compact(['host', 'all_notification', 'hoststatus', 'docuExists']));
        $this->set('NotificationListsettings', $NotificationsControllerRequest->getRequestSettingsForListSettings());

    }

    public function serviceNotification($service_id){
        if (!$this->Service->exists($service_id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        //Check if user is permitted to see this object
        $service = $this->Service->find('first', [
            'recursive' => -1,
            'fields' => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.service_type',
                'Service.service_url'
            ],
            'contain' => [
                'Host' => [
                    'fields' => [
                        'Host.id',
                        'Host.name',
                        'Host.uuid',
                        'Host.address'
                    ],
                    'Container',
                ],
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name',
                    ],
                ],
            ],
            'conditions' => [
                'Service.id' => $service_id,
            ],
        ]);
        if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $this->render403();

            return;
        }
        $allowEdit = false;
        if ($this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $allowEdit = true;
        }

        //Process request and set request settings back to front end
        $NotificationsControllerRequest = new NotificationsControllerRequest(
            $this->request,
            new HostStates(),
            $this->userLimit
        );

        //Process conditions
        $Conditions = new ServiceNotificationConditions();
        $Conditions->setLimit($NotificationsControllerRequest->getLimit());
        $Conditions->setFrom($NotificationsControllerRequest->getFrom());
        $Conditions->setTo($NotificationsControllerRequest->getTo());
        $Conditions->setOrder($NotificationsControllerRequest->getOrder());
        $NotificationsControllerRequest->setShowServiceNotifications(true);
        $Conditions->setServiceUuid($service['Service']['uuid']);

        //Query host notification records
        $query = $this->NotificationService->getQuery($Conditions, $this->Paginator->settings['conditions']);
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $all_notification = $this->Paginator->paginate(
            $this->NotificationService->alias,
            [],
            [key($this->Paginator->settings['order'])]
        );
        $docuExists = $this->Documentation->existsForUuid($service['Service']['uuid']);

        //Get meta data and push to front end
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], [
            'fields' => [
                'Servicestatus.current_state',
                'Servicestatus.is_flapping'
            ],
        ]);
        $this->set(compact(['service', 'all_notification', 'servicestatus', 'docuExists', 'allowEdit']));
        $this->set('NotificationListsettings', $NotificationsControllerRequest->getRequestSettingsForListSettings());
    }

}
