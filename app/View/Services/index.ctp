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

use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\HoststatusIcon;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon;

$this->Paginator->options(['url' => $this->params['named']]);

?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-gear fa-fw "></i>
            <?php echo __('Services'); ?>
            <span>>
                <?php echo __('List'); ?>
            </span>
        </h1>
    </div>
</div>

<?php echo $this->Flash->render('positive'); ?>

<div id="error_msg"></div>

<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
    <?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<?php if (!$QueryHandler->exists()): ?>
    <div class="alert alert-danger alert-block">
        <a href="#" data-dismiss="alert" class="close">×</a>
        <h4 class="alert-heading"><i class="fa fa-warning"></i> <?php echo __('Monitoring Engine is not running!'); ?>
        </h4>
        <?php echo __('File %s does not exists', $QueryHandler->getPath()); ?>
    </div>
<?php endif; ?>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php
                        if ($this->Acl->hasPermission('add')):
                            echo $this->Html->link(__('New'), '/' . $this->params['controller'] . '/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']);
                            echo " "; //Need a space for html
                        endif;
                        echo $this->Html->link(__('Filter'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter']);
                        if ($isFilter):
                            echo " "; //Need a space for html
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        ?>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-cog"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Services'); ?> </h2>
                    <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                        <li class="active">
                            <a href="<?php echo Router::url(array_merge(['controller' => 'services', 'action' => 'index'], $this->params['named'])); ?>"> <i class="fa fa-stethoscope"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Monitored'); ?></span> </a>
                        </li>
                        <?php if ($this->Acl->hasPermission('notMonitored')): ?>
                            <li class="">
                                <a href="<?php echo Router::url(array_merge(['controller' => 'services', 'action' => 'notMonitored'], $this->params['named'])); ?>">
                                    <i class="fa fa-user-md"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Not monitored'); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('disabled')): ?>
                            <li class="">
                                <a href="<?php echo Router::url(array_merge(['controller' => 'services', 'action' => 'disabled'], $this->params['named'])); ?>">
                                    <i class="fa fa-plug"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Disabled'); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </header>
                <div>

                    <div class="widget-body no-padding">
                        <?php
                        $options = ['avoid_cut' => true];
                        echo $this->ListFilter->renderFilterbox($filters, $options, '<i class="fa fa-filter"></i> ' . __('Filter'), false, false);
                        ?>
                        <div class="mobile_table">
                            <table id="service_list" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th colspan="2" class="select_datatable no-sort">
                                        <?php echo $this->Utils->getDirection($order, 'Servicestatus.current_state');
                                        echo $this->Paginator->sort('Servicestatus.current_state', 'Servicestatus'); ?>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-user fa-lg" title="<?php echo __('Acknowledgedment'); ?>"></i>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-power-off fa-lg" title="<?php echo __('in Downtime'); ?>"></i>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa fa-area-chart fa-lg" title="<?php echo __('Grapher'); ?>"></i>
                                    </th>
                                    <th class="no-sort text-center">
                                        <strong title="<?php echo __('Passively transferred service'); ?>">P</strong>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo $this->Utils->getDirection($order, 'Service.servicename');
                                        echo $this->Paginator->sort('Service.servicename', __('Servicename')); ?>
                                    </th>
                                    <th class="no-sort tableStatewidth" title="<?php echo __('Soft state'); ?>">
                                        <?php echo $this->Utils->getDirection($order, 'Servicestatus.last_state_change');
                                        echo $this->Paginator->sort('Servicestatus.last_state_change', __('Last state change')); ?>
                                    </th>
                                    <th class="no-sort tableStatewidth">
                                        <?php echo $this->Utils->getDirection($order, 'Servicestatus.last_check');
                                        echo $this->Paginator->sort('Servicestatus.last_check', __('Last check')); ?>
                                    </th>
                                    <th class="no-sort tableStatewidth">
                                        <?php echo $this->Utils->getDirection($order, 'Servicestatus.next_check');
                                        echo $this->Paginator->sort('Servicestatus.next_check', __('Next check')); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo $this->Utils->getDirection($order, 'Servicestatus.output');
                                        echo $this->Paginator->sort('Servicestatus.output', __('Service output')); ?>
                                    </th>
                                    <th class="no-sort text-center editItemWidth">
                                        <i class="fa fa-gear fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $tmp_host_uuid = null;
                                foreach ($all_services as $service):
                                    $Service = new Service($service);
                                    $Host = new Host($service);
                                    $Servicestatus = new Servicestatus($service['Servicestatus']);

                                    $allowEdit = false;
                                    if ($hasRootPrivileges === true):
                                        $allowEdit = true;
                                    else:
                                        if (isset($hostContainers[$Host->getId()])):
                                            if ($this->Acl->isWritableContainer($hostContainers[$Host->getId()])):
                                                $allowEdit = true;
                                            endif;
                                        endif;
                                    endif;


                                    if ($tmp_host_uuid != $Host->getUuid()):
                                        $tmp_host_uuid = $Host->getUuid();
                                        ?>
                                        <tr>
                                            <td class="service_table_host_header" colspan="13">
                                                <?php
                                                $href = 'javascript:void(0);';
                                                if ($this->Acl->hasPermission('browser', 'hosts')):
                                                    $href = Router::url([
                                                        'controller' => 'hosts',
                                                        'action' => 'browser',
                                                        $Host->getId()
                                                    ]);
                                                endif;
                                                $Hoststatus = new Hoststatus($service['Hoststatus']);
                                                $HoststatusIcon = new HoststatusIcon($Hoststatus->currentState(), $href);

                                                echo $HoststatusIcon->getHtmlIcon();
                                                if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                                    <a class="padding-left-5 txt-color-blueDark"
                                                       href="<?php echo $href; ?>">
                                                        <?php printf(
                                                            '%s (%s)',
                                                            h($Host->getHostname()),
                                                            h($Host->getAddress())); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?php echo h($Host->getHostname()); ?>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                                <a class="pull-right txt-color-blueDark"
                                                   href="<?php echo Router::url([
                                                       'controller' => 'services',
                                                       'action' => 'serviceList',
                                                       $Host->getId()
                                                   ]); ?>
                                                   ">
                                                    <i class="fa fa-list"
                                                       title=" <?php echo __('Go to Service list'); ?>"></i>
                                                    <?php endif; ?>
                                            </td>
                                        </tr>

                                    <?php endif; ?>
                                    <tr>
                                        <td class="text-center width-5">
                                            <?php if ($allowEdit): ?>
                                                <input type="checkbox" class="massChange"
                                                       servicename="<?php echo h($Service->getServicename()); ?>"
                                                       value="<?php echo $Service->getId(); ?>"
                                                       uuid="<?php echo $Service->getUuid(); ?>"
                                                       host-uuid="<?php echo $Host->getUuid(); ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($Servicestatus->isFlapping()):
                                                echo $Servicestatus->getServiceFlappingIconColored();
                                            else:
                                                $serviceHref = 'javascript:void(0);';
                                                if ($this->Acl->hasPermission('browser')):
                                                    $serviceHref = Router::url([
                                                        'controller' => 'services',
                                                        'action' => 'browser',
                                                        $Service->getId()
                                                    ]);
                                                endif;
                                                $ServicestatusIcon = new ServicestatusIcon(
                                                    $Servicestatus->currentState(), $serviceHref
                                                );
                                                echo $ServicestatusIcon->getHtmlIcon();
                                            endif;
                                            ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($Servicestatus->isAcknowledged()): ?>
                                                <?php if ($Servicestatus->getAcknowledgementType() == 1): ?>
                                                    <i class="fa fa-user fa-lg "
                                                       title="<?php echo __('Acknowledgedment'); ?>"></i>
                                                <?php else: ?>
                                                    <i class="fa fa-user-o fa-lg"
                                                       title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($Servicestatus->isInDowntime()): ?>
                                                <i class="fa fa-power-off fa-lg "></i>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($this->Monitoring->checkForServiceGraph($Host->getUuid(), $Service->getUuid())): ?>
                                                <?php
                                                $graphHref = 'javascript:void(0);';
                                                if ($this->Acl->hasPermission('browser')):
                                                    $graphHref = Router::url([
                                                        'controller' => 'services',
                                                        'action' => 'grapherSwitch',
                                                        $Service->getId()
                                                    ]);
                                                endif;
                                                ?>
                                                <a class="txt-color-blueDark" href="<?php echo $graphHref; ?>">
                                                    <i
                                                            class="fa fa-area-chart fa-lg popupGraph"
                                                            host-uuid="<?php echo $Host->getUuid(); ?>"
                                                            service-uuid="<?php echo $Service->getUuid(); ?>"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($Service->isActiveChecksEnabled() === false || $Host->isSatelliteHost()): ?>
                                                <strong title="<?php echo __('Passively transferred service'); ?>">P</strong>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($this->Acl->hasPermission('browser')): ?>
                                                <a href="<?php echo Router::url([
                                                    'controller' => 'services',
                                                    'action' => 'browser',
                                                    $Service->getId()]); ?>">
                                                    <?php echo h($Service->getServicename()); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo h($Service->getServicename()); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td
                                                data-original-title="<?php echo h($this->Time->format($Servicestatus->getLastStateChange(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>"
                                                data-placement="bottom" rel="tooltip" data-container="body">
                                            <?php echo h($this->Utils->secondsInHumanShort(time() - $Servicestatus->getLastStateChange())); ?>
                                        </td>
                                        <td><?php echo h($this->Time->format($Servicestatus->getLastCheck(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?></td>
                                        <td>
                                            <?php
                                            if ($Service->isActiveChecksEnabled() && $Host->isSatelliteHost() === false):
                                                echo h($this->Time->format($Servicestatus->getNextCheck(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')));
                                            else:
                                                echo __('n/a');

                                            endif;
                                            ?>
                                        </td>
                                        <td><?php echo h($Servicestatus->getOutput()); ?></td>
                                        <td class="width-50">
                                            <div class="btn-group">
                                                <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                    <a href="<?php echo Router::url([
                                                        'controller' => 'services',
                                                        'action' => 'edit',
                                                        $Service->getId()
                                                    ]); ?>"
                                                       class="btn btn-default">
                                                        &nbsp;<i class="fa fa-cog"></i>&nbsp
                                                    </a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0);" class="btn btn-default">&n
                                                        <i class="fa fa-cog"></i>&nbsp;</a>
                                                <?php endif; ?>
                                                <a href="javascript:void(0);" data-toggle="dropdown"
                                                   class="btn btn-default dropdown-toggle"><span
                                                            class="caret"></span></a>
                                                <ul class="dropdown-menu pull-right">
                                                    <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url([
                                                                'controller' => 'services',
                                                                'action' => 'edit',
                                                                $Service->getId()
                                                            ]); ?>">
                                                                <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('deactivate') && $allowEdit): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url([
                                                                'controller' => 'services',
                                                                'action' => 'deactivate',
                                                                $Service->getId()
                                                            ]); ?>
                                                            "><i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                        <li>
                                                            <?php echo $this->AdditionalLinks->renderAsListItems(
                                                                $additionalLinksList,
                                                                $Service->getId()
                                                            ); ?>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('delete') && $allowEdit): ?>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> ' . __('Delete'), ['controller' => 'services', 'action' => 'delete', $Service->getId()], ['class' => 'txt-color-red', 'escape' => false], __('Are you sure you want to delete this service?')); ?>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($all_services)): ?>
                            <div class="noMatch">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                </center>
                            </div>
                        <?php endif; ?>
                        <div class="padding-top-10"></div>
                        <div class="row">
                            <div class="col-xs-12 col-md-2 text-muted">
                                <center><span id="selectionCount"></span></center>
                            </div>
                            <div class="col-xs-12 col-md-2 "><span id="selectAll" class="pointer"><i
                                            class="fa fa-lg fa-check-square-o"></i> <?php echo __('Select all'); ?></span>
                            </div>
                            <div class="col-xs-12 col-md-2"><span id="untickAll" class="pointer"><i
                                            class="fa fa-lg fa-square-o"></i> <?php echo __('Undo selection'); ?></span>
                            </div>

                            <div class="col-xs-12 col-md-2">
                                <?php if ($this->Acl->hasPermission('copy')): ?>
                                    <a href="javascript:void(0);" id="copyAll">
                                       <i class="fa fa-lg fa-files-o"></i> <?php echo __('Copy'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <?php if ($this->Acl->hasPermission('delete')): ?>
                                    <a href="javascript:void(0);" id="deleteAll" class="txt-color-red"
                                       style="text-decoration: none;"> <i
                                                class="fa fa-lg fa-trash-o"></i> <?php echo __('Delete'); ?></a>
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <div class="btn-group">
                                    <a href="javascript:void(0);" class="btn btn-default"><?php echo __('More'); ?></a>
                                    <a href="javascript:void(0);" data-toggle="dropdown"
                                       class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo Router::url(array_merge(['controller' => 'services', 'action' => 'listToPdf'], $this->params['named'])); ?>/.pdf"
                                               id="listAsPDF"><i
                                                        class="fa fa-file-pdf-o"></i> <?php echo __('List as PDF'); ?>
                                            </a>
                                        </li>
                                        <?php if ($this->Acl->hasPermission('edit')): ?>
                                            <li>
                                                <a href="javascript:void(0);" data-toggle="modal"
                                                   data-target="#nag_command_reschedule"><i
                                                            class="fa fa-refresh"></i> <?php echo __('Reset check time'); ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" data-toggle="modal"
                                                   data-target="#nag_command_disable_notifications"><i
                                                            class="fa fa-envelope-o"></i> <?php echo __('Disable notification'); ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" data-toggle="modal"
                                                   data-target="#nag_command_enable_notifications"><i
                                                            class="fa fa-envelope"></i> <?php echo __('Enable notifications'); ?>
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="javascript:void(0);" style="text-decoration: none; color:#333;"
                                                   data-toggle="modal" data-target="#nag_command_schedule_downtime"><i
                                                            class="fa fa-clock-o"></i> <?php echo __('Set planned maintenance times'); ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" style="text-decoration: none; color:#333;"
                                                   data-toggle="modal" data-target="#nag_command_ack_state"><i
                                                            class="fa fa-user"></i> <?php echo __('Acknowledge status'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                            <!-- hidden fields for multi language -->
                        </div>

                        <div style="padding: 5px 10px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_info" style="line-height: 32px;"
                                         id="datatable_fixed_column_info"><?php echo $this->Paginator->counter(__('Page') . ' {:page} ' . __('of') . ' {:pages}, ' . __('Total') . ' {:count} ' . __('entries')); ?></div>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <div class="dataTables_paginate paging_bootstrap">
                                        <?php echo $this->Paginator->pagination([
                                            'ul' => 'pagination',
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>


<div class="modal fade" id="nag_command_reschedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Reset check time '); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('nag_command', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <center>
                        <?php echo __('Reset check time now'); ?>
                    </center>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submitRescheduleService" data-dismiss="modal">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_schedule_downtime" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Set planned maintenance times'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="txt-color-red padding-bottom-20" id="validationErrorServiceDowntime"
                         style="display:none;"><i
                                class="fa fa-exclamation-circle"></i> <?php echo __('Please enter a valide date'); ?>
                    </div>
                    <?php
                    echo $this->Form->create('CommitServiceDowntime', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment') . ':']); ?>

                    <!-- from -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label"
                               for="CommitServiceDowntimeFromDate"><?php echo __('From'); ?>:</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitServiceDowntimeFromDate" value="<?php echo date('d.m.Y'); ?>"
                                   class="form-control" name="data[CommitServiceDowntime][from_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitServiceDowntimeFromTime" value="<?php echo date('h:m'); ?>"
                                   class="form-control" name="data[CommitServiceDowntime][from_time]">
                        </div>
                    </div>

                    <!-- to -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label"
                               for="CommitServiceDowntimeToDate"><?php echo __('To'); ?>:</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitServiceDowntimeToDate"
                                   value="<?php echo date('d.m.Y', strtotime('+3 days')); ?>" class="form-control"
                                   name="data[CommitServiceDowntime][to_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitServiceDowntimeToTime" value="<?php echo date('h:m'); ?>"
                                   class="form-control" name="data[CommitServiceDowntime][to_time]">
                        </div>
                    </div>

                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submitCommitServiceDowntime">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_ack_state" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Acknowledge Service status'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('CommitServiceAck', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment') . ':']); ?>
                    <?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __('Sticky'), 'wrapInput' => 'col-md-offset-2 col-md-10']); ?>
                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitServiceAck">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_disable_notifications" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Disable notifications'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('disableNotifications', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <center>
                        <span class="hintmark">
                            <?php echo __('Yes, i want temporarily <strong>disable</strong> notifications.'); ?>
                        </span>
                    </center>

                    <div class="padding-left-10 padding-top-10">
                        <span class="note hintmark_before"><?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine'); ?></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitDisableNotifications">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_enable_notifications" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Disable notifications'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('enableNotifications', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <center>
                        <span class="hintmark">
                            <?php echo __('Yes, i want temporarily <strong>enable</strong> notifications.'); ?>
                        </span>
                    </center>

                    <div class="padding-left-10 padding-top-10">
                        <span class="note hintmark_before"><?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine'); ?></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitEnableNotifications">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<input type="hidden" id="delete_message_h1" value="<?php echo __('Attention!'); ?>"/>
<input type="hidden" id="delete_message_h2"
       value="<?php echo __('Do you really want delete the selected services?'); ?>"/>
<input type="hidden" id="message_yes" value="<?php echo __('Yes'); ?>"/>
<input type="hidden" id="message_no" value="<?php echo __('No'); ?>"/>
