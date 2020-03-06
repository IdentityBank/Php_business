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

namespace app\modules\applications\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use app\helpers\Translate;
use idbyii2\helpers\FileHelper;
use idbyii2\helpers\IdbAccountId;
use idbyii2\models\data\IdbDataProvider;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\models\idb\IdbBankClientBusiness;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

################################################################################
# Class(es)                                                                    #
################################################################################

class ChangeRequestController extends IdbController
{

    private static $params =
        [
            'menu_active_section' => '[menu][applications]',
            'menu_active_item' => '[menu][applications][change-request]',
        ];
    private $businessId;
    private $dbName;
    private $clientModel;
    private $accountName;
    private $metadata;
    private $changeRequests;
    /** @var IdbDataProvider */
    private $dataProvider;

    /**
     * @return array
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
        if (is_array($behaviors['access']['rules'])) {
            $behaviors['access']['rules'] = [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['cr_index'],
                ],
                [
                    'allow' => true,
                    'actions' => ['reverse'],
                    'roles' => ['cr_reverse'],
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['cr_delete'],
                ],
                [
                    'allow' => true,
                    'actions' => ['verify'],
                    'roles' => ['cr_verify'],
                ]
            ];
        }

        return $behaviors;
    }

    /**
     * @param $action
     *
     * @return bool|\yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $return = parent::beforeAction($action);
        if (!$return) {
            return $return;
        }

        $user = Yii::$app->user->identity;
        $this->businessId = IdbAccountId::generateBusinessDbId($user->oid, $user->aid, $user->dbid);
        $this->dbName = BusinessDatabase::findOne($user->dbid);
        if ($this->dbName) {
            $this->dbName = $this->dbName->name;
        }
        $this->clientModel = IdbBankClientBusiness::model($this->businessId);
        $this->accountName = ((empty(Yii::$app->user->identity->accountName)) ? ''
            : Yii::$app->user->identity->accountName);
        $this->metadata = json_decode($this->clientModel->getAccountMetadata()['Metadata'], true);
        $this->dataProvider = new IdbDataProvider($this->businessId);
        $this->dataProvider->init(['isPeopleAccessMap' => true]);
        $this->changeRequests = $this->clientModel->getAllAccountCRs();

        return $return;
    }

    public function actionIndex()
    {
        $this->view->title = Translate::_('business', 'Details');
        $data = [];

        if (!is_null($this->changeRequests) && array_key_exists('QueryData', $this->changeRequests)) {
            foreach ($this->changeRequests['QueryData'] as $key => $changeRequest) {
                $data[$key]['id'] = $changeRequest[0];
                $data[$key]['people_id'] = $changeRequest[1];
                $data[$key]['new_data'] = json_encode(json_decode($changeRequest[2], true)['new']);
                $data[$key]['old_data'] = json_encode(json_decode($changeRequest[2], true)['old']);
                $data[$key]['created_at'] = $changeRequest[3];
                $data[$key]['status'] = $changeRequest[4];
            }
        }

        $provider = new ArrayDataProvider(
            [
                'allModels' => $data,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => ['task', 'access'],
                ],
            ]
        );

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'index',
                        'contentParams' => [
                            'provider' => $provider
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @return \yii\web\Response
     * @throws \Exception`
     */
    public function actionReverse()
    {
        $request = Yii::$app->request;

        if ($request->isPost && array_key_exists('peopleId', $request->post())) {
            $cr = $this->clientModel->getAccountCRbyUserId(intval($request->post('peopleId')));

            if (!empty($cr['QueryData'][0])) {
                $userId = intval($cr['QueryData'][0][1]);
                $cr = json_decode($cr['QueryData'][0][2], 1);
                $data = $cr['old'];
                $businessId = IdbAccountId::generateBusinessDbId(
                    Yii::$app->user->identity->oid,
                    Yii::$app->user->identity->aid,
                    Yii::$app->user->identity->dbid
                );
                $clientModel = IdbBankClientBusiness::model($businessId);
                $metadata = $clientModel->getAccountMetadata();
                $metadata = json_decode($metadata['Metadata'], true);

                $result = [];
                if (array_key_exists('data', $metadata)) {
                    foreach ($metadata['data'] as $key => $value) {
                        if (!array_key_exists('object_type', $value)) {
                            continue;
                        }

                        if ($value['object_type'] === 'type') {
                            if (array_key_exists($value['display_name'], $data)) {
                                $result[$value['uuid']] = $data[$value['display_name']];
                            }
                        }

                        if ($value['object_type'] === 'set') {
                            foreach ($value['data'] as $nr => $type) {
                                if (array_key_exists($value['display_name'] . '-' . $type['display_name'], $data)) {
                                    $result[$type['uuid']] = $data[$value['display_name'] . '-'
                                    . $type['display_name']];
                                }
                            }
                        }
                    }
                }

                $businessId = IdbAccountId::generateBusinessDbId(
                    Yii::$app->user->identity->oid,
                    Yii::$app->user->identity->aid,
                    Yii::$app->user->identity->dbid
                );
                $clientModel = IdbBankClientBusiness::model($businessId);
                $response = $clientModel->update($userId, $result);
                $response = $this->clientModel->updateAccountCRbyUserId(
                    $userId,
                    json_encode($cr),
                    FileHelper::STATUS_REVERSED
                );
            }
        }

        return $this->redirect(['change-request/index']);
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;

        if (!is_null($id)) {
            $userId = intval($id);
            $this->clientModel->deleteAccountCRbyUserId($userId);
        }

        return $this->redirect(['change-request/index']);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionVerify()
    {
        $request = Yii::$app->request;

        if ($request->isPost && array_key_exists('peopleId', $request->post())) {
            $userId = intval($request->post('peopleId'));

            $cr = $this->clientModel->getAccountCRbyUserId($userId);

            $response = $this->clientModel->updateAccountCRbyUserId(
                $userId,
                $cr['QueryData'][0][2],
                FileHelper::STATUS_VERIFIED
            );
        }

        return $this->redirect(['change-request/index']);
    }
}

################################################################################
#                                End of file                                   #
################################################################################
