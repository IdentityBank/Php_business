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

namespace app\helpers;

################################################################################
# Use(s)                                                                       #
################################################################################

use idbyii2\models\db\BusinessAuthlog;
use idbyii2\models\db\BusinessUserData;
use Yii;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Class BusinessPortalHelper
 *
 * @package app\helpers
 */
class BusinessPortalHelper
{

    /**
     * Verify business data
     */
    public static function verifyBusinessData()
    {
        $controller = Yii::$app->controller;
        $keys = [];

        $models = BusinessUserData::find()->where(['uid' => Yii::$app->user->identity->id])->all();

        if (!count($models)) {
            BusinessAuthlog::logout(Yii::$app->user->id);
            Yii::$app->user->logout();

            return $controller->goHome();
        }

        foreach ($models as $model) {
            $keys[$model->key] = $model->value;
        }

        if (
            !array_key_exists('billingFirstName', $keys) or
            !array_key_exists('billingLastName', $keys) or
            !array_key_exists('billingAddressLine1', $keys) or
            !array_key_exists('billingAddressLine2', $keys) or
            !array_key_exists('billingCity', $keys) or
            !array_key_exists('billingPostcode', $keys) or
            !array_key_exists('billingRegion', $keys) or
            !array_key_exists('billingCountry', $keys) or
            !array_key_exists('billingName', $keys) or
            !array_key_exists('billingRegistrationNumber', $keys) or
            !array_key_exists('billingVat', $keys)
        ) {
            return $controller->redirect(['/idbuser/profile/addbilling']);
        }
    }
}

################################################################################
#                                End of file                                   #
################################################################################
