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

class FrontAsset extends AssetBundle
{

    public $sourcePath = '@app/frontend/';

    public $js = [];

    public function getAssetUrl()
    {
        return $this->baseUrl . '/';
    }

    public function checkBrowser()
    {
        $this->js[] = 'js/idb/signup/checkBrowser.js';
    }

    public function dataForm()
    {
        $this->js[] = 'js/adminlte/idb-data/dataForm.js';
    }

    public function idbStorageUpload()
    {
        $this->js[] = 'js/adminlte/idb-storage/upload.js';
    }

    public function idbStorageDownload()
    {
        $this->js[] = 'js/adminlte/idb-storage/download.js';
    }

    public function idbStoragePreview()
    {
        $this->js[] = 'js/adminlte/idb-storage/preview.js';
    }

    public function idbUpdateMessage()
    {
        $this->js[] = 'js/adminlte/tools/updateMessage.js';
    }

    public function idbUpdateTime()
    {
        $this->js[] = 'js/adminlte/tools/updateTime.js';
    }

    public function idbUpdateDpo()
    {
        $this->js[] = 'js/adminlte/tools/updateDpo.js';
    }

    public function updateRetentionPeriod()
    {
        $this->js[] = 'js/adminlte/tools/retentionPeriod.js';
    }
}

################################################################################
#                                End of file                                   #
################################################################################
