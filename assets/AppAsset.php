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

class AppAsset extends AssetBundle
{

    public $sourcePath = '@app/views/assets';
    public $css =
        [
            'css/site.css',
        ];
    public $js =
        [
        ];
    public $depends =
        [
            'app\assets\CommonAsset',
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ];

    public function paymentsAssets()
    {
        $this->css[] = 'css/bootstrap-nav-tabs.css';
        $this->css[] = 'https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/2.1.0/adyen.css';
        $this->js [] = 'https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/2.1.0/adyen.js';
        $this->js [] = 'js/collectPayment.js';
        $this->js [] = 'js/tabSelect.js';
    }
}

################################################################################
#                                End of file                                   #
################################################################################
