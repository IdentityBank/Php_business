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

namespace app\modules\idbdata;

################################################################################
# Use(s)                                                                       #
################################################################################

use yii\base\Module;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * IdbData module definition class
 */
class IdbDataModule extends Module
{

    public $configAuditLogDefault = [
        'blowfishCost' => 1,
        'idbDataPassword' => 'password',
    ];

    public $configAuditLog = [];

    public function init()
    {
        if (is_array($this->configAuditLog)) {
            $this->configAuditLog = array_merge($this->configAuditLogDefault, $this->configAuditLog);
        } else {
            $this->configAuditLog = $this->configAuditLogDefault;
        }

        parent::init();
    }
}

################################################################################
#                                End of file                                   #
################################################################################
