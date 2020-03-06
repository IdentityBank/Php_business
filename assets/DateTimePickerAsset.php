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

namespace app\assets;

################################################################################
# Use(s)                                                                       #
################################################################################

use yii\web\AssetBundle;
use yii\web\View;

################################################################################
# Class(es)                                                                    #
################################################################################

class DateTimePickerAsset extends AssetBundle
{

    public $sourcePath = '@app/themes/adminlte2/views/assetsAdminLTE';

    public $jsOptions = [
        'position' => View::POS_END,
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];

    public $js = [
        'bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
        'bower_components/moment/min/moment.min.js',
        'bower_components/bootstrap-daterangepicker/daterangepicker.js',
        'plugins/timepicker/bootstrap-timepicker.min.js'
    ];

    public $css = [
        'bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css',
        'bower_components/bootstrap-daterangepicker/daterangepicker.css',
        'plugins/timepicker/bootstrap-timepicker.min.css'
    ];

}

################################################################################
#                                End of file                                   #
################################################################################
