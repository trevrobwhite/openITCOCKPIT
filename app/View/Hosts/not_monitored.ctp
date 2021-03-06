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

use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\Hosttemplate;

$this->Paginator->options(['url' => $this->params['named']]);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-desktop fa-fw "></i>
            <?php echo __('Hosts') ?>
            <span>>
                <?php echo __('Not monitored'); ?>
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">

    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php
                        if ($this->Acl->hasPermission('add')):
                            echo $this->Html->link(__('New'), '/' . $this->params['controller'] . '/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']);
                            echo " "; //Need a space for nice buttons
                        endif;
                        echo $this->Html->link(__('Filter'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter']);
                        if ($isFilter):
                            echo " "; //Need a space for html
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        echo $this->AdditionalLinks->renderAsLinks($additionalLinksTop);
                        ?>
                    </div>
                    <div class="widget-toolbar hidden-mobile" role="menu">
                        <a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown">
                            <i class="fa fa-lg fa-table"></i>
                        </a>
                        <ul class="dropdown-menu arrow-box-up-right pull-right">
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left"
                                   class="select_datatable text-left" my-column="2">
                                    <input type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Edit'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left"
                                   class="select_datatable text-left" my-column="3">
                                    <input type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Graph'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left"
                                   class="select_datatable text-left" my-column="4">
                                    <input type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Passive'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left"
                                   class="select_datatable text-left" my-column="5">
                                    <input type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Host Name'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left"
                                   class="select_datatable text-left" my-column="6">
                                    <input type="checkbox" class="pull-left"/> &nbsp; <?php echo __('IP-Address'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left"
                                   class="select_datatable text-left" my-column="7">
                                    <input type="checkbox" class="pull-left"/> &nbsp; <?php echo __('State since'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left"
                                   class="select_datatable text-left" my-column="8">
                                    <input type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Last check'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left"
                                   class="select_datatable text-left" my-column="9">
                                    <input type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Output'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left"
                                   class="select_datatable text-left" my-column="10">
                                    <input type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Instance'); ?>
                                </a>
                            </li>

                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">

                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-desktop"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Hosts'); ?></h2>
                    <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                        <?php if ($this->Acl->hasPermission('index')): ?>
                            <li class="">
                                <a href="<?php echo Router::url(array_merge(['controller' => 'hosts', 'action' => 'index'], $this->params['named'])); ?>"><i class="fa fa-stethoscope"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Monitored'); ?> </span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="active">
                            <a href="<?php echo Router::url(array_merge(['controller' => 'hosts', 'action' => 'notMonitored'], $this->params['named'])); ?>"><i class="fa fa-user-md"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Not monitored'); ?> </span></a>
                        </li>
                        <?php if ($this->Acl->hasPermission('disabled')): ?>
                            <li>
                                <a href="<?php echo Router::url(array_merge(['controller' => 'hosts', 'action' => 'disabled'], $this->params['named'])); ?>"><i class="fa fa-power-off"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Disabled'); ?> </span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('index', 'DeletedHosts')): ?>
                            <li>
                                <a href="<?php echo Router::url(array_merge(['controller' => 'deleted_hosts', 'action' => 'index'], $this->params['named'])); ?>"><i class="fa fa-trash-o"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Deleted'); ?> </span></a>
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
                            <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort"></th>
                                    <th class="no-sort"><?php echo __('Host status'); ?></th>
                                    <th class="no-sort text-center"><i class="fa fa-area-chart fa-lg"></i></th>
                                    <th class="no-sort text-center"><strong>P</strong></th>
                                    <th class="no-sort">
                                        <?php
                                        echo $this->Utils->getDirection($order, 'Host.name');
                                        echo $this->Paginator->sort('Host.name', 'Hostname');
                                        ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php
                                        echo $this->Utils->getDirection($order, 'Host.address');
                                        echo $this->Paginator->sort('Host.address', 'IP address');
                                        ?>
                                    </th>
                                    <th class="no-sort tableStatewidth"><?php echo __('State since'); ?></th>
                                    <th class="no-sort tableStatewidth"><?php echo __('Last check'); ?></th>
                                    <th class="no-sort"><?php echo __('Output'); ?></th>
                                    <th class="no-sort">
                                        <?php
                                        echo $this->Utils->getDirection($order, 'Host.satellite_id');
                                        echo $this->Paginator->sort('Host.satellite_id', __('Instance'));
                                        ?>
                                    </th>
                                    <th class="no-sort text-center editItemWidth"><i class="fa fa-gear fa-lg"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($all_hosts as $host):
                                    $Host = new Host($host);
                                    $Hosttemplate = new Hosttemplate($host);
                                    $hasEditPermission = false;
                                    if ($hasRootPrivileges === true):
                                        $hasEditPermission = true;
                                    else:
                                        if ($this->Acl->isWritableContainer($Host->getContainerIds())):
                                            $hasEditPermission = true;
                                        endif;
                                    endif;
                                    ?>
                                    <tr>
                                        <td class="text-center width-5">
                                            <?php if ($this->Acl->hasPermission('edit') && $hasEditPermission): ?>
                                                <input type="checkbox" class="massChange"
                                                       hostname="<?php echo h($Host->getHostname()); ?>"
                                                       value="<?php echo $Host->getId(); ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center width-75">
                                            <?php
                                            $href = 'javascript:void(0);';
                                            if ($this->Acl->hasPermission('browser')):
                                                $href = Router::url(['action' => 'browser', $Host->getId()]);
                                            endif;
                                            ?>
                                            <a href="<?php echo $href; ?>" class="btn btn-primary status-circle"
                                               style="padding:0;"></a>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($this->Monitoring->checkForHostGraph($Host->getUuid())): ?>
                                                <?php
                                                $graphHref = 'javascript:void(0);';
                                                if ($this->Acl->hasPermission('serviceList', 'services')):
                                                    $graphHref = Router::url([
                                                        'controller' => 'services',
                                                        'action' => 'serviceList',
                                                        $Host->getId()
                                                    ]);
                                                endif;
                                                ?>
                                                <a class="txt-color-blueDark" href="<?php echo $graphHref; ?>">
                                                    <i class="fa fa-area-chart fa-lg "></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($Host->isActiveChecksEnabled() || $Host->getSatelliteId() > 0):
                                                if ($Host->isActiveChecksEnabled() == false || $Host->getSatelliteId() > 0): ?>
                                                    <strong title="<?php echo __('Passively transferred host'); ?>">P</strong>
                                                <?php endif;
                                            elseif ($Hosttemplate->isActiveChecksEnabled() == false): ?>
                                                <strong title="<?php echo __('Passively transferred host'); ?>">P</strong>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($this->Acl->hasPermission('browser')): ?>
                                                <a href="<?php echo Router::url(['action' => 'browser', $Host->getId()]); ?>">
                                                    <?php echo h($Host->getHostname()); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo h($Host->getHostname()); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo h($Host->getAddress()); ?></td>
                                        <td><?php echo __('n/a'); ?></td>
                                        <td><?php echo __('n/a'); ?></td>
                                        <td><?php echo __('n/a'); ?></td>
                                        <td>
                                            <?php
                                            if ($Host->getSatelliteId() == 0):
                                                echo $masterInstance;
                                            else:
                                                echo $SatelliteNames[$Host->getSatelliteId()];
                                            endif;
                                            ?>
                                        </td>
                                        <td class="width-50">
                                            <div class="btn-group">
                                                <?php if ($this->Acl->hasPermission('edit') && $hasEditPermission): ?>
                                                    <a href="<?php echo Router::url(['action' => 'edit', $Host->getId()]); ?>"
                                                       class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp
                                                    </a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0);" class="btn btn-default">&nbsp
                                                        <i class="fa fa-cog"></i>&nbsp
                                                    </a>
                                                <?php endif; ?>
                                                <a href="javascript:void(0);" data-toggle="dropdown"
                                                   class="btn btn-default dropdown-toggle">
                                                    <span class="caret"></span>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <?php if ($this->Acl->hasPermission('edit') && $hasEditPermission): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url([
                                                                'controller' => $this->params['controller'],
                                                                'action' => 'edit',
                                                                '_controller' => $this->params['controller'],
                                                                '_action' => $this->params['action'],
                                                                $Host->getId()
                                                            ]); ?>">
                                                                <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('sharing') && $hasEditPermission): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url(['action' => 'sharing', $Host->getId()]); ?>">
                                                                <i class="fa fa-sitemap fa-rotate-270"></i>
                                                                <?php echo __('Sharing'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('deactivate') && $hasEditPermission): ?>
                                                        <li>
                                                            <a href="<?php Router::url(['action' => 'deactivate', $Host->getId()]); ?>">
                                                                <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                                        <li>

                                                            <a href="<?php echo Router::url([
                                                                'controller' => 'services',
                                                                'action' => 'serviceList',
                                                                $Host->getId()]); ?>">
                                                                <i class="fa fa-list"></i> <?php echo __('Service list'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, $Host->getId()); ?>
                                                    <?php if ($this->Acl->hasPermission('delete') && $hasEditPermission): ?>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> ' . __('Delete'), [
                                                                'controller' => 'hosts',
                                                                'action' => 'delete',
                                                                $Host->getId()
                                                            ], [
                                                                'class' => 'txt-color-red',
                                                                'escape' => false
                                                            ]); ?>
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
                        <?php if (empty($all_hosts)): ?>
                            <div class="noMatch">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                </center>
                            </div>
                        <?php endif; ?>

                        <br/>
                        <?php echo $this->element('host_mass_changes'); ?>

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
