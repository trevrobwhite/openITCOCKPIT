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
?>
<?php
$this->Paginator->options(array('url' => $this->params['named']));
$filter = "/";
foreach($this->params->named as $key => $value){
	$filter.= $key.":".$value."/";
}
?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-gear fa-fw "></i>
				<?php echo __('Services'); ?>
			<span>>
				<?php echo __('Not monitored'); ?>
			</span>
		</h1>
	</div>
</div>

<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" >
				<header>
					<div class="widget-toolbar" role="menu">
						<?php
						if($this->Acl->hasPermission('add')):
							echo $this->Html->link(__('New'), '/'.$this->params['controller'].'/add', array('class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus'));
							echo " "; //Space for html
						endif;
						echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search'));
						if($isFilter):
							echo " "; //Space for html
							echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'));
						endif;
						?>
						</div>

					<div class="jarviswidget-ctrls" role="menu">
					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-cog"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Services not monitored'); ?> </h2>
					<ul class="nav nav-tabs pull-right" id="widget-tab-1">
						<?php if($this->Acl->hasPermission('index')): ?>
							<li class="">
								<a href="/services/index<?php echo $filter; ?>"> <i class="fa fa-stethoscope"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Monitored'); ?></span> </a>
							</li>
						<?php endif; ?>
						<li class="active">
							<a href="/services/notMonitored<?php echo $filter; ?>">
								<i class="fa fa-user-md"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Not monitored'); ?></span>
							</a>
						</li>
						<?php if($this->Acl->hasPermission('disabled')): ?>
							<li class="">
								<a href="/services/disabled<?php echo $filter; ?>">
									<i class="fa fa-plug"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Disabled'); ?></span>
								</a>
							</li>
						<?php endif; ?>
					</ul>

				</header>

				<div>
					<div class="widget-body no-padding">
						<?php
						$options = [ 'avoid_cut' => true];
						echo $this->ListFilter->renderFilterbox($filters, $options, '<i class="fa fa-search"></i> '.__('search'), false, false);
						?>
						<div class="mobile_table">
							<table id="host_list" class="table table-striped table-bordered smart-form" style="">
								<thead>
									<tr>
										<?php $order = $this->Paginator->param('order'); ?>
										<th class="select_datatable no-sort"><?php echo $this->Utils->getDirection($order, 'Service.servicestatus'); echo $this->Paginator->sort('Service.servicestatus', 'Servicestatus'); ?></th>
										<th class="no-sort text-center" ><strong>P</strong></th>
										<th class="no-sort"><?php echo __('Servicename'); ?></th>
										<th class="no-sort text-center editItemWidth" ><i class="fa fa-gear fa-lg"></i></th>
									</tr>
								</thead>
								<tbody>
									<?php $tmp_host_name = null; ?>
									<?php foreach($all_services as $service): ?>
										<?php
										$allowEdit = false;
										if($hasRootPrivileges === true):
											$allowEdit = true;
										else:
											if(isset($hostContainers[$service['Host']['id']])):
												if($this->Acl->isWritableContainer($hostContainers[$service['Host']['id']])):
													$allowEdit = true;
												endif;
											endif;
										endif;

										if($tmp_host_name != $service['Host']['name']):
											$tmp_host_name = $service['Host']['name'];
										?>
											<tr>
												<td class="bg-color-lightGray" colspan="12">
													<?php
													$hostHref = 'javascript:void(0);';
													if($this->Acl->hasPermission('browser')):
														$hostHref = '/hosts/browser/'.$service['Host']['id'];
													endif;
													echo $this->Status->humanHostStatus($service['Host']['uuid'], $hostHref)['html_icon']; ?><a class="padding-left-5 txt-color-blueDark" href="<?php echo $hostHref; ?>"><?php echo h($service['Host']['name']); ?> (<?php echo h($service['Host']['address']); ?>)</a>
													<?php if($this->Acl->hasPermission('serviceList')): ?>
														<a class="pull-right txt-color-blueDark" href="/services/serviceList/<?php echo $service['Host']['id']; ?>"><i class="fa fa-list" title="<?php echo __('Go to Service list'); ?>"></i></a></td>
													<?php endif; ?>
											</tr>

										<?php endif; ?>
											<tr>
												<td class="text-center width-100">
													<?php
													$href = 'javascript:void(0);';
													if($this->Acl->hasPermission('browser')):
														$href = '/services/browser/'.$service['Service']['id'];
													endif;
													echo $this->Status->humanServiceStatus($service['Service']['uuid'], $href)['html_icon'];
													?>
												</td>

												<td class="text-center width-15">
													<?php if($service['Service']['active_checks_enabled'] === '0' || $service['Servicetemplate']['active_checks_enabled'] === '0' || (isset($service['Host']['satellite_id'])) && $service['Host']['satellite_id'] > 0):?>
														<strong title="<?php echo __('Passively transferred service'); ?>">P</strong>
													<?php endif;?>
												</td>
												<td>
													<?php
													$serviceName = $service['Service']['name'];
													if($serviceName === null || $serviceName === ''):
														$serviceName = $service['Servicetemplate']['name'];
													endif;

													if($this->Acl->hasPermission('browser')):?>
														<a href="/services/browser/<?php echo $service['Service']['id']; ?>"><?php echo h($serviceName); ?></a>
													<?php else: ?>
														<?php echo h($serviceName); ?>
													<?php endif; ?>
												</td>

												<td class="width-50">
													<div class="btn-group">
														<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
															<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $service['Service']['id']; ?>" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
														<?php else: ?>
															<a href="javascript:void(0);" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
														<?php endif; ?>
														<a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
														<ul class="dropdown-menu pull-right">
															<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
																<li>
																	<a href="<?php echo Router::url(['controller' => $this->params['controller'], 'action' => 'edit', '_controller' => $this->params['controller'], '_action' => $this->params['action'], $service['Service']['id']]); ?>"><i class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
																</li>
															<?php endif; ?>
															<?php if($this->Acl->hasPermission('deactivate') && $allowEdit): ?>
																<li>
																	<a href="/<?php echo $this->params['controller']; ?>/deactivate/<?php echo $service['Service']['id']; ?>"><i class="fa fa-plug"></i> <?php echo __('Disable'); ?></a>
																</li>
															<?php endif; ?>
															<?php if($this->Acl->hasPermission('delete') && $allowEdit): ?>
																<li class="divider"></li>
																<li>
																	<?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'services', 'action' => 'delete', $service['Service']['id']], ['class' => 'txt-color-red', 'escape' => false]);?>
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
						<?php if(empty($all_services)):?>
							<div class="noMatch">
								<center>
									<span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
								</center>
							</div>
						<?php endif;?>

						<div style="padding: 5px 10px;">
							<div class="row">
								<div class="col-sm-6">
									<div class="dataTables_info" style="line-height: 32px;" id="datatable_fixed_column_info"><?php echo $this->Paginator->counter(__('Page').' {:page} '.__('of').' {:pages}, '.__('Total').' {:count} '.__('entries')); ?></div>
								</div>
								<div class="col-sm-6 text-right">
									<div class="dataTables_paginate paging_bootstrap">
										<?php echo $this->Paginator->pagination(array(
											'ul' => 'pagination'
										)); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	</div>
</section>
