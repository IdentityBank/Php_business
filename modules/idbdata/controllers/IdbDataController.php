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
use idb\idbank\BusinessIdBankClient;
use idbyii2\helpers\AccessManager;
use idbyii2\helpers\DataHTML;
use idbyii2\helpers\Filters;
use idbyii2\helpers\IdbAccountId;
use idbyii2\helpers\Metadata;
use idbyii2\helpers\PeopleAccessHelper;
use idbyii2\helpers\SendUsedForHelper;
use idbyii2\helpers\Translate;
use idbyii2\models\data\IdbDataProvider;
use idbyii2\models\db\BusinessDataMap;
use idbyii2\models\db\IdbAuditLog;
use idbyii2\models\db\IdbAuditMessage;
use idbyii2\models\idb\IdbBankClientBusiness;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Default controller for the `IdbData` module
 */
class IdbDataController extends IdbController
{

    const DATA_FORCE_IMPORT = true;
    private static $params = [
        'menu_active_section' => '[menu][tools]',
        'menu_active_item' => '[menu][tools][idbdata]',
    ];
    /** @var BusinessIdBankClient */
    private $clientModel;
    /** @var IdbDataProvider */
    private $dataProvider;

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
                        'delete' => ['get'],
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
                    'actions' => ['index'],
                    'roles' => ['action_idbdata'],
                ],
                [
                    'allow' => true,
                    'actions' => ['show-all'],
                    'roles' => ['action_idbdata'],
                ],
                [
                    'allow' => true,
                    'actions' => ['reset-search'],
                    'roles' => ['action_idbdata'],
                ],
                [
                    'allow' => true,
                    'actions' => ['save-used-for'],
                    'roles' => ['action_idbdata'],
                ],
                [
                    'allow' => true,
                    'actions' => ['delete-multiple'],
                    'roles' => ['action_idbdata'],
                ],
                [
                    'allow' => true,
                    'actions' => ['set-display'],
                    'roles' => ['action_idbdata'],
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => ['action_idbdata'],
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => ['action_idbdata'],
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['action_idbdata'],
                ],
            ];
        }

        return $behaviors;
    }


    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            $request = Yii::$app->request;
            $dbid = null;

            if ($request->isPost && !empty($request->post('dbid'))) {
                $dbid = $request->post('dbid');

                AccessManager::changeDatabase(
                    Yii::$app->user->identity->id,
                    Yii::$app->user->identity->oid,
                    Yii::$app->user->identity->aid,
                    $dbid
                );
            }

            $user = Yii::$app->user->identity;
            $businessId = IdbAccountId::generateBusinessDbId($user->oid, $user->aid, $dbid ?? $user->dbid);
            $this->clientModel = IdbBankClientBusiness::model($businessId);

            if ($action->id === 'show-all') {
                $this->dataProvider = new IdbDataProvider($businessId);
                $this->dataProvider->init();

                if (
                    empty($this->dataProvider->metadata)
                    || empty($this->dataProvider->metadata['database'])
                    || empty($this->dataProvider->metadata['data'])
                ) {
                    if (IdbDataController::DATA_FORCE_IMPORT) {
                        Yii::$app->session->setFlash(
                            'info',
                            Translate::_('business', 'You have to import your data before you will have access vault.')
                        );

                        $this->redirect(['/tools/wizard/index']);

                        return false;
                    } else {
                        Yii::$app->session->setFlash(
                            'error',
                            Translate::_('business', 'At least one datatype has to exist in your dataset.')
                        );

                        $this->redirect(['/idbdata/data-client-sets/create']);

                        return false;

                    }
                }
            }
        }

        return parent::beforeAction($action);
    }

    /**
     * Show data grid for current database.
     *
     * @return string
     */
    public function actionShowAll()
    {
        $request = Yii::$app->request;
        if (
            !empty($request->get('idbAttr'))
            && $request->get('idbAttr') === 'AuditLog'
        ) {
            Yii::$app->user->identity->setIdbAttribute('UserDatabaseUsedForApproved', 'IDB_AUDIT_LOG');
            $this->redirect(['show-all']);
        }

        self::$params['menu_active_section'] = '[menu][applications]';
        self::$params['menu_active_item'] = '[menu][applications][idbdata]';

        $this->view->title = Translate::_('business', 'IDB Data');

        if (Yii::$app->session->has('pageSize')) {
            $perPage = Yii::$app->session->get('pageSize');
        } else {
            $perPage = 25;
        }
        if (!empty($request->get('per-page'))) {
            $perPage = $request->get('per-page');
        }

        if (!empty($request->get('sort-by')) && !empty($request->get('sort-dir'))) {
            Yii::$app->session->set('sort-by', $request->get('sort-by'));
            Yii::$app->session->set('sort-dir', $request->get('sort-dir'));
            $this->dataProvider->sort = [
                DataHTML::getUuid(
                    explode('-', $request->get('sort-by')),
                    $this->dataProvider->metadata
                ) => $request->get('sort-dir')
            ];
        }

        $this->dataProvider->setPagination(
            [
                'pageSize' => $perPage,
                'page' => $request->get('page') - 1
            ]
        );

        foreach ($this->dataProvider->metadata['columns'] as $columns) {
            foreach ($columns as $key => $value) {
                if($key === 'title') {
                    $value = strip_tags($value);
                }
            }
        }

        $data = [
            'metadata' => $this->dataProvider->metadata,
            'dataProvider' => $this->dataProvider,
            'messages' => array_merge(
                IdbAuditMessage::find()->where(['portal_uuid' => Yii::$app->user->identity->id])->orderBy(
                    'order'
                )->all(),
                IdbAuditMessage::find()->where(['portal_uuid' => 'default_reason'])->all()
            ),
            'legal' => IdbAuditMessage::find()->where(['portal_uuid' => 'default'])->all(),
            'search' => null
        ];


        if (!empty($request->post('search'))) {
            Yii::$app->session->set('search', $request->post('search'));
            $this->dataProvider->prepareSearch(json_decode($request->post('search'), true));
            $data['search'] = json_encode($request->post('search'));
        } elseif (Yii::$app->session->get('search')) {
            $this->dataProvider->prepareSearch(json_decode(Yii::$app->session->get('search'), true));
            $data['search'] = json_encode(Yii::$app->session->get('search'));
        }

        $data['otherOptions']['caseSensitive'] = true;

        if (
            isset($data['metadata']['options']['case_sensitive'])
            && $data['metadata']['options']['case_sensitive'] === 'off'
        ) {
            $data['otherOptions']['caseSensitive'] = false;
        }

        try {
            if (
                !isset($data['metadata']['options']['send_sms']) && !isset($data['metadata']['options']['send_mail'])
            ) {
                if (empty($data['metadata'])) {
                    $data['metadata'] = [];
                }
                if (empty($data['metadata']['options'])) {
                    $data['metadata']['options'] = [];
                }
                $data['metadata']['options']['send_sms'] = false;
                $data['metadata']['options']['send_mail'] = false;
            }

        } catch (Exception $e) {
            Yii::$app->session->setFlash(
                'error',
                Translate::_('business', 'An error occurred when accessing vault options.')
            );

            return $this->goHome();
        }

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'show-all-data',
                        'contentParams' => $data
                    ]
                )
            ]
        );

    }

    /**
     * Reset search filters.
     *
     * @return Response
     */
    public function actionResetSearch()
    {
        if (Yii::$app->session->get('search')) {
            Yii::$app->session->remove('search');
        }

        if (Yii::$app->session->get('sort-by')) {
            Yii::$app->session->remove('sort-by');
        }

        if (Yii::$app->session->get('sort-dir')) {
            Yii::$app->session->remove('sort-dir');
        }

        return $this->redirect(['/idbdata/idb-data/show-all']);
    }

    /**
     * Deletes multiple line from datagrid.
     *
     * @throws \Exception
     */
    public function actionDeleteMultiple()
    {
        $request = Yii::$app->request;

        if ($request->isAjax && !empty($request->post('ids'))) {
            foreach ($request->post('ids') as $id) {
                $this->clientModel->delete((int)$id);
            }

            Yii::$app->session->setFlash(
                'success',
                Translate::_('business', 'Your data was deleted successfully.')
            );

            return true;
        }

        Yii::$app->session->setFlash(
            'success',
            Translate::_('business', 'An error has occurred, please try again later.')
        );

        return false;
    }

    /**
     * Save data from Used for modal to audit_logs.
     *
     * @return Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionSaveUsedFor()
    {
        $request = Yii::$app->request;
        $mailOrSms = null;

        if ($request->isAjax) {
            $ids = $request->post('ids');
            $message = strip_tags($request->post('message'));
            $legal = $request->post('legal');
            $sendSms = $request->post('send_sms');
            $sendMail = $request->post('send_mail');
            $idColumnSms = $request->post('mobile');
            $idColumnMail = $request->post('mail');

            if (!isset($idColumnSms) or !isset($idColumnMail)) {
                Yii::$app->session->setFlash(
                    'info',
                    Translate::_('business', 'Please do the safe mapping first.')
                );

                return $this->redirect(['/applications/contacts/check-mapping']);
            }

            if (($sendSms === 'on') or ($sendMail === 'on')) {
                $metadata = json_decode($this->clientModel->getAccountMetadata()['Metadata'], true);

                $mailUuid = $metadata["PeopleAccessMap"]["email_no"];
                $mobileUuid = $metadata["PeopleAccessMap"]["mobile_no"];
                $nameUuid = $metadata["PeopleAccessMap"]["name_no"];
                $surnameUuid = $metadata["PeopleAccessMap"]["surname_no"];

                $result = Filters::filterByIds($ids);
                $idsForSend = $this->clientModel->find(
                    $result['filterExpr'],
                    $result['exprAttrNames'],
                    $result['exprAttrVal'],
                    ["database" => [$mailUuid, $mobileUuid, $nameUuid, $surnameUuid]]
                );

                $user = Yii::$app->user->identity;
                $businessId = IdbAccountId::generateBusinessDbId(
                    $user->oid,
                    $user->aid,
                    $user->dbid
                );

                if ($sendSms === 'on') {
                    $mailOrSms = 'sms';
                    SendUsedForHelper::executeSendUsedFor(
                        [
                            'businessId' => $businessId,
                            'ids' => $idsForSend,
                            'mailOrSms' => $mailOrSms,
                            'legal' => $legal,
                            'message' => $message,
                            'oid' => $user->oid,
                            'iso' => Yii::$app->language
                        ]
                    );
                }

                if ($sendMail === 'on') {
                    $mailOrSms = 'mail';
                    SendUsedForHelper::executeSendUsedFor(
                        [
                            'businessId' => $businessId,
                            'ids' => $idsForSend,
                            'mailOrSms' => $mailOrSms,
                            'legal' => $legal,
                            'message' => $message,
                            'oid' => $user->oid,
                            'iso' => Yii::$app->language
                        ]
                    );
                }
            }

            IdbAuditLog::saveByArray($request->post());
            IdbAuditMessage::saveMessage($request->post('message'));

            if (
                is_null($sendSms) && is_null($sendMail)
                || $sendSms === 'off' && $sendMail === 'off'
            ) {
                Yii::$app->session->setFlash(
                    'info',
                    Translate::_('business', 'Sending a message has been disabled')
                );

                return $this->redirect(['/idbdata/idb-data/show-all']);
            }

            Yii::$app->session->setFlash(
                'success',
                Translate::_('business', 'Messages were correctly sent.')
            );

            return $this->redirect(['/idbdata/idb-data/show-all']);
        }
    }

    /**
     * Set columns to display in data grid.
     *
     * @return Response
     * @throws \Exception
     */
    public function actionSetDisplay()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $metadata = json_decode($this->clientModel->getAccountMetadata()['Metadata'], true);
            if (empty($metadata['settings'])) {
                $metadata['settings'] = [];
            }

            $metadata['settings'][Yii::$app->user->identity->id] = [];
            if (!empty($request->post('display'))) {
                foreach ($request->post('display') as $key => $value) {
                    $metadata['settings'][Yii::$app->user->identity->id][$key] = $value;
                }
            }

            if (!empty($request->post('case_sensitive'))) {
                $metadata['options']['case_sensitive'] = 'on';
            } else {
                $metadata['options']['case_sensitive'] = 'off';
            }

            $this->clientModel->setAccountMetadata(json_encode($metadata));

            return $this->redirect(['/idbdata/idb-data/show-all']);
        }
    }

    /**
     * Append row of data for current database.
     *
     * @return string|Response
     * @throws \Exception
     */
    public function actionCreate()
    {
        //TODO: merge create and update actions
        $request = Yii::$app->request;

        if ($request->isAjax) {
            if (
                !empty($request->post('data'))
                && !empty($request->post('invitation'))
            ) {
                $response = $this->clientModel->put($request->post('data'));
                if (
                    !empty($response)
                    && !empty($response['QueryData'])
                    && !empty($response['QueryData'][0])
                    && !empty($response['QueryData'][0][0])
                ) {
                    $order = [];
                    foreach ($request->post('data') as $key => $data) {
                        $order['database'] [] = $key;
                    }
                    $currentData = $this->clientModel->get((int)$response['QueryData'][0][0], $order);

                    //TODO: move this logic to method to check if data was saved successfully.
                    $currentData = $currentData['QueryData'][0];

                    $counter = 0;
                    $saveSuccess = true;

                    foreach ($request->post('data') as $change) {
                        if ($change !== $currentData[$counter]) {
                            $saveSuccess = false;
                            break;
                        }
                        $counter++;
                    }

                    if ($saveSuccess) {
                        $user = Yii::$app->user->identity;
                        $businessId = IdbAccountId::generateBusinessDbId(
                            $user->oid,
                            $user->aid,
                            $user->dbid
                        );

                        $metadata = json_decode($this->clientModel->getAccountMetadata()['Metadata'], true);
                        $tmpMetadata = [];
                        $post = $request->post('data');

                        if (Metadata::hasPeopleAccessMap($metadata)) {
                            foreach ($post as $key => $value) {
                                if ($key == $metadata['PeopleAccessMap']['email_no']) {
                                    $tmpMetadata['email'] = $post[$key];
                                } elseif ($key == $metadata['PeopleAccessMap']['mobile_no']) {
                                    $tmpMetadata['mobile'] = $post[$key];
                                } elseif ($key == $metadata['PeopleAccessMap']['name_no']) {
                                    $tmpMetadata['name'] = $post[$key];
                                } elseif ($key == $metadata['PeopleAccessMap']['surname_no']) {
                                    $tmpMetadata['surname'] = $post[$key];
                                }
                            }
                        }

                        $tmpMetadata['dbId'] = (int)$response['QueryData'][0][0];
                        $result = [];
                        array_push($result, $tmpMetadata);
                        $data = [
                            'businessId' => $businessId,
                            'data' => json_encode($result),
                            'uid' => Yii::$app->user->identity->id
                        ];


                        if (filter_var($request->post('invitation'), FILTER_VALIDATE_BOOLEAN)) {
                            PeopleAccessHelper::executeSendInvitations($data);
                        }

                        Yii::$app->session->setFlash(
                            'success',
                            Translate::_(
                                'business',
                                'Your data has been successfully updated'
                            )
                        );

                        return json_encode(['success' => true]);
                    }
                }
            }

            $errorMessage = Translate::_(
                'business',
                'An error has occured. Please contact your system administrator.'
            );

            return json_encode(['success' => false, 'message' => $errorMessage]);
        } else {
            $this->view->title = Translate::_('business', 'IDB Data');

            $model = $this->clientModel->getAccountMetadata();

            return $this->render(
                '@app/themes/adminlte2/views/site/template',
                [
                    'params' => ArrayHelper::merge
                    (
                        self::$params,
                        [
                            'content' => 'create',
                            'contentParams' => [
                                'model' => json_decode($model['Metadata'], true),
                                'preventReady' => true
                            ]
                        ]
                    )
                ]
            );
        }
    }

    /**
     * Delete data with specific $uuid for current database.
     *
     * @param $uuid
     *
     * @return Response
     * @throws \Exception
     */
    public function actionDelete($uuid)
    {
        $this->clientModel->delete((int)$uuid);

        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    /**
     * Update data with specific $uuid for current database.
     *
     * @param $uuid
     *
     * @return string|Response
     * @throws \Exception
     */
    public function actionUpdate($uuid)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            if (!empty($request->post())) {
                if (!empty($this->clientModel->update((int)$uuid, $request->post()))) {
                    $order = [];
                    foreach ($request->post() as $key => $data) {
                        $order['database'] [] = $key;
                    }
                    $currentData = $this->clientModel->get((int)$uuid, $order);

                    $currentData = $currentData['QueryData'][0];

                    $counter = 0;
                    $saveSuccess = true;
                    foreach ($request->post() as $change) {
                        if ($change !== $currentData[$counter]) {
                            $saveSuccess = false;
                            break;
                        }
                        $counter++;
                    }

                    if ($saveSuccess) {
                        Yii::$app->session->setFlash(
                            'success',
                            Translate::_(
                                'business',
                                'Your data has been successfully updated'
                            )
                        );

                        return json_encode(['success' => true]);
                    }
                }
            }

            $errorMessage = Translate::_(
                'business',
                'An error has occured. Please contact your system administrator.'
            );

            return json_encode(['success' => false, 'message' => $errorMessage]);
        } else {
            $this->view->title = Translate::_('business', 'IDB Data');

            $metadata = json_decode($this->clientModel->getAccountMetadata()['Metadata'], true);
            $order = [];
            $order['database'] [] = 'id';
            foreach ($metadata['database'] as $data) {
                $order['database'] [] = $data['uuid'];
            }
            $model = $this->clientModel->get((int)$uuid, $order);

            return $this->render(
                '@app/themes/adminlte2/views/site/template',
                [
                    'params' => ArrayHelper::merge
                    (
                        self::$params,
                        [
                            'content' => 'update',
                            'contentParams' => [
                                'preventReady' => true,
                                'metadata' => $metadata,
                                'model' => $model['QueryData'][0]
                            ]
                        ]
                    )
                ]
            );
        }
    }
}

################################################################################
#                                End of file                                   #
################################################################################
