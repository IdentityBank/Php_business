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

namespace app\modules\paymentcheck\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use BusinessConfig;
use idbyii2\services\Payment;
use Yii;
use yii\web\Controller;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Default controller for the `payment-check` module
 */
class CheckController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = array_merge_recursive(
            $behaviors,
            [
                'verbs' => [
                    'actions' => [
                        'delete' => ['post'],
                    ],
                ],
            ]
        );
        if (!empty($behaviors['access']) && is_array($behaviors['access']['rules'])) {
            $behaviors['access']['rules'] = [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['paycheck_index'],
                ],
            ];
        }

        return [];
    }

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex()
    {
        $paymentService = new Payment();

        $request = Yii::$app->request;


        $data = [
            'locale' => BusinessConfig::get()->getPaymentLocale(),
            'loadingContext' => BusinessConfig::get()->getPaymentLoadingContext(),
            'originKey' => $paymentService->getOriginKey(),
            'response' => null
        ];

        if (!empty($request->post('paymentMethod'))) {
            $data['response'] = $paymentService->paymentCheck($request->post());
        }


        return $this->render('billing', $data);
    }
}

################################################################################
#                                End of file                                   #
################################################################################
