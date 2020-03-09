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

namespace app\themes\adminlte2\views\yii\widgets\breadcrumbs;

################################################################################
# Class(es)                                                                    #
################################################################################

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{

    private static $visible = false;
    public $encodeLabels = false;
    public $tag = 'ol';
    public $itemTemplate = "<li>{link}</li>" . PHP_EOL;
    public $activeItemTemplate = "<li class=\"active\">{link}</li>" . PHP_EOL;
    public $homeLink = ['label' => '<i class="fa fa-home"></i>', 'url' => ['/']];

    /**
     * @return string|void
     */
    public function run()
    {
        if (self::$visible) {
            parent::run();
        }
    }
}

################################################################################
#                                End of file                                   #
################################################################################
