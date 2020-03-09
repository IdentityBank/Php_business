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

################################################################################
# Class(es)                                                                    #
################################################################################

class IdbDataAsset extends AssetBundle
{

    public $sourcePath = '@app/themes/adminlte2/views/assets';

    public $css = [];

    public $js = [];

    public function getAssetUrl()
    {
        return $this->baseUrl . '/';
    }

    public function formAssets()
    {
        $this->js [] = 'js/sortable.min.js';
        $this->js[] = 'js/dataForm.js';
    }

    public function formUpdateAssets()
    {
        $this->js [] = 'js/sortable.min.js';
        $this->js[] = 'js/dataFormUpdate.js';
    }

    public function createSetAssets()
    {
        $this->js [] = 'js/sortable.min.js';
        $this->js [] = 'js/lodash.js';
        $this->js [] = 'js/helpers/deepDiff.js';
        $this->js [] = 'js/helpers/array.js';
        $this->js [] = 'js/helpers/uuid.js';
        $this->js [] = 'js/helpers/dataTypes.js';
        $this->js [] = 'js/creator.js';
    }

    public function showAllAssets()
    {
        $this->css [] = 'css/pagination.css';
        $this->js [] = 'js/helpers/pagination.js';
        $this->js [] = 'js/dataTable.js';
    }

    public function logTableAssets()
    {
        $this->js [] = 'js/logTable.js';
    }
}

################################################################################
#                                End of file                                   #
################################################################################
