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

namespace app\modules\logs\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use idbyii2\components\PortalApi;
use idbyii2\helpers\IdbAccountId;
use idbyii2\helpers\Translate;
use idbyii2\models\data\IdbCRDataProvider;
use idbyii2\models\idb\IdbBankClientBusiness;
use Yii;
use yii\helpers\ArrayHelper;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Default controller for the `logs` module
 */
class ChangedDataController extends IdbController
{

    private static $params = [
        'menu_active_section' => '[menu][tools]',
        'menu_active_item' => '[menu][tools][logs_change_requests]',
    ];

    /** @var \idb\idbank\BusinessIdBankClient */
    private $clientModel;
    /** @var IdbCRDataProvider */
    private $dataProvider;

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
                    'actions' => ['index', 'revert', 'reset-search'],
                    'roles' => ['action_logs_change_request'],
                ],
            ];
        }

        return $behaviors;
    }

    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $businessId = IdbAccountId::generateBusinessDbId($user->oid, $user->aid, $user->dbid);

            $this->clientModel = IdbBankClientBusiness::model($businessId);

            if ($action->id === 'index') {
                $this->dataProvider = new IdbCRDataProvider($businessId);
                $this->dataProvider->init();
            }
        }

        return parent::beforeAction($action);
    }

    /**
     * Renders the index view for the module, with log grid.
     *
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;

        $this->view->title = Translate::_('business', 'Used data logs');
        $this->view->params['breadcrumbs'][] = '<i class="fa fa-sitemap"></i>&ensp;' . $this->view->title;

        $perPage = 25;
        if (!empty($request->get('per-page'))) {
            $perPage = $request->get('per-page');
        }

        $this->dataProvider->setPagination(
            [
                'pageSize' => $perPage,
                'page' => $request->get('page') - 1
            ]
        );

        $search = null;
        if (!empty($request->post('search'))) {
            Yii::$app->session->set('search', $request->post('search'));
            $this->dataProvider->prepareSearch(json_decode($request->post('search'), true));
            $search = json_encode($request->post('search'));
        }

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'index',
                        'contentParams' => [
                            'dataProvider' => $this->dataProvider,
                            'search' => $search
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * Revert change and add new change log.
     *
     * @return mixed
     * @throws \Exception
     */
    public function actionRevert()
    {
        $success = false;
        $request = Yii::$app->request;
        if ($request->isPost) {
            if (
                !empty($request->post('data'))
                && !empty($request->post('name'))
                && !empty($request->post('businessId'))
            ) {
                $parsedIds = IdbAccountId::parse($request->post('businessId'));
                $businessId = IdbAccountId::generateBusinessDbId(
                    $parsedIds['oid'],
                    $parsedIds['aid'],
                    $parsedIds['dbid']
                );
                $businessClient = IdbBankClientBusiness::model($businessId);

                $order = [];
                foreach ($request->post('data') as $key => $data) {
                    $order['database'] [] = $key;
                }

                $oldData = $businessClient->findById((int)$parsedIds['uid'], $order);
                if (!empty($oldData) && !empty($oldData['QueryData']) && !empty($oldData['QueryData'][0])) {
                    $oldData = $oldData['QueryData'][0];
                    if ($businessClient->update((int)$parsedIds['uid'], $request->post('data')) !== null) {
                        $currentData = $businessClient->findById((int)$parsedIds['uid'], $order);
                        if (
                            !empty($currentData) && !empty($currentData['QueryData'])
                            && !empty($currentData['QueryData'][0])
                        ) {
                            $currentData = $currentData['QueryData'][0];
                            $counter = 0;
                            $saveSuccess = true;
                            $changes['businessId'] = $request->post('businessId');
                            $changes['userId'] = Yii::$app->user->identity->userId;
                            foreach ($request->post('data') as $key => $change) {
                                if ($change !== $currentData[$counter]) {
                                    $saveSuccess = false;
                                    break;
                                }
                                $changes['data'][$key] = [
                                    'value' => $change,
                                    'old_value' => $oldData[$counter],
                                    'display_name' => $request->post('name')[$key]
                                ];
                                $counter++;
                            }

                            if ($saveSuccess) {
                                $portalApi = PortalApi::getPeopleApi();

                                if (!empty($request->post('peopleId')) && $request->post('peopleId') !== '') {
                                    $portalApi->requestBusiness2PeopleMessageInfo(
                                        [
                                            'people_users' => [$request->post('peopleId')],
                                            'Business2PeopleFormModel' => [
                                                'subject' => Translate::_('business', 'Data reverse.'),
                                                'message' => $request->post('message'),
                                                'expires_at' => null,
                                                'business_user' => Yii::$app->user->identity->accountName,
                                            ]
                                        ]
                                    );
                                }

                                $businessClient->addAccountCRbyUserId(
                                    (int)$parsedIds['uid'],
                                    json_encode($changes),
                                    'toRevert',
                                    '###BUSINESS+++###REVERTED+++'
                                );

                                Yii::$app->session->setFlash(
                                    'success',
                                    Translate::_(
                                        'business',
                                        'Your dataset has been successfully reverted'
                                    )
                                );

                                $success = true;
                            }
                        }
                    }
                }
            }
        }

        if (!$success) {
            Yii::$app->session->setFlash(
                'error',
                Translate::_(
                    'business',
                    'An error has occurred, please try again later.'
                )
            );
        }

        return $this->redirect(['index']);
    }
}

################################################################################
#                                End of file                                   #
################################################################################
