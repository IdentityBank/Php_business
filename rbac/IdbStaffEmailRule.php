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

namespace app\rbac;

################################################################################
# Use(s)                                                                       #
################################################################################

use Yii;

################################################################################
# Class(es)                                                                    #
################################################################################

class IdbStaffEmailRule extends IdbRule
{

    public $name = 'isIdbEmail';
    public $regex = '/(?<=@)(identitybank.eu|idbank.eu|p57b.eu)/';

    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            return (!empty(Yii::$app->user->getIdentity()->email) ? (preg_match(
                    $this->regex,
                    strtolower(trim(Yii::$app->user->getIdentity()->email))
                ) > 0) : false);
        }

        return false;
    }
}

################################################################################
#                                End of file                                   #
################################################################################
