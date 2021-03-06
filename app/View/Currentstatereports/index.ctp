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
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-file-image-o fa-fw "></i>
            <?php echo __('Adhoc Reports'); ?>
            <span>>
                <?php echo __('Current State Report'); ?>
			</span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Create Current State Report'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Currentstatereport', [
                'class' => 'form-horizontal clear',
            ]);

            echo $this->Form->input('Service', [
                'options'          => Hash::combine($services,
                    '{n}.Service.id'
                    , [
                        '%s/%s', '{n}.Host.name', '{n}.{n}.ServiceDescription',
                    ],
                    '{n}.Host.name'
                ),
                'class'            => 'chosen',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => __('Services'),
                'data-placeholder' => __('Please choose a service'),
                'wrapInput'        => [
                    'tag'   => 'div',
                    'class' => 'col col-xs-10',
                ],
            ]);

            echo $this->Form->input(__('current_state'), [
                'label'    => __('State filter'),
                'type'     => 'select',
                'multiple' => 'checkbox',
                'class'    => 'font-sm padding-right-10',
                'options'  => [
                    $this->Status->humanSimpleServiceStatus(0),
                    $this->Status->humanSimpleServiceStatus(1),
                    $this->Status->humanSimpleServiceStatus(2),
                    $this->Status->humanSimpleServiceStatus(3),
                ],
                'default'  => [0, 1, 2, 3],
            ]);

            echo $this->Form->input('report_format', [
                    'options'          => ['pdf' => __('PDF'), 'html' => __('HTML')],
                    'data-placeholder' => __('Please select...'),
                    'class'            => 'chosen',
                    'label'            => __('Report format'),
                    'style'            => 'width:100%;',
                ]
            );
            ?>
        </div>
        <?php
        echo $this->Form->formActions(__('Create'));
        ?>
    </div>
</div>
</div>
