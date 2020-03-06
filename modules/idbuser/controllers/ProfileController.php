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

namespace app\modules\idbuser\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use app\helpers\AccessManagerHelper;
use app\helpers\Translate;
use Exception;
use idbyii2\helpers\Account;
use idbyii2\helpers\IdbAccountId;
use idbyii2\helpers\IdbArrayHelper;
use idbyii2\models\db\BusinessAccount;
use idbyii2\models\db\BusinessDeleteAccount;
use idbyii2\models\db\BusinessNotification;
use idbyii2\models\db\BusinessOrganization;
use idbyii2\models\db\BusinessSignup;
use idbyii2\models\db\BusinessUserData;
use idbyii2\models\form\ChangeContactForm;
use idbyii2\models\form\ChangePasswordForm;
use idbyii2\models\form\IdbBusinessSignUpDPOForm;
use idbyii2\models\form\IdbBusinessSignUpForm;
use idbyii2\models\form\NotificationsForm;
use idbyii2\models\identity\IdbBusinessUser;
use kartik\mpdf\Pdf;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;

################################################################################
# Class(es)                                                                    #
################################################################################

class ProfileController extends IdbController
{

    private static $params = [
        'menu_active_section' => '[menu][account_administration]',
        'menu_active_item' => '[menu][account][user_profile]',
    ];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (is_array($behaviors['access']['rules'])) {
            $behaviors['access']['rules'] = [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['action_profile'],
                ],
                [
                    'allow' => true,
                    'actions' => ['edit-business-information','edit-dpo-information', 'delete-account', 'get-token', 'restore-account', 'change-contact', 'save-contact'],
                    'roles' => ['action_account_manager', 'action_organization_billing_manager'],
                ],
                [
                    'allow' => true,
                    'actions' => ['changepassword', 'passwordchanged'],
                    'roles' => ['action_profile_password'],
                ]
            ];
        }

        return $behaviors;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $accountName = ((empty(Yii::$app->user->identity->accountName)) ? '' : Yii::$app->user->identity->accountName);
        $userId = ((empty(Yii::$app->user->identity->userId)) ? '' : Yii::$app->user->identity->userId);
        $accountNumber = ((empty(Yii::$app->user->identity->accountNumber)) ? ''
            : Yii::$app->user->identity->accountNumber);
        $authKey = ((empty(Yii::$app->user->identity->authKey)) ? '' : Yii::$app->user->identity->authKey);
        $login = ((empty(Yii::$app->user->identity->login)) ? '' : Yii::$app->user->identity->login);
        $email = ((empty(Yii::$app->user->identity->email)) ? '' : Yii::$app->user->identity->email);
        $phone = ((empty(Yii::$app->user->identity->phone)) ? '' : Yii::$app->user->identity->phone);
        $businessId = Yii::$app->user->identity->getBusinessAccountId();

        $oid = $aid = null;
        $idbAccountId = IdbAccountId::parse($businessId);
        if (!empty($idbAccountId['oid'])) {
            $oid = $idbAccountId['oid'];
            $oid = BusinessOrganization::findOne($oid);
            $oid = $oid->name;
        }

        if (!empty($idbAccountId['aid'])) {
            $aid = $idbAccountId['aid'];
            $aid = BusinessAccount::findOne($aid);
            $aid = $aid->name;
        }

        $databases = AccessManagerHelper::getDatabases();

        $information = BusinessUserData::find()->where(['uid' => Yii::$app->user->identity->id])->all();

        $data = [];

        foreach ($information as $model) {
            $data[$model->key] = $model->value;
        }

        $this->view->title = Translate::_('business', 'User profile');

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'index',
                        'contentParams' => [
                            'data' => $data,
                            'businessAccounts' => $databases['businessAccounts'],
                            'businessAccountDatabases' => $databases['businessAccountDatabases'],
                            'businessAccountsNames' => $databases['businessAccountsNames'],
                            'accountName' => $accountName,
                            'login' => $login,
                            'userId' => $userId,
                            'accountNumber' => $accountNumber,
                            'authKey' => $authKey,
                            'email' => $email,
                            'phone' => $phone,
                            'oid' => $oid,
                            'aid' => $aid
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @return string|Response
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionChangepassword()
    {
        $model = new ChangePasswordForm;
        if (!empty(Yii::$app->request->post() ['ChangePasswordForm'])) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                if (!empty(Yii::$app->request->post() ['ChangePasswordForm']['oldPassword'])) {
                    $passwordValidated = Yii::$app->user->identity->validatePassword(
                        Yii::$app->request->post() ['ChangePasswordForm']['oldPassword']
                    );
                    if ($passwordValidated) {
                        $userDataModel = BusinessUserData::instantiate(['uid' => Yii::$app->user->identity->getId()]);
                        $userDataModel = BusinessUserData::findOne(
                            [
                                'uid' => Yii::$app->user->identity->getId(),
                                'key_hash' => $userDataModel->getKeyHash(Yii::$app->user->identity->getId(), 'password')
                            ]
                        );
                        if ($userDataModel) {
                            $saved = $userDataModel->updatePassword(
                                Yii::$app->request->post() ['ChangePasswordForm']['password'],
                                Yii::$app->user->identity->accountNumber
                            );
                            if (!$saved) {
                                $model->addErrors($userDataModel->getErrors());
                            } else {
                                return $this->redirect(['passwordchanged']);
                            }
                        }
                    } else {
                        $model->addError(
                            'oldPassword',
                            Translate::_('business', 'Incorrect password entered please try again.')
                        );
                    }
                }
            }
        }
        $this->view->title = Translate::_('business', 'Change Password');

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'changepassword',
                        'contentParams' => [
                            'model' => $model
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @return string
     */
    public function actionPasswordchanged()
    {
        $this->view->title = Translate::_('business', 'Password Changed');

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'passwordchanged',
                    ]
                )
            ]
        );
    }

    /**
     * @return string
     */
    public function actionEditBusinessInformation()
    {
        $verificationSession = Yii::$app->session->get('verificationModuleCode', []);
        $url = Url::to(Yii::$app->request->url, true);

        if (
            ArrayHelper::getValue($verificationSession, 'url', '/') === $url
            && ArrayHelper::getValue($verificationSession, 'status', 'failed') === 'success'
        ) {
            $uid = Yii::$app->user->identity->id;
            $businessInformationAttributes = IdbBusinessSignUpForm::businessDetailsAttributes();
            $businessInformationAttributes[] = 'vat';
            IdbArrayHelper::removeElement($businessInformationAttributes, 'VAT');
            $userData = BusinessUserData::getUserDataByKeys(
                $uid,
                $businessInformationAttributes
            );
            $model = new IdbBusinessSignUpForm();
            $model->scenario = IdbBusinessSignUpForm::SCENARIO_BUSINESS_DETAILS;
            foreach ($userData as $userDataItem) {
                if ($userDataItem->key === 'vat') {
                    $userDataItem->key = strtoupper($userDataItem->key);
                }
                $model->{$userDataItem->key} = $userDataItem->value;
            }

            if (!empty(Yii::$app->request->post() ['IdbBusinessSignUpForm'])) {
                $model->load(Yii::$app->request->post());
                if ($model->validate()) {
                    IdbArrayHelper::removeElement($businessInformationAttributes, 'name');
                    $userDataModel = BusinessUserData::instantiate();
                    foreach ($businessInformationAttributes as $attribute) {
                        var_dump($attribute);

                        $userDataModelAttribute = BusinessUserData::find()->where(
                            ['uid' => $uid, 'key_hash' => $userDataModel->getKeyHash($uid, $attribute)]
                        )->one();
                        $attributeModel = $attribute;
                        if ($attributeModel === 'vat') {
                            $attributeModel = strtoupper($attributeModel);
                        }
                        if (is_null($userDataModelAttribute)) {
                            $userDataModelAttribute = BusinessUserData::instantiate(
                                ['uid' => $uid, 'key' => $attribute, 'value' => $model->{$attributeModel}]
                            );
                        } else {
                            $userDataModelAttribute->setAttributes(
                                ['uid' => $uid, 'key' => $attribute, 'value' => $model->{$attributeModel}]
                            );
                        }
                        if (
                        !($userDataModelAttribute->validate()
                            && $userDataModelAttribute->save())
                        ) {
                            $model->addErrors($userDataModelAttribute->getErrors());
                        }
                    }
                }
                if (empty($model->getErrors())) {
                    return $this->redirect(['index']);
                }
            }

            return $this->render(
                '@app/themes/adminlte2/views/site/template',
                [
                    'params' => ArrayHelper::merge
                    (
                        self::$params,
                        [
                            'content' => 'edit-business-information',
                            'contentParams' => [
                                'model' => $model
                            ]
                        ]
                    )
                ]
            );

        } else {
            Yii::$app->session->set('verificationModuleCode', array_merge(Yii::$app->session->get('verificationModuleCode', []),['url' => $url, 'status' => 'failed']));

            return $this->redirect(['/idbverification/code/email']);
        }
    }

    /**
     * @return string
     */
    public function actionEditDpoInformation()
    {
        $verificationSession = Yii::$app->session->get('verificationModuleCode', []);
        $url = Url::to(Yii::$app->request->url, true);

        if (
            ArrayHelper::getValue($verificationSession, 'url', '/') === $url
            && ArrayHelper::getValue($verificationSession, 'status', 'failed') === 'success'
        ) {
            $uid = Yii::$app->user->identity->id;
            $businessDpoAttributes = IdbBusinessSignUpDPOForm::dpoDetailsAttributes();
            $userData = BusinessUserData::getUserDataByKeys(
                $uid,
                $businessDpoAttributes
            );
            $model = new IdbBusinessSignUpDPOForm();
            foreach ($userData as $userDataItem) {
                $model->{$userDataItem->key} = $userDataItem->value;
            }

            if (Yii::$app->request->post('IdbBusinessSignUpDPOForm', false)) {
                $model->load(Yii::$app->request->post());
                foreach (Yii::$app->request->post('IdbBusinessSignUpDPOForm') as $key => $value) {
                    $model->{$key} = $value;
                }
                if ($model->validate()) {
                    $userDataModel = BusinessUserData::instantiate();
                    foreach ($businessDpoAttributes as $attribute) {
                        $userDataModelAttribute = BusinessUserData::find()->where(
                            ['uid' => $uid, 'key_hash' => $userDataModel->getKeyHash($uid, $attribute)]
                        )->one();
                        $attributeModel = $attribute;
                        if (is_null($userDataModelAttribute)) {

                            $userDataModelAttribute = BusinessUserData::instantiate(
                                ['uid' => $uid, 'key' => $attribute, 'value' => $model->{$attributeModel}]
                            );
                        } else {
                            $userDataModelAttribute->setAttributes(
                                ['uid' => $uid, 'key' => $attribute, 'value' => $model->{$attributeModel}]
                            );
                        }
                        if (
                            !($userDataModelAttribute->validate()
                            && $userDataModelAttribute->save())
                        ) {
                            $model->addErrors($userDataModelAttribute->getErrors());
                        }
                    }
                }

                if (empty($model->getErrors())) {
                    return $this->redirect(['index']);
                }
            }

            return $this->render(
                '@app/themes/adminlte2/views/site/template',
                [
                    'params' => ArrayHelper::merge
                    (
                        self::$params,
                        [
                            'content' => 'edit-dpo-information',
                            'contentParams' => [
                                'model' => $model
                            ]
                        ]
                    )
                ]
            );

        } else {
            Yii::$app->session->set('verificationModuleCode', array_merge(Yii::$app->session->get('verificationModuleCode', []),['url' => $url, 'status' => 'failed']));

            return $this->redirect(['/idbverification/code/email']);
        }
    }

    /**
     * @return Response
     */
    public function actionDeleteAccount()
    {
        $verificationSession = Yii::$app->session->get('verificationModuleCode', []);
        $url = Url::to(Yii::$app->request->url, true);


        if (
            ArrayHelper::getValue($verificationSession, 'url', '/') === $url
            && ArrayHelper::getValue($verificationSession, 'status', 'failed') === 'success'
        ) {
            $model = new BusinessDeleteAccount();
            $model->uid = Yii::$app->user->identity->id;
            $model->status = 'DELETE_STARTED';

            $notification = new NotificationsForm();
            $notification->uid = Yii::$app->user->identity->id;
            $notification->type = 'red';
            $notification->status = 1;
            $notification->title = \idbyii2\helpers\Translate::_('business', 'Delete account has been started.');
            $notification->body = Translate::_(
                'business',
                'Delete account has been started. You can restore your account for 30 days from ' . $model->created_at
            );
            $notification->action_name = Translate::_('business', 'Restore Account');
            $notification->url = '/idbuser/profile/restore-account';

            if (!$notification->save() || !$model->save()) {
                Yii::$app->session->setFlash(
                    'error',
                    Translate::_(
                        'business',
                        'We can\'t start delete process. Please contact with administrator.'
                    )
                );
            }

            return $this->redirect(['index']);
        } else {
            Yii::$app->session->set('verificationModuleCode', array_merge(Yii::$app->session->get('verificationModuleCode', []),['url' => $url, 'status' => 'failed']));

            return $this->redirect(['/idbverification/code/email']);
        }
    }

    /**
     * @return Response
     */
    public function actionRestoreAccount()
    {
        $verificationSession = Yii::$app->session->get('verificationModuleCode', []);
        $url = Url::to(Yii::$app->request->url, true);

        if (
            ArrayHelper::getValue($verificationSession, 'url', '/') === $url
            && ArrayHelper::getValue($verificationSession, 'status', 'failed') === 'success'
        ) {
            try {
                $model = BusinessDeleteAccount::findOne([
                    'uid' => Yii::$app->user->identity->id
                ]);


                if ($model instanceof BusinessDeleteAccount && $model->can_restore) {
                    Account::deleteAndCheck(BusinessDeleteAccount::class, ['uid' => Yii::$app->user->identity->id]);
                }

                Account::deleteAndCheck(BusinessNotification::class, ['uid' => Yii::$app->user->identity->id, 'type' => 'red']);
            } catch (Exception $exception) {
                Yii::error($exception->getMessage());
                Yii::$app->session->setFlash(
                    'error',
                    Translate::_(
                        'business',
                        'We can\'t start delete process. Please contact with administrator.'
                    )
                );
            }

            return $this->redirect(['index']);
        } else {
            Yii::$app->session->set('verificationModuleCode', array_merge(Yii::$app->session->get('verificationModuleCode', []),['url' => $url, 'status' => 'failed']));

            return $this->redirect(['/idbverification/code/email']);
        }
    }

    public function actionChangeContact()
    {
        $verificationSession = Yii::$app->session->get('verificationModuleToken', []);
        $url = Url::to(Yii::$app->request->url, true);

        if (
            ArrayHelper::getValue($verificationSession, 'url', '/') === $url
            && ArrayHelper::getValue($verificationSession, 'status', 'failed') === 'success'
        ) {
            $request = Yii::$app->request;

            $model = new ChangeContactForm();

            if (!empty($request->post('ChangeContactForm'))) {
                $model->load($request->post());

                if ($model->validate()) {
                    Yii::$app->session->set('verificationModuleCode', [
                       'mobile' => $model->mobile,
                       'email' => $model->email
                    ]);

                    $this->redirect(['save-contact']);
                }
            } else {
                $model->email = Yii::$app->user->identity->email;
                $model->mobile = Yii::$app->user->identity->mobile;
            }

            return $this->render(
                '@app/themes/adminlte2/views/site/template',
                [
                    'params' => ArrayHelper::merge
                    (
                        self::$params,
                        [
                            'content' => 'contact',
                            'contentParams' => [
                                'model' => $model
                            ]
                        ]
                    )
                ]
            );
        } else {
            Yii::$app->session->set('verificationModuleToken', array_merge(Yii::$app->session->get('verificationModuleToken', []),['url' => $url, 'status' => 'failed']));

            return $this->redirect(['/idbverification/token']);
        }
    }

    public function actionSaveContact()
    {
        $verificationSession = Yii::$app->session->get('verificationModuleCode', []);
        $url = Url::to(Yii::$app->request->url, true);

        if (
            ArrayHelper::getValue($verificationSession, 'url', '/') === $url
            && ArrayHelper::getValue($verificationSession, 'status', 'failed') === 'success'
        ) {
            try {
                $email = BusinessUserData::getUserDataByKeys(Yii::$app->user->identity->id, ['email'])[0];
                $email->value = $verificationSession['email'];

                $mobile = BusinessUserData::getUserDataByKeys(Yii::$app->user->identity->id, ['mobile'])[0];
                $mobile->value = $verificationSession['mobile'];

                $token = BusinessUserData::getUserDataByKeys(Yii::$app->user->identity->id, ['passwordToken'])[0];
                $token->value = Yii::$app->user->identity->generateToken($email->value, $mobile->value);

                if (!$mobile->save() || !$email->save() || !$token->save()) {
                    throw new \Exception("Cant save mobile or email.");
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', Translate::_('business', "We can't change email or mobile. Please contact with administrator."));

                return $this->redirect(['/']);
            }

            return $this->render(
                '@app/themes/adminlte2/views/site/template',
                [
                    'params' => ArrayHelper::merge
                    (
                        self::$params,
                        [
                            'content' => 'save-contact',
                            'contentParams' => []
                        ]
                    )
                ]
            );
        } else {
            Yii::$app->session->set('verificationModuleCode', array_merge(Yii::$app->session->get('verificationModuleCode', []),['url' => $url, 'status' => 'failed']));

            return $this->redirect(['/idbverification/code/email']);
        }
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGetToken()
    {
        $uid = Yii::$app->user->identity->id;

        $userData = [];

        if (!empty(Yii::$app->user->identity->userId)) {
            $userData[IdbBusinessUser::instance()->getAttributeLabel('loginName')] = Yii::$app->user->identity->userId;
        }
        if (!empty(Yii::$app->user->identity->accountNumber)) {
            $userData[IdbBusinessUser::instance()->getAttributeLabel('accountNumber')] = Yii::$app->user->identity->accountNumber;
        }

        $businessData = new BusinessUserData();

        $passwordToken = BusinessUserData::find()->where(
            ['uid' => $uid, 'key_hash' => $businessData->getKeyHash($uid, 'passwordToken')]
        )->one();

        $content = $this->renderPartial(
            '@app/themes/idb/modules/signup/views/pdfs/passwordToken.php',
            compact('passwordToken', 'userData')
        );

        $pdf = new Pdf(
            [
                // set to use core fonts only
                'mode' => Pdf::MODE_UTF8,
                // A4 paper format
                'format' => Pdf::FORMAT_A4,
                // portrait orientation
                'orientation' => Pdf::ORIENT_PORTRAIT,
                // stream to browser inline
                'destination' => Pdf::DEST_BROWSER,
                // your html content input
                'content' => $content,
                // format content from your own css file if needed or use the
                // set mPDF properties on the fly
                'options' => ['title' => 'IDBank recovery token'],
                'defaultFont' => 'DejaVuSans'
            ]
        );

        return $pdf->render();
    }
}

################################################################################
#                                End of file                                   #
################################################################################
