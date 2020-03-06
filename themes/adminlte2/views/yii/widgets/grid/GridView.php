<?php
# * ********************************************************************* *
# *                                                                       *
# *   Business Portal                                                     *
# *   This file is part of business. This project may be found at:        *
# *   https://github.com/IdentityBank/Php_business.                       *
# *                                                                       *
# *   Copyright (C) 2020 by Identity Bank. All Rights Reserved.           *
# *   https://www.identitybank.eu - You belong to you                     *
# *                                                                       *
# *   This program is free software: you can redistribute it and/or       *
# *   modify it under the terms of the GNU Affero General Public          *
# *   License as published by the Free Software Foundation, either        *
# *   version 3 of the License, or (at your option) any later version.    *
# *                                                                       *
# *   This program is distributed in the hope that it will be useful,     *
# *   but WITHOUT ANY WARRANTY; without even the implied warranty of      *
# *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the        *
# *   GNU Affero General Public License for more details.                 *
# *                                                                       *
# *   You should have received a copy of the GNU Affero General Public    *
# *   License along with this program. If not, see                        *
# *   https://www.gnu.org/licenses/.                                      *
# *                                                                       *
# * ********************************************************************* *

################################################################################
# Namespace                                                                    #
################################################################################

namespace app\themes\adminlte2\views\yii\widgets\grid;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\helpers\Translate;
use yii\helpers\Html;
use yii\web\AssetBundle;

################################################################################
# Class(es)                                                                    #
################################################################################

class GridView extends \yii\grid\GridView
{

    public $dataColumnClass = 'app\themes\adminlte2\views\yii\widgets\grid\DataColumn';
    public $tableOptionsClass = ['class' => 'table dataTable'];
    public $layout = '
    <div class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
            <div class="col-sm-12">
                {items}
            </div>
        </div>
        <div class="row">&ensp;</div>
        <div class="row">
            <div class="col-sm-5">
                <div class="dataTables_info" role="status" aria-live="polite">
                    {summary}
                </div>
            </div>
            <div class="col-sm-7">
                <div class="dataTables_paginate paging_simple_numbers">
                    {pager}
                </div>
            </div>
        </div>
    </div>';
    public $bordered = true;
    public $condensed = false;
    public $striped = false;
    public $hover = true;

    public function init()
    {
        $this->summary = Translate::_('business', 'Showing {begin} - {end} of {totalCount} items.');
        if (empty($this->tableOptions['class'])) {
            if (is_array($this->tableOptions)) {
                $this->tableOptions = array_merge($this->tableOptions, $this->tableOptionsClass);
            }
        } else {
            $this->tableOptions['class'] .= $this->tableOptionsClass;
        }
        if ($this->bordered) {
            Html::addCssClass($this->tableOptions, 'table-bordered');
        }
        if ($this->condensed) {
            Html::addCssClass($this->tableOptions, 'table-condensed');
        }
        if ($this->striped) {
            Html::addCssClass($this->tableOptions, 'table-striped');
        }
        if ($this->hover) {
            Html::addCssClass($this->tableOptions, 'table-hover');
        }
        parent::init();
    }

    public function run()
    {
        AssetBundle::register($this->getView());
        parent::run();
    }

    public function renderPager()
    {
        return Html::tag('div', parent::renderPager(), ['class' => 'dataTables_paginate paging_simple_numbers']);
    }
}

################################################################################
#                                End of file                                   #
################################################################################
