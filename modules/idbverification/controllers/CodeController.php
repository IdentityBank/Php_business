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
use app\helpers\Translate;
use Exception;
use idbyii2\enums\EmailActionType;
use idbyii2\helpers\EmailTemplate;
use idbyii2\helpers\Sms;
use idbyii2\helpers\VerificationCode;
use idbyii2\models\db\BusinessSignup;
use idbyii2\models\db\BusinessUserData;
use idbyii2\models\form\EmailVerificationForm;
use idbyii2\models\form\SmsVerificationForm;
use Yii;
use yii\helpers\ArrayHelper;

################################################################################
# Class(es)                                                                    #
################################################################################

class CodeController extends IdbController
{
    private static $params = [
        'menu_active_section' => '[menu][account_administration]',
        'menu_active_item' => '[menu][account][user_profile]',
    ];

    /**
     * @return string|\yii\web\Response
     */
    public function actionEmail()
    {
        $request = Yii::$app->request;
        $model = new EmailVerificationForm();
        $model->captchaEnabled = false;
        $verificationSession  = Yii::$app->session->get('verificationModuleCode', []);

        if (
        $this->verifyCode(
            $request->post('code'),
            'emailCode',
            [
                'info' => Translate::_('business', 'Email code was incorrect please try again.')
            ]
        )
        ) {
            Yii::$app->session->set('tryCount', 3);

            return $this->redirect(['sms']);
        }

        $code = BusinessSignup::generateVeryficationCodeStatic();
        Yii::$app->session->set('emailCode', $code);

        $email = ArrayHelper::getValue($verificationSession, 'email', Yii::$app->user->identity->email);

        EmailTemplate::sendEmailByAction(
            EmailActionType::BUSINESS_EMAIL_VERIFICATION,
            array_merge(['code' => $code], $this->getMandatoryParameters()),
            Translate::_('business', 'Confirm code'),
            $email,
            Yii::$app->language
        );

        $code = explode('.', $code);
        $codeFirst = $code[0];
        $codeThird = $code[2];

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'email',
                        'contentParams' => compact('codeFirst', 'codeThird', 'model')
                    ]
                )
            ]
        );
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionSms()
    {
        $request = Yii::$app->request;
        $model = new SmsVerificationForm();
        $model->captchaEnabled = false;
        $verificationSession  = Yii::$app->session->get('verificationModuleCode', []);

        if (
        $this->verifyCode(
            $request->post('code'),
            'smsCode',
            [
                'info' => Translate::_('business', 'Incorrect email or SMS code'),
                'tryCount' => Yii::$app->session->get('tryCount') - 1
            ]
        )
        ) {
            $verificationSession['status'] = 'success';
            Yii::$app->session->set('verificationModuleCode', $verificationSession);

            return $this->redirect(ArrayHelper::getValue($verificationSession, 'url', '/'));
        }

        $tryCount = Yii::$app->session->get('tryCount');

        $code = BusinessSignup::generateVeryficationCodeStatic();
        Yii::$app->session->set('smsCode', $code);

        $mobile = ArrayHelper::getValue($verificationSession, 'mobile', Yii::$app->user->identity->mobile);

        Sms::sendVerificationCode(
            $mobile,
            $code
        );

        $code = explode('.', $code);
        $codeFirst = $code[0];
        $codeThird = $code[2];


        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'sms',
                        'contentParams' => compact('model', 'codeFirst', 'codeThird', 'tryCount')
                    ]
                )
            ]
        );
    }

    /**
     * @param $code
     * @param $type
     * @param $flashMessages
     *
     * @return bool
     */
    private function verifyCode($code, $type, $flashMessages)
    {
        if (empty($code)) {
            return false;
        }

        if (!empty($code) && count($code) > 11) {

            if (Yii::$app->session->get($type) == VerificationCode::parseFromArray($code)) {
                return true;
            } else {
                foreach ($flashMessages as $key => $value) {
                    Yii::$app->session->setFlash($key, $value);
                }
            }
        }

        return false;
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
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            return [];
        }
    }
}



################################################################################
#                                End of file                                   #
################################################################################
