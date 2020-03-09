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

namespace app\modules\idbdata\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use DateTime;
use idbyii2\helpers\DataJSON;
use idbyii2\helpers\IdbAccountId;
use idbyii2\helpers\Metadata;
use idbyii2\helpers\Translate;
use idbyii2\models\db\DataSet;
use idbyii2\models\db\DataType;
use idbyii2\models\db\IdbAuditMessage;
use idbyii2\models\form\BusinessRetentionPeriodForm;
use idbyii2\models\idb\IdbBankClientBusiness;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;

################################################################################
# Class(es)                                                                    #
################################################################################

class DataClientSetsController extends IdbController
{

    private static $params = [
        'menu_active_section' => '[menu][tools]',
        'menu_active_item' => '[menu][tools][idbdata]',
    ];

    private $modelClient;

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
        if (is_array($behaviors['access']['rules'])) {
            $behaviors['access']['rules'] = [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['action_idbdata'],
                ],
                [
                    'allow' => true,
                    'actions' => ['create', 'get-model'],
                    'roles' => ['action_idbdata'],
                ]
            ];
        }

        return $behaviors;
    }

    /**
     * @param \yii\base\Action $action
     *
     * @return bool|void
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        try {
            $user = Yii::$app->user->identity;

            $businessId = IdbAccountId::generateBusinessDbId($user->oid, $user->aid, $user->dbid);

            $this->modelClient = IdbBankClientBusiness::model($businessId);
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            Yii::$app->user->logout();
        } catch (\Error $e) {
            Yii::error($e->getMessage());
            Yii::$app->user->logout();
        }

        return parent::beforeAction($action);
    }

    /**
     * Create or update data set for current database.
     *
     * @param null $id
     *
     * @return bool
     * @throws \Exception
     */
    public function actionCreate($id = null)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            $metadata = $this->modelClient->getAccountMetadata();
            if ($metadata == null) {
                $this->modelClient->createAccountMetadata();
                $metadata = [];
            } else {
                $metadata = json_decode($metadata['Metadata'], true);
            }

            if (empty($metadata['data'])) {
                $metadata['data'] = [];
            }

            $metadata['database'] = $request->post('database');
            $metadata['columns'] = $request->post('columns');
            $metadata['display_name'] = $request->post('display_name') ?? '';
            $metadata['used_for'] = $request->post('used_for') ?? '';
            $metadata['uuid'] = $request->post('uuid') ??
                DataJSON::generateUuid((new DateTime())->format('Y-m-d H:s:m'));

            if (!empty($request->post('diff'))) {
                Metadata::updateDataByDiff($request->post('diff'), $metadata['data']);
            }

            if (!empty($request->post('edit'))) {
                if (empty($this->modelClient->updateDataTypes(['database' => $request->post('edit')]))) {
                    return false;
                }

                if (!empty($metadata['settings'])) {
                    Metadata::updateSettingsByEdit($request->post('edit'), $metadata['settings']);
                }
            }

            if (
                empty($metadata['options'])
                || empty($metadata['options']['send_mail'])
                || empty($metadata['options']['send_sms'])
            ) {
                $metadata['options'] = [
                    'send_mail' => 'off',
                    'send_sms' => 'off'
                ];
            }

            if (empty($this->modelClient->setAccountMetadata(json_encode($metadata)))) {
                return false;
            }

            if (empty($request->post('database'))) {
                Yii::$app->session->setFlash(
                    'error',
                    Translate::_('business', 'At least one datatype has to exist in your dataset.')
                );

                return $this->redirect(['/idbdata/data-client-sets/create']);
            }

            Yii::$app->session->setFlash(
                'success',
                Translate::_(
                    'business',
                    'Your dataset has been successfully saved'
                )
            );

            return true;
        }

        $sets = DataJSON::setsToArray(DataSet::find()->all());
        $types = DataJSON::typesToArray(DataType::find()->all());

        $this->view->title = Translate::_('business', 'Create set objects');

        $preventReady = true;
        $model = new BusinessRetentionPeriodForm();
        $model2 = new DynamicModel(['messages', 'legal', 'message', 'purposeLimitation']);
        $model2->addRule(['message', 'purposeLimitation', 'legal'], 'required');
        $model2->addRule(['message', 'purposeLimitation'], 'string');

        $legals = [];
        /** @var IdbAuditMessage $legal */
        foreach (IdbAuditMessage::find()->where(['portal_uuid' => 'default'])->all() as $legal) {
            $legals[$legal->message] = $legal->message;
        }

        $messages = [];
        $messagesObjects = array_merge(
            IdbAuditMessage::find()->where(['portal_uuid' => Yii::$app->user->identity->id])->orderBy(
                'order'
            )->all(),
            IdbAuditMessage::find()->where(['portal_uuid' => 'default_reason'])->all()
        );
        /** @var IdbAuditMessage $message */
        foreach ($messagesObjects as $message) {
            $messages [$message->message] = $message->message;
        }

        if (empty($model['message'])) {
            $model2['message'] = $messagesObjects[0]->message;
        }
        $legal = $legals;
        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'create',
                        'contentParams' => compact('sets', 'types', 'id', 'preventReady', 'model', 'model2', 'legal', 'messages')
                    ]
                )
            ]
        );
    }

    /**
     * @param null|int $id
     *
     * @return false|string
     * @throws \Exception
     */
    public function actionGetModel($id = null)
    {
        $model = DataSet::findOne($id);
        if (empty($model)) {
            $model = $this->modelClient->getAccountMetadata()['Metadata'];
        } else {
            $model = DataJSON::setToArray($model);
        }

        if (empty($model) || $model === '{}') {
            $model = DataJSON::setToArray(new DataSet());
        } else {
            $model = json_decode($model, true);
        }

        if (empty($model['database'])) {
            $model['database'] = [];
        }

        $data = [
            'uuid' => $model['uuid'] ?? DataJSON::generateUuid((new DateTime())->format('Y-m-d H:s:m')),
            'display_name' => $model['display_name'] ?? '',
            'database' => $model['database'] ?? '',
            'used_for' => $model['used_for'] ?? '',
            'hasPeopleAccessMap' => Metadata::hasPeopleAccessMap($model),
            'PeopleAccessMap' => $model['PeopleAccessMap'] ?? ''
        ];

        if (!empty($model['data'])) {
            $data['data'] = $model['data'];
        }

        return json_encode($data);
    }
}

################################################################################
#                                End of file                                   #
################################################################################
