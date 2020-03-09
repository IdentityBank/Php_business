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

namespace app\modules\mfarecovery\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\helpers\BusinessConfig;
use idbyii2\controllers\MfaRecoveryAbstract;
use idbyii2\enums\EmailActionType;
use idbyii2\models\db\BusinessSignup;
use idbyii2\models\db\BusinessUserData;
use Yii;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Default controller for the `mfarecovery` module
 */
class WizardController extends MfaRecoveryAbstract
{

    /**
     * @param $action
     *
     * @return bool
     * @throws \yii\base\ExitException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->captchaEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaEnabled();
        $this->emailAction = EmailActionType::BUSINESS_MFA_RECOVERY;

        return parent::beforeAction($action);
    }

    /**
     * @return string
     */
    protected function generateVerificationCodeStatic()
    {
        return BusinessSignup::generateVeryficationCodeStatic();
    }

    /**
     * @param $id
     *
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    protected function deleteMfa($id)
    {
        BusinessUserData::find()->where(
            [
                'uid' => $id,
                'key_hash' => (new BusinessUserData())->getKeyHash($id, 'mfa')
            ]
        )->one()->delete();
    }

    /**
     * @return array
     */
    protected function getMandatoryParameters()
    {
        try {
            $firstName = BusinessUserData::getUserDataByKeys(
                Yii::$app->user->identity->id,
                ['firstname']
            )[0];

            $lastName = BusinessUserData::getUserDataByKeys(
                Yii::$app->user->identity->id,
                ['lastname']
            )[0];

            $businessName = BusinessUserData::getUserDataByKeys(
                Yii::$app->user->identity->id,
                ['name']
            )[0];

            return [
                'firstName' => $firstName->value,
                'lastName' => $lastName->value,
                'businessName' => $businessName->value
            ];
        } catch(\Exception $e) {
            Yii::error($e->getMessage());
            return [];
        }
    }
}

################################################################################
#                                End of file                                   #
################################################################################
