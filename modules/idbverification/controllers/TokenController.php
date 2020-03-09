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

namespace app\modules\idbverification\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use app\helpers\BusinessConfig;
use app\helpers\Translate;
use Exception;
use idbyii2\enums\EmailActionType;
use idbyii2\helpers\EmailTemplate;
use idbyii2\helpers\PasswordToken;
use idbyii2\helpers\Sms;
use idbyii2\helpers\VerificationCode;
use idbyii2\models\db\BusinessSignup;
use idbyii2\models\db\BusinessUserData;
use idbyii2\models\form\EmailVerificationForm;
use idbyii2\models\form\PasswordRecoveryForm;
use idbyii2\models\form\SmsVerificationForm;
use idbyii2\models\identity\IdbBusinessUser;
use Yii;
use yii\helpers\ArrayHelper;

################################################################################
# Class(es)                                                                    #
################################################################################

class TokenController extends IdbController
{
    private static $params = [
        'menu_active_section' => '[menu][account_administration]',
        'menu_active_item' => '[menu][account][user_profile]',
    ];

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $model = new PasswordRecoveryForm();
        $model->captchaEnabled =
            BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaEnabled();

        if (!empty($request->post('PasswordRecoveryForm'))) {
            $passwordTokenHelper = new PasswordToken();
            $model->load($request->post());
            if ($model->validate()) {
                $passwordToken = $passwordTokenHelper->decodeToken($model->token);
                if (
                    (!empty($passwordToken))
                    && (
                        $model->email === $passwordToken['email']
                        && $model->mobile === $passwordToken['mobile']
                    )
                ) {
                    $identity = IdbBusinessUser::findIdentity($passwordToken['uid']);
                    if (!empty($identity)) {
                        $verificationSession  = Yii::$app->session->get('verificationModuleToken', []);
                        $verificationSession['status'] = 'success';
                        Yii::$app->session->set('verificationModuleToken', $verificationSession);

                        return $this->redirect(ArrayHelper::getValue($verificationSession, 'url', '/'));
                    }
                } else {
                    $model->addError('token', Translate::_('business', 'Provided data is incorrect.'));
                }
            }
        }

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'index',
                        'contentParams' => compact('model')
                    ]
                )
            ]
        );
    }
}



################################################################################
#                                End of file                                   #
################################################################################
