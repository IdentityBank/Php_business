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

class AdminLte2Asset extends AssetBundle
{

    public $sourcePath = '@app/themes/adminlte2/views/assetsAdminLTE';

    public $jsOptions = [
        'position' => View::POS_END
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'app\assets\CommonAsset',
    ];
    public $js = [
        'bower_components/bootstrap/dist/js/bootstrap.min.js',
        'dist/js/adminlte.min.js',
    ];
    public $css = [
        'bower_components/bootstrap/dist/css/bootstrap.min.css',
        'dist/css/AdminLTE.min.css',
        'dist/css/skins/_all-skins.min.css',
    ];

    public function getAssetUrl()
    {
        return $this->baseUrl . '/';
    }

    public function loadIdbSkin()
    {
        $this->css[] = 'idb/css/skins/skin-idb.css';
    }

    public function layoutError($view)
    {
        $this->layoutMain($view);
    }

    public function layoutMain($view)
    {
        // CSS
        $this->css[] = 'plugins/bootstrap-slider/slider.css';
        $this->css[] = 'bower_components/font-awesome/css/font-awesome.min.css';
        $this->css[] = 'bower_components/Ionicons/css/ionicons.min.css';
        $this->css[] = '//use.fontawesome.com/releases/v5.7.1/css/all.css';
        $this->css[] = '//use.fontawesome.com/releases/v5.7.1/css/v4-shims.css';
    }

    public function chartAssets()
    {
        $this->js []= 'bower_components/chart.js';
    }

    public function layoutDataTable($view)
    {
        // CSS
        $this->css[] = 'bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css';

        // JS
        $this->js[] = 'bower_components/datatables.net/js/jquery.dataTables.min.js';
        $this->js[] = 'bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js';
    }

    public function layoutForms($view)
    {
        // CSS
        $this->css[] = 'plugins/iCheck/all.css';

        // JS
        $this->js[] = 'plugins/iCheck/icheck.min.js';
    }

    public function layoutDataGrid()
    {
        // CSS
        $this->css [] = 'bower_components/datatables.net-bs/css/dataTables.bootstrap.css';

        // JS
        $this->js [] = 'bower_components/datatables.net/js/jquery.dataTables.js';
        $this->js [] = 'bower_components/datatables.net-bs/js/dataTables.bootstrap.js';
    }
}

################################################################################
#                                End of file                                   #
################################################################################
