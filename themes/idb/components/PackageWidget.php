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

namespace app\themes\idb\components;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\helpers\Translate;
use yii\base\Widget;
use yii\helpers\Html;

################################################################################
# Class(es)                                                                    #
################################################################################

class PackageWidget extends Widget
{

    public $name = 'IDB';
    public $priceCurrency = '';
    public $priceValue = '';
    public $pricePeriod = '';
    public $included = [];
    public $excluded = [];
    public $id = '';

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $body = '
        <style>
            div.package {
              border-radius: 15px;
              border: 2px solid #73AD21;
              color: black;
              background-color: white;
              padding: 15px;
            }
        </style>
        ';

        $body .= '
            <div class="package">
                <h1 class="page-header" align="center">
                    <strong>' . Html::encode($this->name) . '</strong>
                </h1>
                <h2 class="page-header" align="center">
                    <small style="font-size: 50px;"><sup>' . $this->priceCurrency . '</sup><strong>' . $this->priceValue
            . '</strong><sub style="font-size: 15px;">/' . $this->pricePeriod . '</sub></small>
                </h2>
                <h2 class="page-header" align="center">
                <label>
                    <input id="package_' . $this->name . '" type="radio" name="idb_package" value="' . $this->id . '" required/>&nbsp;
                    ' . Html::button(
                Translate::_('business', 'I would like this Service Plan.'),
                ['class' => 'btn btn-package btn-flat input-block-level ']
            ) . '
                </label>
                </h2>
            </div>
            <div>&nbsp;</div>
        ';
        if (
            is_array($this->included) && is_array($this->excluded)
            && (count($this->included) + count($this->excluded)) > 0
        ) {
            $body .= '<div class="package">';
            foreach ($this->included as $item) {
                $body .= '<p><i style="color: rgb(76, 174, 76);" class="glyphicon glyphicon-ok">&nbsp;</i>' . $item
                    . '</p>';
            }
            foreach ($this->excluded as $item) {
                $body .= '<p><i style="color: rgb(215, 57, 37);" class="glyphicon glyphicon-remove">&nbsp;</i>' . $item
                    . '</p>';
            }
            $body .= '</div>';
        }

        return $body;
    }
}

################################################################################
#                                End of file                                   #
################################################################################
