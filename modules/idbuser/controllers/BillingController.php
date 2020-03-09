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
use app\helpers\BusinessConfig;
use app\helpers\Translate;
use DateInterval;
use DateTime;
use Exception;
use idbyii2\enums\DownloadType;
use idbyii2\enums\PaymentResultCode;
use idbyii2\helpers\Credits;
use idbyii2\helpers\Localization;
use idbyii2\models\db\BusinessCreditsLog;
use idbyii2\models\db\BusinessUserData;
use idbyii2\models\db\IdbDownloadLog;
use idbyii2\models\idb\BusinessIdbBillingClient;
use idbyii2\services\Payment;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

################################################################################
# Class(es)                                                                    #
################################################################################

class BillingController extends IdbController
{

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
                    'roles' => ['action_organization_billing_manager'],
                ],
                [
                    'allow' => true,
                    'actions' => ['payments'],
                    'roles' => ['action_organization_billing_manager'],
                ],
                [
                    'allow' => true,
                    'actions' => ['logs'],
                    'roles' => ['action_organization_billing_manager'],
                ],
                [
                    'allow' => true,
                    'actions' => ['invoice', 'check'],
                    'roles' => ['action_organization_billing_manager'],
                ],
                [
                    'allow' => true,
                    'actions' => ['billing-information'],
                    'roles' => ['action_organization_billing_manager'],
                ]
            ];
        }

        return $behaviors;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        $creditsBurned = BusinessCreditsLog::getLastMonthForChart(Yii::$app->user->identity->oid);
        //BusinessPortalHelper::verifyBusinessData();
        $this->view->title = Translate::_('business', 'User billing information');
        $model = BusinessIdbBillingClient::model();
        $businessPackage = $model->getBusinessPackage(Yii::$app->user->identity->oid);
        if (!empty($businessPackage)) {
            for ($i = 0; $i < count($businessPackage[0]); $i++){
                if($businessPackage[0][$i] === null){
                    $businessPackage[0][$i] = Translate::_('business','does not apply');
                }
            }
            $package = $model->getPackage($businessPackage[0][2]);
            $package[0]['isBilling'] = true;
            $package = $package[0];
            $businessPackage = $businessPackage[0];
        } else {
            $package = [];
            $businessPackage = [];
        }

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => [
                    'content' => 'index',
                    'menu_active_section' => '[menu][account_administration]',
                    'menu_active_item' => '[menu][billing][user_billing]',
                    'contentParams' => compact(
                        'package',
                        'businessPackage',
                        'creditsBurned'
                    )
                ]
            ]
        );
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionPayments()
    {
        $this->view->title = Translate::_('business', 'User billing information: Payments');
        $model = BusinessIdbBillingClient::model();

        $payments = $model->getPaymentsForOrganization(Yii::$app->user->identity->oid);
        $paymentsArray = [];

        if (!empty($payments)) {
            foreach ($payments as $key => $payment) {
                $status = $this->timeDiff($payment[1], $payment[4]);
                $paymentsArray[] = [
                    'id' => $payment[0],
                    'timestamp' => substr($payment[1], 2, 17),
                    'payment_method' => $payment[3],
                    'status' => $status,
                    'amount' => number_format($payment[5] / 100, 2),
                    'downloads' => self::getCountInvoiceDownloadForPayment($payment[0])
                ];
            }
        }

        $paymentProvider = new ArrayDataProvider(
            [
                'allModels' => $paymentsArray,
                'sort' => [
                    'attributes' => ['timestamp', 'status'],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => [
                    'content' => 'payments',
                    'menu_active_section' => '[menu][account_administration]',
                    'menu_active_item' => '[menu][billing][user_payments]',
                    'contentParams' => [
                        'payments' => $paymentProvider,
                    ]
                ]
            ]
        );
    }

    /**
     * @return string
     */
    public function actionLogs()
    {
        $this->view->title = Translate::_('business', 'User billing information: Activity logs');
        $model = BusinessIdbBillingClient::model();
        $logs = $model->getCountAllBillingAuditLogsForBusiness(Yii::$app->user->identity->oid);
        $arrayLogs = [];

        if (!empty($logs)) {
            foreach ($logs as $key => $log) {
                $arrayLogs[] = [
                    'id' => $log[0],
                    'action_date' => $log[2],
                    'action_name' => $log[3],
                    'action_type' => $log[4],
                    'cost' => $log[5],
                    'credits_before' => $log[6],
                    'additional_credits_before' => $log[7],
                ];
            }
        }

        $logsProvider = new ArrayDataProvider(
            [
                'allModels' => $arrayLogs,
                'sort' => [
                    'attributes' => ['timestamp', 'status'],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => [
                    'content' => 'logs',
                    'menu_active_section' => '[menu][account_administration]',
                    'menu_active_item' => '[menu][billing][user_logs]',
                    'contentParams' => [
                        'logs' => $logsProvider
                    ]
                ]
            ]
        );
    }

    /**
     * @return string
     * @throws \Adyen\AdyenException
     * @throws \yii\web\NotFoundHttpException
     * @throws \Throwable
     */
    public function actionBillingInformation()
    {
        $verificationSession = Yii::$app->session->get('verificationModuleCode', []);
        $url = Url::to(Yii::$app->request->url, true);

        if (
            ArrayHelper::getValue($verificationSession, 'url', '/') === $url
            && ArrayHelper::getValue($verificationSession, 'status', 'failed') === 'success'
        ) {
            $paymentService = new Payment();
            $request = Yii::$app->request;
            if ($request->isPost) {
                try {
                    $error = !$paymentService->changePaymentMethod($request->post());
                } catch (Exception $e) {
                    $error = true;
                    $message = sprintf(
                        "Payment ERROR - INFO:[%s]",
                        $e->getMessage()
                    );
                    Yii::error($message);
                } finally {
                    if ($error) {
                        Yii::$app->session->setFlash(
                            'error',
                            Translate::_(
                                'business',
                                'There something goes wrong'
                            )
                        );
                    } else {
                        Yii::$app->session->setFlash(
                            'success',
                            Translate::_(
                                'business',
                                'You\'re data was updated successfully'
                            )
                        );
                    }
                }
            }

            $locale = BusinessConfig::get()->getPaymentLocale();
            $loadingContext = BusinessConfig::get()->getPaymentLoadingContext();
            $originKey = $paymentService->getOriginKey();

            return $this->render(
                '@app/themes/adminlte2/views/site/template',
                [
                    'params' => [
                        'content' => 'billingInformation',
                        'menu_active_section' => '[menu][account_administration]',
                        'menu_active_item' => '[menu][billing][user_logs]',
                        'contentParams' => compact('locale', 'loadingContext', 'originKey')
                    ]
                ]
            );
        } else {
            Yii::$app->session->set('verificationModuleCode', array_merge(Yii::$app->session->get('verificationModuleCode', []),['url' => $url, 'status' => 'failed']));

            return $this->redirect(['/idbverification/code/email']);
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionInvoice($id)
    {
        $model = BusinessIdbBillingClient::model();
        $invoice = $model->getInvoiceForPayment($id, Yii::$app->user->identity->oid);
        $invoice = $invoice[0];

        if(
            count(
                IdbDownloadLog::find()->where(
                    [
                        'oid' => Yii::$app->user->identity->oid,
                        'name' => $invoice[3]
                    ]
                )->all()
            ) > 1
        ) {
            Credits::takeCredits(
                Yii::$app->user->identity->oid,
                'invoiceDownload'
            );
        }


        $download = new IdbDownloadLog();
        $download->oid = Yii::$app->user->identity->oid;
        $download->timestamp = Localization::getDatabaseDateTime(new \DateTime());
        $download->name = $invoice[3];
        $download->type = DownloadType::INVOICE;
        $download->save();

        $content = $this->renderPartial(
            '@idbyii2/static/templates/PDFs/invoice',
            compact('invoice')
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
                'options' => ['title' => 'IDBank invoice'],
            ]
        );

        self::countInvoiceDownloadsForPayment($id);

        return $pdf->render();
    }

    /**
     * Private function used for counting invoice downloads
     *
     * @param $id
     */
    private static function countInvoiceDownloadsForPayment($id)
    {
        $userDataModel = BusinessUserData::instantiate();
        $key = 'payment_' . $id;

        $business = BusinessUserData::findOne(
            [
                'uid' => Yii::$app->user->identity->id,
                'key_hash' => $userDataModel->getKeyHash(Yii::$app->user->identity->id, $key)
            ]
        );
        if (empty($business)) {
            $business = BusinessUserData::instantiate(
                ['uid' => Yii::$app->user->identity->id, 'key' => $key, 'value' => '0']
            );
        }
        $business->value = strval(intval($business->value) + 1);
        if (!$business->save()) {
            Yii::error('Cannot save count downloads for invoice!');
            Yii::error(json_encode($business->getErrors()));
        }
    }

    /**
     * @param $id
     *
     * @return string
     */
    private static function getCountInvoiceDownloadForPayment($id)
    {
        $userDataModel = BusinessUserData::instantiate();
        $key = 'payment_' . $id;

        $business = BusinessUserData::findOne(
            [
                'uid' => Yii::$app->user->identity->id,
                'key_hash' => $userDataModel->getKeyHash(Yii::$app->user->identity->id, $key)
            ]
        );
        if (!empty($business)) {
            return $business->value;
        }

        return '0';
    }

    /**
     * @param $date
     * @param $currentStatus
     *
     * @return string
     * @throws \Exception
     */
    private function timeDiff($date, $currentStatus)
    {
        $dateLastPayment = DateTime::createFromFormat('Y-m-d H:i:s.u', $date);
        $dateNextPayment = DateTime::createFromFormat('Y-m-d H:i:s.u', $date)->add(new DateInterval('P1M'));
        $now = new DateTime();
        $interval = $now->diff($dateNextPayment);
        $diff = $interval->format('%R%a');

        if (
            ($currentStatus === PaymentResultCode::CANCELLED || $currentStatus === PaymentResultCode::REFUSED)
            && $diff[0] === '-'
        ) {
            return 'Overdue since: ' . $dateNextPayment->format('Y-m-d');
        } elseif ($currentStatus === PaymentResultCode::RECEIVED || $currentStatus === PaymentResultCode::AUTHORISED) {
            return 'Paid on: ' . $dateLastPayment->format('Y-m-d');
        } elseif ($diff[0] === '+') {
            return 'Due on: ' . $dateNextPayment->format('Y-m-d');
        } else {
            return 'Overdue since: ' . $dateNextPayment->format('Y-m-d');
        }
    }
}

################################################################################
#                                End of file                                   #
################################################################################
