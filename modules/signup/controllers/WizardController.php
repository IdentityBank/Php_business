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

namespace app\modules\signup\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################


use Adyen\ConnectionException;
use app\helpers\BusinessConfig;
use app\helpers\Translate;
use app\models\LogPaymentNotification;
use app\models\Test;
use Exception;
use idbyii2\enums\EmailActionType;
use idbyii2\enums\PaymentEventCode;
use idbyii2\enums\PaymentResultCode;
use idbyii2\helpers\EmailTemplate;
use idbyii2\helpers\FileHelper;
use idbyii2\helpers\IdbAccountId;
use idbyii2\helpers\Localization;
use idbyii2\helpers\PasswordToken;
use idbyii2\helpers\Sms;
use idbyii2\helpers\VerificationCode;
use idbyii2\models\db\AuthAssignment;
use idbyii2\models\db\BusinessAccount;
use idbyii2\models\db\BusinessAccountUser;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\models\db\BusinessDatabaseUser;
use idbyii2\models\db\BusinessInvoice;
use idbyii2\models\db\BusinessOrganization;
use idbyii2\models\db\BusinessSignup;
use idbyii2\models\db\BusinessUserData;
use idbyii2\models\db\IdbPaymentLog;
use idbyii2\models\form\EmailVerificationForm;
use idbyii2\models\form\IdbBusinessSignUpDPOForm;
use idbyii2\models\form\SmsVerificationForm;
use idbyii2\models\form\IdbBusinessSignUpForm;
use idbyii2\models\idb\BusinessIdbBillingClient;
use idbyii2\models\identity\IdbBusinessUser;
use idbyii2\services\Payment;
use kartik\mpdf\Pdf;
use Yii;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

################################################################################
# Class(es)                                                                    #
################################################################################

class WizardController extends Controller
{

    const STATUS_FAILED = 'failed';
    const STATUS_CANCEL = 'cancel';

    public $defaultAction = 'welcome';
    /** @var BusinessSignup */
    protected $signUpModel = null;

    /**
     * @param $action action name to verify
     *
     * @return bool status of validation before we execute any action for that controller
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $requestId = Yii::$app->request->get('id');
        switch ($action->id) {
            case 'check':
                $this->enableCsrfValidation = false;
                break;
            case 'tac':
            case 'package':
            case 'billing':
            case 'billingcomplete':
            case 'success':
            case 'email-verification':
            case 'sms-verification':
            case 'payment-request':
            case 'other-payment':
            case 'get-token':
                if (
                    $this->updateSignupModel($action, $requestId)
                    && $action->id !== 'payment-request'
                    && $action->id !== 'other-payment'
                    && $action->id !== 'get-token'
                ) {
                    if ($action->id !== $this->signUpModel->getDataChunk('currentState')) {
                        $this->redirect(
                            [$this->signUpModel->getDataChunk('currentState'), 'id' => $requestId]
                        );

                        return false;
                    }
                }

                break;
        }

        return parent::beforeAction($action);
    }

    /**
     * @param $action
     * @param $requestId
     *
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    protected function updateSignupModel($action, $requestId)
    {
        $this->signUpModel = BusinessSignup::findByAuthKey($requestId);

        if ($this->signUpModel === null) {
            throw new BadRequestHttpException(Translate::_('business', 'Your request cannot be delivered.'));

            return false;
        }

        $this->signUpModel->setDataChunk(
            'action_log_' . $action->id . '_' . Localization::getDateTimeFileString(),
            $_SERVER,
            false,
            false
        );

        return true;
    }

    /**
     * @param int $value
     *
     * @return string
     * @throws \Adyen\AdyenException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCheckPayment($value = 1)
    {
        $paymentService = new Payment();
        $response = null;
        $request = Yii::$app->request;
        if (
            !empty($request->post('paymentMethod'))
            && !empty($request->post('value'))
        ) {
            try {
                $response = $paymentService->paymentCheck($request->post());
            } catch (Exception $e) {
                $response = $e;
                Yii::$app->session->setFlash(
                    'error',
                    Translate::_('business', 'An error has occurred, please try again.')
                );
            }
        }

        $currency = '€';
        $currencyDisplayPosition = 0;
        $data = [
            'locale' => BusinessConfig::get()->getPaymentLocale(),
            'loadingContext' => BusinessConfig::get()->getPaymentLoadingContext(),
            'originKey' => $paymentService->getOriginKey(),
            'response' => $response,
            'paymentAttributes' => [
                'value' => $value,
                'currency' => $currency,
                'currencyDisplayPosition' => $currencyDisplayPosition,
                'recurringPeriod' => Translate::_('business', 'per month')
            ],
        ];

        return $this->render('billingCheck', $data);
    }

    /**
     * @return mixed
     */
    public function actionWelcome()
    {
        return $this->render('welcome');
    }

    /**
     * @return mixed
     */
    public function actionUnsupportedBrowser()
    {
        return $this->render('unsupportedBrowser');
    }

    /**
     * @return string
     */
    public function actionBeforeWeStart()
    {
        return $this->render('beforeWeStart');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function actionBusinessDetails()
    {
        $model = new IdbBusinessSignUpForm();
        $model->scenario = IdbBusinessSignUpForm::SCENARIO_BUSINESS_DETAILS;
        $model->authenticatorEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormAuthenticatorEnabled();
        $model->captchaEnabled =
            BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaEnabled();
        if (!empty(Yii::$app->request->post() ['SignUpForm'])) {
            $model->load(Yii::$app->request->post());
            $model->authenticatorEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormAuthenticatorEnabled();
            $model->captchaEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaEnabled();
            if ($model->validate()) {
                $bd = [];
                $attributes = IdbBusinessSignUpForm::businessDetailsAttributes();
                foreach ($attributes as $attribute) {
                    $bd[$attribute] = $model->{$attribute};
                }

                return $this->redirect(['dpo-details', 'id' => base64_encode(json_encode($bd))]);
            }
        }

        return $this->render('businessDetails', ['model' => $model]);
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function actionDpoDetails($id = null)
    {
        if(empty($id)) {
            return $this->redirect(['business-details']);
        }
        $model = new IdbBusinessSignUpDPOForm();
        $model->captchaEnabled =
            BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaEnabled();

        if (!empty(Yii::$app->request->post())) {
            $model->load(Yii::$app->request->post());

            if ($model->validate()) {
                $bd = json_decode(base64_decode($id), true);
                $attributes = IdbBusinessSignUpDPOForm::dpoDetailsAttributes();
                foreach ($attributes as $attribute) {
                    $bd[$attribute] = $model->{$attribute};
                }

                return $this->redirect(['primary-contact', 'id' => base64_encode(json_encode($bd))]);
            }
        }

        return $this->render('dpoDetails', ['model' => $model]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function actionPrimaryContact($id = null)
    {
        $model = new IdbBusinessSignUpForm();
        if (!empty($id)) {
            $id = base64_decode($id);
            $id = json_decode($id, true);

            $attributes = IdbBusinessSignUpForm::businessDetailsAttributes();
            if (!empty($id)) {
                foreach ($attributes as $attribute) {
                    if (!empty($id[$attribute]) && property_exists(IdbBusinessSignUpForm::class, $attribute)) {
                        $model->{$attribute} = $id[$attribute];
                    }
                }
                foreach (IdbBusinessSignUpDPOForm::dpoDetailsAttributes() as $attribute) {
                    $model->dpo[$attribute] = $id[$attribute];
                }
            }

        } elseif (!empty(Yii::$app->request->post() ['SignUpForm'])) {
            $post = Yii::$app->request->post();

            $model->load($post);
            $model->authenticatorEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormAuthenticatorEnabled();
            $model->captchaEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaEnabled();
            $model->dpo = json_decode($post['IdbBusinessSignUpForm']['dpo'], true);

            if ($model->validate()) {
                $signUpModel = new BusinessSignup();
                $signUpModel->setDataFromForm($model);
                $createIdbUserStatus = IdbBusinessUser::createFromSignUpModel($signUpModel);
                $signUpModel->setDataChunk('uid', $createIdbUserStatus['uid']);
                $signUpModel->generateAuthKey();
                $auth_key = $signUpModel->auth_key;

                $signUpModel->save();

                $this->appendSignUpTables($signUpModel);

                return $this->redirect(['email-verification', 'id' => $auth_key]);
            }
        }

        $model->authenticatorEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormAuthenticatorEnabled();
        $model->captchaEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaEnabled();

        return $this->render('primaryContact', ['model' => $model]);
    }

    /**
     * @param $uid
     *
     * @return void
     * @throws \Exception
     */
    private function appendSignUpTables(BusinessSignup $signUpModel)
    {
        $uid = $signUpModel->getDataChunk('uid');
        $authAssignment = new AuthAssignment();
        $authAssignment->item_name = "organization_admin";
        $authAssignment->user_id = $uid;
        $authAssignment->save();
        $authAssignment = new AuthAssignment();
        $authAssignment->item_name = "organization_billing";
        $authAssignment->user_id = $uid;
        $authAssignment->save();
        $authAssignment = new AuthAssignment();
        $authAssignment->item_name = "organization_user";
        $authAssignment->user_id = $uid;
        $authAssignment->save();

        $organization = BusinessOrganization::createOrganizationByName(
            $signUpModel->getDataChunk('name')
        );
        $signUpModel->setDataChunk('oid', $organization->oid);
        $signUpModel->save();
        $account = BusinessAccount::createAccountByOrganization($organization);
        $database = BusinessDatabase::createDatabaseByAccount($account);

        BusinessDatabaseUser::create(
            [
                'uid' => $uid,
                'dbid' => $database->dbid
            ]
        );
        BusinessAccountUser::create(
            [
                'uid' => $uid,
                'aid' => $account->aid
            ]
        );

        $user = IdbBusinessUser::findIdentity($uid);
        $businessId = IdbAccountId::generateBusinessDbId($user->oid, $user->aid, $user->dbid);

        try {
            FileHelper::createTables($businessId);
        } catch (Exception $e) {
            $logger = new Logger();
            $message = $e->getFile() . ': ' . $e->getLine() . ": " . $e->getMessage() . "\n";
            $message .= "Stacktrace: \n" . $e->getTraceAsString();
            $logger->log($message, Logger::LEVEL_ERROR);
            var_dump($message);
            die();
        }

        $business = BusinessUserData::instantiate(['uid' => $uid, 'key' => 'aid', 'value' => $account->aid]);
        $business->save();
        $business = BusinessUserData::instantiate(['uid' => $uid, 'key' => 'oid', 'value' => $organization->oid]);
        $business->save();
        $business = BusinessUserData::instantiate(['uid' => $uid, 'key' => 'dbid', 'value' => $database->dbid]);
        $business->save();
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionInvoice($id)
    {
        $invoice = BusinessInvoice::findOne($id);
        if (empty($invoice)) {
            throw new NotFoundHttpException();
        }
        $content = $this->renderPartial('@app/themes/idb/modules/signup/views/pdfs/invoice.php', compact('invoice'));


        $pdf = new Pdf(
            [
                // set to use core fonts only
                'mode' => Pdf::MODE_CORE,
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
                'options' => ['title' => 'IDBank invoice'],
            ]
        );

        return $pdf->render();
    }

    /**
     * @param null $id
     *
     * @return string|Response
     * @throws \Exception
     */
    public function actionEmailVerification($id = null)
    {
        $request = Yii::$app->request;
        $model = new EmailVerificationForm();
        $model->captchaEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaEnabled();

        if (!empty($request->post('code')) && count($request->post('code')) > 11) {
            $model->load(Yii::$app->request->post());

            if (
                $this->signUpModel->getDataChunk('emailCode') == VerificationCode::parseFromArray(
                    $request->post('code')
                )
            ) {
                $this->signUpModel->setDataChunk('currentState', 'sms-verification');
                $this->signUpModel->save();

                return $this->redirect(['sms-verification', 'id' => $id]);
            } else {
                Yii::$app->session->setFlash(
                    'info',
                    Translate::_('business', 'Email code was incorrect please try again.')
                );
            }
        }

        $this->signUpModel->generateVeryficationCode('emailCode');
        EmailTemplate::sendEmailByAction(
            EmailActionType::BUSINESS_EMAIL_VERIFICATION,
            [
                'code' => $this->signUpModel->getDataChunk('emailCode'),
                'businessName' => $this->signUpModel->getDataChunk('name'),
                'firstName' => $this->signUpModel->getDataChunk('firstname'),
                'lastName' => $this->signUpModel->getDataChunk('lastname'),
            ],
            Translate::_('business', 'Confirm code'),
            $this->signUpModel->getDataChunk('email'),
            Yii::$app->language
        );

        $code = explode('.', $this->signUpModel->getDataChunk('emailCode'));
        $codeFirst = $code[0];
        $codeThird = $code[2];

        return $this->render(
            (($this->signUpModel->getDataChunk('signup-register'))
                ? '/wizard/emailVerification'
                : 'emailVerification'),
            compact('id', 'model', 'codeFirst', 'codeThird')
        );
    }

    /**
     * @param null $id
     *
     * @return string|Response
     * @throws \Exception
     */
    public function actionSmsVerification($id = null)
    {
        $request = Yii::$app->request;
        $model = new SmsVerificationForm();
        $model->captchaEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaEnabled();
        $tryCount = $this->signUpModel->getDataChunk('tryCount');

        if (!empty($request->post('code')) && count($request->post('code')) > 11) {
            $model->load(Yii::$app->request->post());

            if (
                $this->signUpModel->getDataChunk('smsCode') == VerificationCode::parseFromArray(
                    $request->post('code')
                )
            ) {
                $this->signUpModel->setDataChunk('currentState', 'tac');
                $this->signUpModel->save();

                return $this->redirect(['tac', 'id' => $id]);
            } else {
                $this->signUpModel->setDataChunk(
                    'tryCount',
                    $this->signUpModel->getDataChunk('tryCount') - 1
                );
                $tryCount = $this->signUpModel->getDataChunk('tryCount');
                $this->signUpModel->save();
                Yii::$app->session->setFlash(
                    'info',
                    Translate::_('business', 'Incorrect email or SMS code')
                );
            }
        }

        $this->signUpModel->generateVeryficationCode('smsCode');
        Sms::sendVerificationCode(
            $this->signUpModel->getDataChunk('mobile'),
            $this->signUpModel->getDataChunk('smsCode')
        );

        $code = explode('.', $this->signUpModel->getDataChunk('smsCode'));
        $codeFirst = $code[0];
        $codeThird = $code[2];

        return $this->render(
            (($this->signUpModel->getDataChunk('signup-register'))
                ? '/wizard/smsVerification'
                : 'smsVerification'),
            compact('id', 'model', 'revision', 'tryCount', 'codeFirst', 'codeThird')
        );
    }

    /**
     * @param $id signup id number which is used to proceed with the request
     *
     * @return string|Response
     */
    public function actionTac($id)
    {
        $request = Yii::$app->request;
        if (
            !empty($request->getBodyParam('TermsAndConditionsAgreement'))
            && ($request->getBodyParam('TermsAndConditionsAgreement') === 'on')
        ) {
            $action = 'package';
            $this->signUpModel->setDataChunk('tac', 'agreed');
            $this->signUpModel->save();

            if ($this->signUpModel->getDataChunk('signup-register')) {
                $action = 'success';
                $this->assignPasswordToken($this->signUpModel->getDataChunk('uid'));
                $this->sendLoginData();
            }

            $this->signUpModel->setDataChunk(
                'currentState',
                $action
            );
            $this->signUpModel->save();

            return $this->redirect(
                [
                    $action,
                    'id' => $id
                ]
            );
        }

        return $this->render(
            (($this->signUpModel->getDataChunk('signup-register'))
                ? '/wizard/termsAndConditions'
                : 'termsAndConditions'),
            ['id' => $id]
        );
    }

    /**
     * Assign password token to created user
     *
     * @param $uid
     */
    private function assignPasswordToken($uid)
    {
        $businessData = new BusinessUserData();

        $passwordToken = BusinessUserData::find()->where(
            ['uid' => $uid, 'key_hash' => $businessData->getKeyHash($uid, 'passwordToken')]
        )->one();
        if (empty($passwordToken)) {
            $passwordTokenHelper = new PasswordToken();
            $passwordToken = BusinessUserData::instantiate(
                [
                    'uid' => $uid,
                    'key' => 'passwordToken',
                    'value' => $passwordTokenHelper->encodeToken($this->signUpModel->getDataJSONByNamespace())
                ]
            );

            $passwordToken->save();
        }
    }

    /**
     * Send Email with login data to client.
     *
     * @param null $url
     */
    private function sendLoginData($url = null)
    {
        $user = IdbBusinessUser::findIdentity($this->signUpModel->getDataChunk('uid'));

        EmailTemplate::sendEmailByAction(
            EmailActionType::BUSINESS_LOGIN_DATA,
            [
                'loginLink' => $url ?? Url::toRoute(['/login'], true),
                'loginName' => $user->userId,
                'accountNumber' => $user->accountNumber,
                'businessName' => $this->signUpModel->getDataChunk('name'),
                'firstName' => $this->signUpModel->getDataChunk('firstname'),
                'lastName' => $this->signUpModel->getDataChunk('lastname')
            ],
            Translate::_('business', 'Login Details'),
            $this->signUpModel->getDataChunk('email'),
            Yii::$app->language
        );
    }

    /**
     * @param null $id
     *
     * @return mixed
     */
    public function actionPackage($id = null)
    {
        $billingModel = BusinessIdbBillingClient::model();
        $packages = $billingModel->getCountAllActivePackages();

        $request = Yii::$app->request;
        if (!empty($request->post('idb_package'))) {
            $idbPackage = $request->post('idb_package');
            $this->signUpModel->setDataChunk(
                'package',
                $idbPackage
            );

            foreach ($packages as $package) {
                if (($package[0] ?? null) == $idbPackage) {
                    $this->signUpModel->setDataChunk(
                        'packageDetails',
                        json_encode($package)
                    );
                    break;
                }
            }

            $this->signUpModel->setDataChunk('currentState', 'billing');
            $this->signUpModel->save();

            return $this->redirect(['billing', 'id' => $id]);
        }

        return $this->render('choosePackage', compact('id', 'packages'));
    }

    /**
     * @param null $id
     * @param bool $paymentType
     * @param null $attributes
     *
     * @return mixed|string|\yii\web\Response
     * @throws \Adyen\AdyenException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionBilling($id = null, $paymentType = false, $attributes = null)
    {
        if (BusinessConfig::get()->skipPaymentEnabled()) {
            return $this->createPortalUser($id);
        }

        if (!empty($paymentType)) {
            return $this->actionBillingPayment($id, $paymentType, $attributes);
        }

        return $this->render('paymentAuthorization', compact('id', 'packages'));
    }

    /**
     * @param null $id
     *
     * @return \yii\web\Response
     */
    private function createPortalUser($id = null)
    {
        $uid = $this->signUpModel->getDataChunk('uid');

        $this->assignPasswordToken($uid);

        $billingModel = BusinessIdbBillingClient::model();
        $package = $billingModel->getPackage($this->signUpModel->getDataChunk('package'));
        $billingModel->assignPackageToBusiness(
            [
                'business_id' => $this->signUpModel->getDataChunk('oid'),
                'package_id' => intval($this->signUpModel->getDataChunk('package')),
                'payment_log_id' => 0,
                'credits' => $package[0][2],
                'base_credits' => $package[0][2],
                'additional_credits' => 0,
                'duration' => $package[0][3],
                'start_date' => null,
                'end_date' => null,
                'last_payment' => null,
                'next_payment' => null,
                'billing_type' => 'amateur'
            ]
        );

        $this->sendLoginData();
        $this->signUpModel->setDataChunk('currentState', 'success');
        $this->signUpModel->save();

        return $this->redirect(['success', 'id' => $id]);
    }

    /**
     * @param null $id
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Adyen\AdyenException
     */
    public function actionBillingPayment($id, $paymentType, $attributes)
    {
        $paymentService = new Payment();
        if (!empty($attributes)) {
            $attributes = json_decode(base64_decode($attributes), true);
        }

        $request = Yii::$app->request;
        if (
            !empty($request->post('paymentMethod'))
            && !empty($request->post('value'))
        ) {
            try {
                $error = !$paymentService->makeFirstPayment($this->signUpModel, $request->post());
                if (!$error) {
                    return $this->createPortalUser($id);
                }
            } catch (Exception $e) {
                $error = true;
                $message = sprintf(
                    "Payment ERROR - ID:[%s], INFO:[%s]",
                    strval($id),
                    $e->getMessage()
                );
                Yii::error($message);
            } catch(ConnectionException $e) {
                $error = true;
                $message = sprintf(
                    "Payment ERROR - ID:[%s], INFO:[%s]",
                    strval($id),
                    $e->getMessage()
                );
                Yii::error($message);
            } catch(\Error $e) {
                $error = true;
                $message = sprintf(
                    "Payment ERROR - ID:[%s], INFO:[%s]",
                    strval($id),
                    $e->getMessage()
                );
                Yii::error($message);
            } finally {
                if (!empty($error)) {
                    $this->signUpModel->setDataChunk('currentState', 'billingcomplete');
                    $this->signUpModel->save();

                    return $this->redirect(['billingcomplete', 'id' => $id, 'status' => self::STATUS_FAILED]);
                }
            }
        }

        $package = json_decode($this->signUpModel->getDataChunk('packageDetails'));
        $value = $package[5] ?? 1;
        $currency = $package[6] ?? '€';
        $currencyDisplayPosition = 0;
        $data = [
            'id' => $id,
            'paymentType' => $paymentType,
            'attributes' => $attributes,
            'locale' => BusinessConfig::get()->getPaymentLocale(),
            'loadingContext' => BusinessConfig::get()->getPaymentLoadingContext(),
            'originKey' => $paymentService->getOriginKey(),
            'paymentAttributes' => [
                'value' => $value,
                'currency' => $currency,
                'currencyDisplayPosition' => $currencyDisplayPosition,
                'recurringPeriod' => Translate::_('business', 'per month')
            ],
        ];

        return $this->render('billing', $data);
    }

    /**
     * @param null $id
     *
     * @return \yii\web\Response
     */
    public function actionPaymentRequest($id = null)
    {
        $request = Yii::$app->request;

        if (!empty($request->post('email'))) {
            $link = Url::toRoute(['/signup/other-payment', 'id' => $this->signUpModel->auth_key], true);
            EmailTemplate::sendEmailByAction(
                EmailActionType::BUSINESS_PAYMENT_REQUEST,
                [
                    'paymentLink' => $link,
                    'primaryAccountContact' => sprintf(
                        "%s %s",
                        $this->signUpModel->getDataChunk('firstname'),
                        $this->signUpModel->getDataChunk('lastname')
                    ),
                    'businessName' => $this->signUpModel->getDataChunk('name'),
                    'firstName' => $this->signUpModel->getDataChunk('firstname'),
                    'lastName' => $this->signUpModel->getDataChunk('lastname')
                ],
                Translate::_('business', 'Payment Request Identity Bank'),
                $request->post('email'),
                Yii::$app->language
            );

            Yii::$app->session->setFlash(
                'success',
                Translate::_('business', 'The payment request was sent successfully.')
                . '<BR>' . PHP_EOL .
                Translate::_('business', 'You will receive an email when the payment has been processed.')
                . '<BR>' . PHP_EOL .
                Translate::_('business', 'You can now close this window.')
            );
        } else {
            Yii::$app->session->setFlash(
                'error',
                Translate::_('business', 'An error has occurred, please try again.')
            );
        }

        return $this->redirect(
            [
                'billing',
                'id' => $id,
                'paymentType' => 'other',
                'attributes' => base64_encode(json_encode(['paid' => true]))
            ]
        );
    }

    /**
     * @param null $id
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Adyen\AdyenException
     */
    public function actionOtherPayment($id = null)
    {
        $paymentService = new Payment();

        $request = Yii::$app->request;
        if (
            !empty($request->post('paymentMethod'))
            && !empty($request->post('value'))
        ) {
            try {
                $error = !$paymentService->makeFirstPayment($this->signUpModel, $request->post());
                if (!$error) {
                    $uid = $this->signUpModel->getDataChunk('uid');
                    $businessData = new BusinessUserData();

                    $this->assignPasswordToken($uid);
                    $this->sendLoginData(Url::toRoute(['success', 'id' => $id], true));
                    $this->signUpModel->setDataChunk('currentState', 'success');
                    $this->signUpModel->setDataChunk('paymentComplete', 'true');
                    $this->signUpModel->save();
                } else {
                    Yii::$app->session->setFlash(
                        'error',
                        Translate::_('business', 'An error has occurred, please try again.')
                    );
                }
            } catch (Exception $e) {
                $error = true;
                $message = sprintf(
                    "Payment ERROR - ID:[%s], INFO:[%s]",
                    strval($id),
                    $e->getMessage()
                );
                Yii::error($message);
                Yii::$app->session->setFlash(
                    'error',
                    Translate::_('business', 'An error has occurred, please try again.')
                );
            }
        }

        if ($this->signUpModel->getDataChunk('paymentComplete') === 'true') {
            return $this->render('otherComplete');
        } else {
            $package = json_decode($this->signUpModel->getDataChunk('packageDetails'));
            $value = $package[5] ?? 1;
            $currency = $package[6] ?? '€';
            $currencyDisplayPosition = 0;
            $data = [
                'id' => $id,
                'locale' => BusinessConfig::get()->getPaymentLocale(),
                'loadingContext' => BusinessConfig::get()->getPaymentLoadingContext(),
                'originKey' => $paymentService->getOriginKey(),
                'paymentAttributes' => [
                    'value' => $value,
                    'currency' => $currency,
                    'currencyDisplayPosition' => $currencyDisplayPosition,
                    'recurringPeriod' => Translate::_('business', 'per month')
                ],
            ];

            return $this->render('otherPayment', $data);

        }
    }

    /**
     * @param null $id
     * @param null $status
     *
     * @return mixed
     */
    public function actionBillingcomplete($id = null, $status = null)
    {
        if ($status == null) {
            $status = self::STATUS_CANCEL;
        }
        if (
            (strtolower($status) === self::STATUS_FAILED)
            || (strtolower($status) === self::STATUS_CANCEL)
        ) {
            $message = sprintf(
                "Payment ERROR/CANCEL - ID:[%s], STATUS:[%s]",
                strval($id),
                $status
            );
            Yii::error($message);

            $this->signUpModel->setDataChunk('currentState', 'billing');
            $this->signUpModel->save();
        }

        return $this->render('billingComplete' . ucfirst($status), compact('id', 'passwordToken'));
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function actionSuccess($id)
    {
        $login = IdbBusinessUser::findIdentity($this->signUpModel->getDataChunk('uid'));

        return $this->render('success', compact('login', 'id'));
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGetToken($id)
    {
        $uid = $this->signUpModel->getDataChunk('uid');
        $login = IdbBusinessUser::findIdentity($this->signUpModel->getDataChunk('uid'));
        $userData = [];
        if (!empty($login)) {
            if (!empty($login->userId)) {
                $userData[IdbBusinessUser::instance()->getAttributeLabel('loginName')] = $login->userId;
            }
            if (!empty($login->accountNumber)) {
                $userData[IdbBusinessUser::instance()->getAttributeLabel('accountNumber')] = $login->accountNumber;
            }
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
