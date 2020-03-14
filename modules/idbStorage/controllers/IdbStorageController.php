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

namespace app\modules\idbStorage\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use app\helpers\Translate;
use DateTime;
use Exception;
use idbyii2\components\PortalApi;
use idbyii2\helpers\IdbAccountId;
use idbyii2\helpers\IdbStorageExpressions;
use idbyii2\helpers\Localization;
use idbyii2\models\data\IdbStorageItemDataProvider;
use idbyii2\models\form\BusinessShareFileForm;
use idbyii2\models\form\RequestUploadFormModel;
use idbyii2\models\form\RequestUploadUpdateFormModel;
use idbyii2\models\idb\IdbBankClientBusiness;
use idbyii2\models\idb\IdbStorageClient;
use idbyii2\models\idb\IdbStorageItem;
use idbyii2\models\idb\IdbStorageObject;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Default controller for the `IdbStorage` module
 */
class IdbStorageController extends IdbController
{
    private static $params = [
        'menu_active_section' => '[menu][tools]',
        'menu_active_item' => '[menu][tools][idbstorage]',
    ];
    private $businessId = '';
    private $storageClient;

    /**
     * @param $action
     * @return bool|void
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->view->title = Translate::_('business', 'The vault files');
            $this->storageClient = IdbStorageClient::model();

            $this->businessId = IdbAccountId::generateBusinessDbId(
                Yii::$app->user->identity->oid,
                Yii::$app->user->identity->aid,
                Yii::$app->user->identity->dbid
            );

            return true;
        }

        return false;
    }

    /**
     * @param null $uuid
     * @return string
     * @throws Exception
     */
    public function actionIndex($uuid = null)
    {
        Url::remember(['index', 'uuid' => $uuid], 'idbstorage');
        $user = [];
        if ($uuid !== null) {
            $clientModel = IdbBankClientBusiness::model($this->businessId);
            $metadata = json_decode($clientModel->getAccountMetadata()['Metadata'], true);
            $tmp = $clientModel->findById($uuid);
            if(ArrayHelper::getValue($tmp, 'QueryData.0', false)) {
                $tmp = $tmp['QueryData'][0];
            } else {
                throw new NotFoundHttpException();
            }

            for ($i = 0; $i < count($metadata['columns']); $i++) {
                $user[$metadata['columns'][$i]['title']] = $tmp[$i + 1];
            }
        }
        $showAllUserFiles = filter_var(Yii::$app->session->get('showAllUserFiles', false), FILTER_VALIDATE_BOOLEAN);
        $dataProvider = new IdbStorageItemDataProvider();
        $IdBankClient = IdbBankClientBusiness::model($this->businessId);
        $metadata = json_decode($IdBankClient->getAccountMetadata()['Metadata'], true);
        $uploadLimit = Yii::$app->controller->module->configIdbStorage['uploadLimit'];
        $options['peopleUpload'] = false;
        if (ArrayHelper::getValue(ArrayHelper::getValue($metadata, 'options', []), 'peopleUpload', 'off') === 'on') {
            $options['peopleUpload'] = true;
        }

        $modelRequest = new RequestUploadFormModel();

        $dataProvider->init();
        $request = Yii::$app->request;
        $perPage = 20;
        if (!empty($request->get('per-page'))) {
            $perPage = $request->get('per-page');
        }

        $search = $request->post('search', Yii::$app->session->get('search', []));
        $searchArray = [];
        if (!empty($search) && is_array($search)) {
            foreach ($search as $key => $value) {
                if ($value === '') {
                    continue;
                }
                $searchArray [] = ['column' => $key, 'value' => $value, 'operator' => 'ILIKE'];
            }
        }

        if (empty($uuid)) {
            $searchArray [] = ['column' => 'uid', 'value' => $this->businessId];
            $searchArray [] = ['column' => 'owner', 'value' => "true"];
        } else {
            $searchArray [] = ['column' => 'uid', 'value' => $this->businessId . '.uid.' . $uuid];
            $searchArray [] = ['column' => 'owner', 'value' => "false"];
        }


        $dataProvider->setPagination(
            [
                'pageSize' => $perPage,
                'page' => $request->get('page') - 1
            ]
        );

        if (empty($uuid)) {
            $dataProvider->prepareSearch($searchArray);
        } else {
            if ($showAllUserFiles) {
                if (!empty(Yii::$app->session->get('search', [])['name'])) {
                    $dataProvider->setSearch(
                        IdbStorageExpressions::getPeopleItemExpression(true),
                        [
                            '#col1' => 'uid',
                            '#col2' => 'owner',
                            '#col3' => 'uid',
                            '#col4' => 'name'
                        ],
                        [
                            ':val1' => $this->businessId,
                            ':val2' => 'false',
                            ':val3' => $this->businessId . '.uid.' . $uuid,
                            ':val4' => str_replace(
                                '*',
                                '%',
                                Yii::$app->session->get('search', [])['name']
                            )
                        ]
                    );
                } else {
                    $dataProvider->setSearch(
                        IdbStorageExpressions::getPeopleItemExpression(),
                        [
                            '#col1' => 'uid',
                            '#col2' => 'owner',
                            '#col3' => 'uid'
                        ],
                        [
                            ':val1' => $this->businessId,
                            ':val2' => 'false',
                            ':val3' => $this->businessId . '.uid.' . $uuid
                        ]
                    );
                }
            } else {
                $dataProvider->prepareSearch($searchArray);
            }
        }

        $model = new BusinessShareFileForm();

        $redirectUrl = Url::toRoute('upload', true);
        $files = IdbStorageItem::initMultiple($this->storageClient->findItemByVault($this->businessId)['QueryData']);

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'index',
                        'contentParams' => compact(
                            'files',
                            'redirectUrl',
                            'dataProvider',
                            'uploadLimit',
                            'showAllUserFiles',
                            'user',
                            'model',
                            'search',
                            'modelRequest',
                            'uuid',
                            'options'
                        )
                    ]
                )
            ]
        );
    }

    /**
     * @return mixed
     */
    public function actionInitObject()
    {
        return json_encode($this->storageClient->initObject($this->businessId));
    }

    /**
     * @return Response
     */
    public function actionSettings()
    {
        try {
            $request = Yii::$app->request;
            $IdBankClient = IdbBankClientBusiness::model($this->businessId);
            $metadata = json_decode($IdBankClient->getAccountMetadata()['Metadata'], true);
            $metadata['options']['peopleUpload'] = ArrayHelper::getValue(ArrayHelper::getValue($request->post(), 'options', []), 'people_upload', 'off');
            $IdBankClient->setAccountMetadata(json_encode($metadata));
            Yii::$app->session->setFlash('success', Translate::_('business', 'Change was successfully'));
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            Yii::$app->session->setFlash('error', Translate::_('business', 'There was a problem please try again'));
        }

        return $this->redirect(Yii::$app->request->referrer ? Yii::$app->request->referrer : ['index']);
    }

    /**
     * @return Response
     */
    public function actionShare()
    {
        try {
            $request = Yii::$app->request;
            $info = $this->storageClient->infoStorageObject($request->post('shareOid'))['info']['HTTPHeaders']['etag'];
            $info = str_replace('"', '', $info);

            if ($info === $request->post('shareChecksum')) {
                $this->storageClient->addStorageItem([
                    'owner' => "true",
                    'oid' => $request->post('shareOid'),
                    'uid' => $this->businessId,
                    'type' => 'FILE',
                    'name' => $request->post('shareName')
                ]);


                $this->storageClient->editStorageObject(
                    $request->post('shareOid'),
                    [
                        'metadata' => json_encode([
                            'checkSum' => $request->post('shareChecksum'),
                            'origName' => $request->post('shareName'),
                            'size' => $request->post('shareSize')
                        ]),
                        'attributes' => json_encode(['downloads' => 0])
                    ]
                );

                if (!empty($request->post('people_users')) && $request->post('wholeVault', 'off') !== 'on') {
                    foreach ($request->post('people_users') as $user) {
                        $this->storageClient->addStorageItem([
                            'owner' => "false",
                            'oid' => $request->post('shareOid'),
                            'uid' => $user,
                            'type' => 'FILE',
                            'name' => $request->post('shareName')
                        ]);
                    }
                    $this->notifyAboutShare($request->post('people_users'));
                } elseif (!empty($request->post('shareUuid'))) {
                    $this->storageClient->addStorageItem([
                        'owner' => "false",
                        'oid' => $request->post('shareOid'),
                        'uid' => $this->businessId . '.uid.' . $request->post('shareUuid'),
                        'type' => 'FILE',
                        'name' => $request->post('shareName')
                    ]);
                    $this->notifyAboutShare([$this->businessId . '.uid.' . $request->post('shareUuid')]);
                } elseif ($request->post('wholeVault', 'off') === 'on') {
                    $this->storageClient->addStorageItem([
                        'owner' => "false",
                        'oid' => $request->post('shareOid'),
                        'uid' => $this->businessId,
                        'type' => 'FILE',
                        'name' => $request->post('shareName')
                    ]);

                    $this->notifyAboutShare($this->getVaultUsers($this->businessId));
                }
            } else {
                throw new Exception('Wrong checksum');
            }
            Yii::$app->session->setFlash('success', Translate::_('business', 'Upload was successfully'));
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            Yii::$app->session->setFlash('error', Translate::_('business', 'There was a problem please try again'));
        }

        return $this->redirect(Yii::$app->request->referrer ? Yii::$app->request->referrer : ['index']);
    }

    /**
     * @param $users
     */
    private function notifyAboutShare($users)
    {
        $clientModel = IdbBankClientBusiness::model($this->businessId);
        $portalPeopleApi = PortalApi::getPeopleApi();
        $toSend = [];

        foreach ($users as $user) {
            $relation = $clientModel->getRelatedPeoples($user);

            if (!empty($relation['QueryData'][0][0])) {
                $toSend[] = $relation['QueryData'][0][0];
            }
        }
        if (!empty($toSend)) {
            $portalPeopleApi->requestBusiness2PeopleMessageInfo(
                [
                    'people_users' => [$relation['QueryData'][0][0]],
                    'Business2PeopleFormModel' => [
                        'subject' => Translate::_('business', 'Shared file.'),
                        'message' => Translate::_('business', 'You have new share from {account}', ['account' => Yii::$app->user->identity->accountName]),
                        'business_user' => Yii::$app->user->identity->accountName,
                    ]
                ]
            );
        }
    }

    private function getVaultUsers($vault)
    {
        $clientModel = IdbBankClientBusiness::model($vault);
        $relations = $clientModel->getRelatedPeoplesBusinessId($vault . '.%');

        $users = [];
        foreach ($relations['QueryData'] as $relation) {
            $users[] = $relation[0];
        }

        return $users;
    }

    public function actionDeleteRequest($id)
    {
        $peopleApi = PortalApi::getPeopleApi();
        if ($peopleApi->requestDeleteUploadRequest($id)) {
            Yii::$app->session->setFlash('success', Translate::_('business', 'Deleting was successfully'));

        } else {
            Yii::$app->session->setFlash('error', Translate::_('business', 'There was a problem please try again'));
        }


        return $this->redirect(Yii::$app->request->referrer ? Yii::$app->request->referrer : ['index']);
    }

    public function actionFileRequests()
    {
        Url::remember(['file-requests'], 'idbstorage-file-requests');
        $showAllRequests = filter_var(Yii::$app->session->get('allIdbStorageRequests', false), FILTER_VALIDATE_BOOLEAN);
        $showRequests = Yii::$app->session->get('showIdbStorageRequests', 'inactive');
        $modelRequest = new RequestUploadFormModel();
        $peopleApi = PortalApi::getPeopleApi();
        $requests = $peopleApi->requestUploadRequestsByVaultAndType(['id' => $this->businessId, 'type' => $showRequests]);

        $uploadLimit = Yii::$app->controller->module->configIdbStorage['uploadLimit'];
        $redirectUrl = Url::toRoute('upload', true);
        $provider = new ArrayDataProvider([
            'allModels' => $requests,
            'sort' => [
                'attributes' => ['timestamp' => [
                    'asc' => ['timestamp' => SORT_ASC],
                    'desc' => ['timestamp' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Timestamp',
                ]],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $users = [];
        if (!empty($requests)) {
            $users = json_decode($this->actionGetUsers(), true);
        }

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'file-requests',
                        'contentParams' => compact(
                            'modelRequest',
                            'requests',
                            'showRequests',
                            'provider',
                            'redirectUrl',
                            'uploadLimit',
                            'showAllRequests',
                            'users'
                        )
                    ]
                )
            ]
        );
    }

    /**
     * @return false|string
     * @throws Exception
     */
    public function actionGetUsers()
    {
        $user = Yii::$app->user->identity;
        $businessId = IdbAccountId::generateBusinessDbUserId($user->oid, $user->aid, $user->dbid, '%');
        $clientModel = IdbBankClientBusiness::model($businessId);
        $relations = $clientModel->getRelatedPeoples($businessId);
        $businessesId = $clientModel->getRelatedPeoplesBusinessId($businessId);

        if (empty($relations['QueryData'])) {
            $usersName = [];
        } else {
            $portalPeopleApi = PortalApi::getPeopleApi();
            $usersName = $portalPeopleApi->requestPeopleInfo($relations['QueryData']);
        }

        $userToSend = [];

        foreach ($usersName as $index => $user) {
            $name = $user['name'] . ' ' . $user['surname'];

            $userToSend [] = [
                'id' => $businessesId['QueryData'][$index][0],
                'text' => $name
            ];
        }

        return json_encode($userToSend);
    }

    public function actionShowInactive($showRequests = 'inactive')
    {
        Yii::$app->session->set('showIdbStorageRequests', $showRequests);

        return $this->redirect(Yii::$app->request->referrer ? Yii::$app->request->referrer : ['index']);
    }

    public function actionShowAllUserFiles($all = false)
    {
        Yii::$app->session->set('showAllUserFiles', $all);

        return $this->redirect(Yii::$app->request->referrer ? Yii::$app->request->referrer : ['index']);
    }

    public function actionFileRequest($id)
    {
        $peopleApi = PortalApi::getPeopleApi();
        $files = $peopleApi->requestFilesRequestsByRequest($id);
        $request = $peopleApi->requestUploadRequest($id);
        $model = new RequestUploadUpdateFormModel();
        if (!empty($request)) {
            $parsedId = IdbAccountId::parse($request['pid']);
            $businessId = IdbAccountId::generateBusinessDbId($parsedId['oid'], $parsedId['aid'], $parsedId['dbid']);
            if ($businessId !== $this->businessId) {
                Throw new NotFoundHttpException();
            }
        } else {
            Throw new NotFoundHttpException();
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if ($peopleApi->requestUpdateFileUploadRequest([
                'id' => $id,
                'type' => $model->type === 'complete' ? 'complete' : 'uncomplete',
                'upload_limit' => $model->upload_limit
            ])) {
                Yii::$app->session->setFlash('success', Translate::_('business', 'Request options have been updated'));
            } else {
                Yii::$app->session->setFlash('error', Translate::_('business', 'There was a problem please try again'));
            }
        } else {
            $model->loadByArray($request);
        }


        $requestedFiles = [];
        if(!empty($files)) {
            foreach($files as $file) {
                $response = $this->storageClient->findItemOwnerByOid($file['oid']);
                if(ArrayHelper::getValue($response, 'QueryData.0', false)) {
                    $requestedFiles[] = new IdbStorageItem($response['QueryData'][0]);
                }
            }
        }

        $provider = new ActiveDataProvider(
            [
                'query' => new Query(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );
        $provider->setModels($requestedFiles);

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'file-request',
                        'contentParams' => compact(
                            'request',
                            'requestedFiles',
                            'id',
                            'model',
                            'provider'
                        )
                    ]
                )
            ]
        );
    }

    /**
     * @param $itemId
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDownload($oid)
    {
        try {
            $item = new IdbStorageItem($this->storageClient->findItemOwnerByOid($oid)['QueryData'][0]);
            if ($item->uid === $this->businessId) {
                $object = new IdbStorageObject($this->storageClient->findStorageObjectById($item->oid)['QueryData'][0]);
                $object->attributes['downloads']++;
                $this->storageClient->editStorageObject($object->oid, ['attributes' => json_encode($object->attributes)]);

                $download = $this->storageClient->downloadStorageObject($item->oid, urlencode($item->name))['downloadUrl'];
                $name = $item->name;
                $preventLoading = true;
                return $this->render(
                    '@app/themes/adminlte2/views/site/template',
                    [
                        'params' => ArrayHelper::merge
                        (
                            self::$params,
                            [
                                'content' => 'download',
                                'contentParams' => compact(
                                    'name',
                                    'download',
                                    'preventLoading'
                                )
                            ]
                        )
                    ]
                );
            } else {
                Throw new NotFoundHttpException();
            }
        } catch (Exception $e) {
            Throw new NotFoundHttpException();
        }
    }

    /**
     * @return Response
     */
    public function actionChangeShare()
    {
        try {
            $request = Yii::$app->request;
            $object = new IdbStorageObject($this->storageClient->findStorageObjectById($request->post('shareOid'))['QueryData'][0]);
            $this->storageClient->deleteStorageItemByObjectId($object->oid);

            $this->storageClient->addStorageItem([
                'owner' => "true",
                'oid' => $object->oid,
                'uid' => $this->businessId,
                'type' => 'FILE',
                'name' => $object->metadata['origName']
            ]);
            if (!empty($request->post('people_users')) && $request->post('wholeVault', 'off') !== 'on') {
                foreach ($request->post('people_users') as $user) {
                    $this->storageClient->addStorageItem([
                        'owner' => "false",
                        'oid' => $object->oid,
                        'uid' => $user,
                        'type' => 'FILE',
                        'name' => $object->metadata['origName']
                    ]);
                }
                $this->notifyAboutShare($request->post('people_users'));
            } elseif ($request->post('wholeVault', 'off') === 'on') {
                $this->storageClient->addStorageItem([
                    'owner' => "false",
                    'oid' => $object->oid,
                    'uid' => $this->businessId,
                    'type' => 'FILE',
                    'name' => $object->metadata['origName']
                ]);

            }
            Yii::$app->session->setFlash('success', Translate::_('business', 'Sharing options have been updated'));
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', Translate::_('business', 'There was a problem please try again'));
        }

        return $this->redirect(Yii::$app->request->referrer ? Yii::$app->request->referrer : ['index']);
    }

    /**
     * @param $objectId
     * @return Response
     */
    public function actionDelete($objectId)
    {
        try {
            $object = new IdbStorageObject($this->storageClient->findStorageObjectById($objectId)['QueryData'][0]);
            $this->storageClient->deleteStorageObject($object->id);
            $this->storageClient->deleteStorageItemByObjectId($objectId);

            Yii::$app->session->setFlash('success', Translate::_('business', 'Deleted successfully'));
        } catch (Exception $e) {
            Yii::error('DOWNLOAD IDB STORAGE');
            Yii::error($e->getMessage());
            Yii::$app->session->setFlash('error', Translate::_('business', 'There was a problem please try again'));
        }

        return $this->redirect(Yii::$app->request->referrer ? Yii::$app->request->referrer : ['index']);
    }

    /**
     * @param $oid
     * @return string
     * @throws Exception
     */
    public function actionSummary($oid)
    {
        Url::remember(['summary', 'oid' => $oid], 'idbstorage-summary');

        $model = new BusinessShareFileForm();
        try {
            $item = new IdbStorageItem($this->storageClient->findItemOwnerByOid($oid)['QueryData'][0]);
            $shareItems = IdbStorageItem::initMultiple($this->storageClient->findItemByOid($oid)['QueryData']);
            $object = new IdbStorageObject($this->storageClient->findStorageObjectById($oid)['QueryData'][0]);
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', Translate::_('business', 'File does not exist'));
            return $this->redirect(Url::toRoute('/idb-storage/index'));
        }

        $portalPeopleApi = PortalApi::getPeopleApi();
        $endShare = [];


        /** @var IdbStorageItem $share */
        foreach ($shareItems as $share) {
            $clientModel = IdbBankClientBusiness::model($this->businessId);
            $relation = $clientModel->getRelatedPeoples($share->uid);
            if (empty($relation['QueryData'])) {
                continue;
            }
            $usersName = $portalPeopleApi->requestPeopleInfo($relation['QueryData']);
            $parsedId = IdbAccountId::parse($share->uid);
            $endShare[$share->uid] = [
                'value' => ucfirst($usersName[0]['name']) . ' ' . ucfirst($usersName[0]['surname']),
                'id' => $parsedId['uid'] ?? ''
            ];
        }

        $file_info = pathinfo($item->name);

        $support_type = !empty($file_info['extension'])
            && ($file_info['extension'] === 'pdf'
                || $file_info['extension'] === 'mp3'
                || $file_info['extension'] === 'ogg'
                || $file_info['extension'] === 'wav');

        $data = [
            'oId' => $object->oid,
            'name' => $item->name,
            'createTime' => Localization::getDateTimePortalFormat(new DateTime($item->createtime)),
            'metadata' => $object->metadata,
            'attributes' => $object->attributes,
            'share' => $endShare,
            'download' => Url::toRoute(['download', 'oid' => $item->oid]),
            'model' => $model,
            'itemId' => $item->id,
            'support' => $support_type
        ];

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'summary',
                        'contentParams' => $data
                    ]
                )
            ]
        );
    }

    public function actionRequestFileUpload()
    {
        $request = Yii::$app->request;
        $model = new RequestUploadFormModel();
        try {
            $model->load($request->post());
            $peopleApi = PortalApi::getPeopleApi();

            $data = [];
            foreach ($model->people_user as $people) {
                $data['requests'] [] = [
                    'pid' => $people,
                    'dbid' => $this->businessId,
                    'name' => $model->name,
                    'message' => $model->message,
                    'type' => 'Download request',
                    'uploads' => 0,
                    'upload_limit' => $model->upload_limit
                ];
            }
            $peopleApi->requestAddFileUploadRequests($data);
            Yii::$app->session->setFlash('success', Translate::_('business', 'Requested successfully'));
        } catch (Exception $e) {
            Yii::error('REQUEST IDB STORAGE');
            Yii::error($e->getMessage());
            Yii::$app->session->setFlash('error', Translate::_('business', 'There was a problem please try again'));
        }
        return $this->redirect(Yii::$app->request->referrer ? Yii::$app->request->referrer : ['index']);
    }

    public function actionPreview()
    {
        $oid = Yii::$app->request->get('oid');

        try {
            $item = new IdbStorageItem($this->storageClient->findItemOwnerByOid($oid)['QueryData'][0]);
            if ($item->uid === $this->businessId) {
                $download = $this->storageClient->downloadStorageObject($oid, urlencode($item->name))['downloadUrl'];
            }
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', Translate::_('business', 'File does not exist'));

            return $this->redirect(Url::toRoute('/idb-storage/index'));
        }

        $type = null;
        $format = null;
        $file_info = pathinfo($item->name);

        switch ($file_info['extension']) {
            case 'mp3':
                $type = 'audio';
                $format = 'audio/mpeg';
                break;
            case 'ogg':
                $type = 'audio';
                $format = 'audio/ogg';
                break;
            case 'wav':
                $type = 'audio';
                $format = 'audio/wav';
                break;
            case 'pdf':
                $type = 'pdf';
                $format = 'application/pdf';
                break;
        }

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'preview',
                        'contentParams' => [
                            'src' => $download,
                            'type' => $type,
                            'format' => $format,
                            'name' => $item->name
                        ]
                    ]
                )
            ]
        );
    }


}

################################################################################
#                                End of file                                   #
################################################################################
