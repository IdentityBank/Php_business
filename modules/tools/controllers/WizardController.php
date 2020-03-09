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

namespace app\modules\tools\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use app\helpers\AccessManagerHelper;
use app\helpers\BusinessConfig;
use app\helpers\ConfigHelper;
use app\helpers\CreateDatabase;
use app\helpers\Translate;
use DateInterval;
use DateTime;
use Exception;
use idbyii2\helpers\DataJSON;
use idbyii2\helpers\Event;
use idbyii2\helpers\FileHelper;
use idbyii2\helpers\IdbAccountId;
use idbyii2\helpers\Import;
use idbyii2\helpers\Localization;
use idbyii2\helpers\Metadata;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\models\db\BusinessDatabaseUser;
use idbyii2\models\db\BusinessImport;
use idbyii2\models\db\BusinessImportWorksheet;
use idbyii2\models\db\BusinessUserData;
use idbyii2\models\db\IdbAuditMessage;
use idbyii2\models\form\BusinessRetentionPeriodForm;
use idbyii2\models\idb\IdbBankClientBusiness;
use idbyii2\validators\IdbNameValidator;
use Throwable;
use Yii;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Class WizardController
 *
 * @package app\modules\tools\controllers
 */
class WizardController extends IdbController
{

    private static $params = [
        'menu_active_section' => '[menu][tools]',
        'menu_active_item' => '[menu][tools][import]',
    ];

    private $businessId;
    private $businessUserId;
    private $clientModel;
    private $metadata;

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
                    'roles' => ['action_import'],
                ],
            ];
        }

        return $behaviors;
    }

    /**
     * @param $action
     *
     * @return bool|Response
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $return = parent::beforeAction($action);
        if (!$return) {
            return $return;
        }

        $user = Yii::$app->user->identity;
        $this->businessId = IdbAccountId::generateBusinessDbId($user->oid, $user->aid, $user->dbid);
        $this->businessUserId = $this->businessId . 'uid' . $user->getId();
        $this->clientModel = IdbBankClientBusiness::model($this->businessId);
        $this->metadata = json_decode($this->clientModel->getAccountMetadata()['Metadata'], true);

        return $return;
    }

    /**
     * @return string|Response
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionSelectDb()
    {
        $post = Yii::$app->request->post();

        if (Yii::$app->request->isPost) {
            if (empty($post['dbid'])) {
                Yii::$app->session->setFlash(
                    'error',
                    Translate::_('business', 'Choose at least one vault.')
                );

                return $this->redirect(['select-db']);
            }

            if ($post['dbid'] === 'new') {
                $uid = Yii::$app->user->identity->id;
                $dbName = IdbNameValidator::adjustName($post['dbname'] ?? Translate::_('business', 'Imported vault'));

                $db = AccessManagerHelper::createDatabase($uid, $dbName);

                if ($db instanceof BusinessDatabaseUser) {
                    try {
                        $user = Yii::$app->user->identity;
                        $businessId = IdbAccountId::generateBusinessDbId($user->oid, $user->aid, $db->dbid);

                        $this->clientModel = IdbBankClientBusiness::model($businessId);

                        $metadata = json_decode($this->clientModel->getAccountMetadata()['Metadata'], true);

                        if (!array_key_exists('options', $metadata)) {
                            $metadata['options'] = ['send_sms' => 'off', 'send_mail' => 'off'];
                        }

                        $model = BusinessUserData::instantiate(['uid' => Yii::$app->user->identity->id]);
                        $model = BusinessUserData::findOne(
                            [
                                'uid' => Yii::$app->user->identity->id,
                                'key_hash' => $model->getKeyHash(
                                    Yii::$app->user->identity->id,
                                    'dbid'
                                )
                            ]
                        );

                        if (is_null($model)) {
                            $business = BusinessUserData::instantiate(
                                ['uid' => Yii::$app->user->identity->id, 'key' => 'dbid', 'value' => $db->dbid]
                            );
                            $business->save();
                        } else {
                            $model->updateAid($db->dbid);
                        }

                        $this->clientModel->setAccountMetadata(json_encode($metadata));

                        Yii::$app->session->setFlash(
                            'success',
                            Translate::_('business', 'The vault has been created.')
                        );

                        Yii::$app->session->set('importSteps', 'select-db');

                        return $this->redirect(['/idb-menu', 'dbid' => $db->dbid, 'action' => '/tools/wizard/index']);
                    } catch (Exception $e) {
                        Yii::$app->session->setFlash(
                            'error',
                            Translate::_(
                                'business',
                                'An error has occured. Please contact your system administrator.'
                            )
                        );

                        return $this->redirect(['select-db']);
                    }
                }
            } else {
                Yii::$app->session->set('importSteps', 'select-db');

                return $this->redirect(['/idb-menu', 'dbid' => $post['dbid'], 'action' => '/tools/wizard/index']);
            }

            Yii::$app->session->set('importSteps', 'select-db');

            return $this->redirect(['index']);
        }

        $this->view->title = Translate::_('business', 'Select a vault');

        $userDatabases = BusinessDatabaseUser::find()
            ->where(['uid' => Yii::$app->user->identity->id])
            ->select('dbid')
            ->asArray()
            ->all();
        $userDatabases = ArrayHelper::getColumn($userDatabases, 'dbid');
        $accountDatabases = BusinessDatabase::find()->where(['aid' => Yii::$app->user->identity->aid])->orderBy(
            ['name' => SORT_ASC]
        )->asArray()->all();
        $dbsArray = [];
        foreach ($accountDatabases as $accountDatabase) {
            if (in_array($accountDatabase['dbid'], $userDatabases)) {
                $dbsArray[$accountDatabase['dbid']] = $accountDatabase['name'];
            }
        }

        $model = new DynamicModel(['dbid']);


        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    [
                        'menu_active_section' => '[menu][tools]',
                        'menu_active_item' => '[menu][tools][import][select-db]',
                    ],
                    [
                        'content' => 'select-db',
                        'contentParams' =>
                            [
                                'model' => $model,
                                'dbsArray' => $dbsArray
                            ]

                    ]
                )
            ]
        );
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->view->title = Translate::_('business', 'Select a file to import');

        $post = Yii::$app->getRequest()->post();
        $dbidInfo = Yii::$app->user->identity->dbidInfo;

        if (Yii::$app->getRequest()->isPost && array_key_exists('radioButtonSelection', $post)) {
            $file = BusinessImport::findOne(['id' => $post['radioButtonSelection']]);

            if ($file instanceof BusinessImport) {
                $worksheets = BusinessImportWorksheet::findAll(['file_id' => $post['radioButtonSelection']]);

                $file->aid = Yii::$app->user->identity->aid;
                $file->uid = Yii::$app->user->identity->id;
                $file->dbid = Yii::$app->user->identity->dbid;

                if (Yii::$app->session->has('importSteps')) {
                    $file->steps = json_encode([Yii::$app->session->get('importSteps'), 'index']);
                    Yii::$app->session->remove('importSteps');
                } else {
                    $file->steps = json_encode(['index']);
                }
                if (count($worksheets) == 0) {
                    $file->status = FileHelper::STATUS_ADDED;
                } else {
                    $file->status = FileHelper::STATUS_CONVERTED;

                    /** @var BusinessImportWorksheet $worksheet */
                    foreach ($worksheets as $worksheet) {
                        $worksheet->dbid = Yii::$app->user->identity->dbid;
                        $worksheet->aid = Yii::$app->user->identity->aid;
                        $worksheet->uid = Yii::$app->user->identity->id;
                        $worksheet->status = FileHelper::STATUS_ADDED;
                        try {
                            $worksheet->save();
                        } catch (Exception $e) {
                            var_dump($e->getMessage());
                        }
                    }
                }

                try {
                    $file->save();
                    Import::executeImportsForDb($post['radioButtonSelection']);
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                }
            }

            return $this->redirect(['/tools/wizard/worksheets', 'file' => $post['radioButtonSelection']]);
        }

        $dataProvider = new ActiveDataProvider(
            [
                'query' => BusinessImport::find()
                    ->where(
                        ['>', 'created_at', Localization::getDatabaseDateTime((new DateTime())->sub(new DateInterval('P1M')))]
                    )
                    ->andWhere(
                        [
                            'uid' => Yii::$app->user->identity->id,
                            'oid' => Yii::$app->user->identity->oid
                        ]
                    )->orderBy(['created_at' => SORT_DESC]),
                'pagination' => [
                    'pageSize' => 5,
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
                        'contentParams' =>
                            [
                                'dataProvider' => $dataProvider,
                                'backButton' => Yii::$app->session->has('importSteps') ? Yii::$app->session->get(
                                    'importSteps'
                                ) : null,
                                'dbidInfo' => $dbidInfo
                            ]

                    ]
                )
            ]
        );
    }

    /**
     * @return void|Response
     * @throws BadRequestHttpException
     */
    public function actionFile()
    {
        if (
            !BusinessConfig::get()->getYii2BusinessUploadEnabled()
            || empty($_FILES['file']['name'])
            || empty($_FILES['file']['tmp_name'])
            || (isset($_FILES['file']['error']) && ($_FILES['file']['error'] != UPLOAD_ERR_OK))
        ) {
            throw new BadRequestHttpException(
                Translate::_('business', 'An error occurred during the file upload, please try again.')
            );

            return;
        }

        $_FILES['file']['name'] = strip_tags(str_replace('%', '_', $_FILES['file']['name']));

        $uploadMaxFilesize = BusinessConfig::get()->getYii2BusinessUploadMaxFilesize() * (1024 * 1024);
        $targetDir = Import::getTargetDir(
            BusinessConfig::get()->getYii2BusinessUploadLocation(),
            Yii::$app->user->identity->id
        );

        $post = Yii::$app->request->post();

        if (
            (isset($post['chunkIndex']) && empty($post['chunkDataBlockSize']))
            || empty($post['filesize'])
            || $post['filesize'] > $uploadMaxFilesize
            || empty($post['fileuuid'])
            || empty($post['client_md5'])
        ) {
            throw new BadRequestHttpException(
                Translate::_('business', 'An error occurred during the file upload, please try again.')
            );

            return;
        }

        $data = file_get_contents($_FILES['file']["tmp_name"]);
        $name = preg_replace('/[^a-zA-Z0-9-]/', '', $post['fileuuid']);
        $name = substr($name, 0, 64);

        if (empty($name)) {
            throw new BadRequestHttpException(Translate::_('business', 'Incorrect filename.'));

            return;
        }

        $filepath = $targetDir . $name;

        if (file_exists($filepath)) {
            $uploadedFilesize = filesize($filepath);
            if (($uploadedFilesize + strlen($data)) > $post['filesize']) {
                throw new BadRequestHttpException(Translate::_('business', 'Incorrect filesize'));

                return;
            }
        }

        file_put_contents($filepath, $data, FILE_APPEND);
        clearstatcache(true, $filepath);
        $uploadedFilesize = filesize($filepath);

        if ($uploadedFilesize < $post['filesize']) {
            return;
        }
        if ($uploadedFilesize > $post['filesize']) {
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            throw new BadRequestHttpException(Translate::_('business', 'Filesize doesn\'t match.'));

            return;
        }

        $userData = [
            'oid' => Yii::$app->user->identity->oid,
            'aid' => Yii::$app->user->identity->aid,
            'dbid' => Yii::$app->user->identity->dbid,
            'uid' => Yii::$app->user->identity->id,
            'step' => Yii::$app->session->has('importSteps') ? Yii::$app->session->get('importSteps') : null
        ];

        $response = Import::prepareRetrunFile($filepath, $targetDir, $_FILES['file']['name'], $userData, true);

        if (array_key_exists('file', $response)) {
            try {

                Import::executeImportsForDb($response['file']);
            } catch (Exception $e) {
                var_dump($e->getMessage());
            }
            unset($response['file']);
        }

        echo json_encode($response);
    }

    /**
     * @param $file
     *
     * @return string
     */
    public function actionWorksheets($file)
    {
        if (Yii::$app->request->isPost && !empty(Yii::$app->request->post('radioButtonSelection'))) {
            $worksheetId = Yii::$app->request->post('radioButtonSelection');
            $worksheet = BusinessImportWorksheet::findOne(['id' => $worksheetId]);

            if ($worksheet instanceof BusinessImportWorksheet) {
                /** @var BusinessImport $fileObject */
                $fileObject = $worksheet->getFile()->one();
                $steps = json_decode($fileObject->steps, true);
                array_push($steps, 'worksheets');
                $worksheet->steps = json_encode($steps);
                try {
                    $worksheet->save();
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                }
            }

            return $this->redirect(['/tools/wizard/data-types', 'file' => $file, 'id' => $worksheetId]);
        }
        $dataProvider = new ActiveDataProvider(
            [
                'query' => BusinessImportWorksheet::find()->where(
                    [
                        'import_worksheet.uid' => Yii::$app->user->identity->id,
                        'import_worksheet.oid' => Yii::$app->user->identity->oid,
                        'import_worksheet.file_id' => $file
                    ]
                )->joinWith('file', true)
            ]
        );
        $fileObject = BusinessImport::findOne(['id' => $file]);
        $status = null;

        if ($fileObject instanceof BusinessImport) {
            $status = $fileObject->status;
        }

        if ($fileObject->status === FileHelper::STATUS_CONVERTED) {
            $worksheets = BusinessImportWorksheet::findAll(['file_id' => $file]);

            if (count($worksheets) == 1) {
                foreach ($worksheets as $worksheet) {
                    return $this->redirect(['/tools/wizard/data-types', 'file' => $file, 'id' => $worksheet->id]);
                }
            } else {
                $steps = json_decode($fileObject->steps, true);
                array_push($steps, 'worksheets');
                $fileObject->steps = json_encode($steps);
                try {
                    $fileObject->save();
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                }
            }
        }

        $this->view->title = \idbyii2\helpers\Translate::_('business', 'Select worksheets');

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'sheet',
                        'contentParams' =>
                            [
                                'dataProvider' => $dataProvider,
                                'id' => $file,
                                'status' => $status
                            ]
                    ]
                )
            ]
        );
    }

    /**
     * @param $file
     * @param $id
     *
     * @return string|Response
     */
    public function actionDataTypes($file, $id)
    {
        try {
            $post = Yii::$app->getRequest()->post();

            /** @var BusinessImportWorksheet $worksheet */
            $worksheet = BusinessImportWorksheet::findOne(
                ['id' => $id, 'uid' => Yii::$app->user->identity->id, 'oid' => Yii::$app->user->identity->oid]
            );
            $columns = [];

            if (!empty($this->metadata) && array_key_exists('data', $this->metadata)) {
                foreach ($this->metadata['data'] as $value) {
                    if (!array_key_exists('object_type', $value)) {
                        continue;
                    }

                    if ($value['object_type'] === 'type') {
                        $columns[$value['uuid']] = $value['display_name'];
                    }

                    if ($value['object_type'] === 'set') {
                        foreach ($value['data'] as $nr => $type) {
                            $columns[$type['uuid']] = strtoupper('set:' . $value['display_name']) . '-'
                                . $type['display_name'];
                        }
                    }

                }
            }

            $dir = Import::getTargetDir(
                \BusinessConfig::get()->getYii2BusinessUploadLocation(),
                Yii::$app->user->identity->id
            );

            /** @var BusinessImport $fileToImport */
            $fileToImport = $worksheet->getFile()->one();
            $headers = Import::getHeadersFromFile(
                $dir,
                $fileToImport->file_name,
                $worksheet->worksheet_id,
                $worksheet->name
            );
            $headersNameArray = [];
            $headersArray = [];
            $attributes = [];

            $index = 0;
            foreach ($headers as $header) {
                $headerName = $header;
                $header = preg_replace('/[\'".()<>\/]/', '_', $header);
                $header = htmlspecialchars($header);
                $headerInternal = str_replace(' ', '_', $header);
                $headersArray[] = [
                    'header' => $header,
                    'headerInternal' => $headerInternal,
                    'type' => DataJSON::NEW_COLUMN,
                    'index' => $index
                ];
                $headersNameArray[$header] = $headerName;
                array_push($attributes, $headerInternal);
                $index++;
            }

            if (array_key_exists('DynamicModel', $post)) {
                $data = [];

                $index = 0;
                foreach ($post['DynamicModel'] as $header => $type) {
                    $pureHeader = $header;
                    if ($type === 'new') {
                        foreach ($headersArray as $headerTmp) {
                            if ($headerTmp['headerInternal'] === $header) {
                                $pureHeader = $headerTmp['header'];
                            }
                        }
                    }
                    $data[] = [
                        'header' => $pureHeader,
                        'type' => $type,
                        'index' => $index
                    ];
                    $index++;
                }

                if (empty($this->metadata)) {
                    $formattedArray = DataJSON::getFormattedArray($data);
                    $this->clientModel->setAccountMetadata(json_encode($formattedArray));

                    if (array_key_exists('database', $formattedArray) && !empty($formattedArray['database'])) {
                        $newColumns = DataJSON::getFormattedDataToAddColumns($formattedArray['database']);
                        $this->clientModel->updateDataTypes($newColumns);
                    }

                } else {
                    $formattedNewColumns = DataJSON::getFormattedArray($data);
                    if (
                        array_key_exists('database', $formattedNewColumns)
                        && !empty($formattedNewColumns['database'])
                    ) {
                        $newColumns = DataJSON::getFormattedDataToAddColumns($formattedNewColumns['database']);
                        $this->clientModel->updateDataTypes($newColumns);
                    }

                    $formattedMappingInfo['headerMapping'] = $formattedNewColumns['headerMapping'];

                    if (!empty($formattedNewColumns['data'])) {
                        if (array_key_exists('data', $this->metadata)) {
                            $this->metadata['data'] = array_merge(
                                $this->metadata['data'],
                                $formattedNewColumns['data']
                            );
                        } else {
                            $this->metadata['data'] = $formattedNewColumns['data'];
                        }

                        if (array_key_exists('database', $this->metadata)) {
                            $this->metadata['database'] = array_merge(
                                $this->metadata['database'],
                                $formattedNewColumns['database']
                            );
                        } else {
                            $this->metadata['database'] = $formattedNewColumns['database'];
                        }
                    }

                    $this->metadata['headerMapping'] = [];
                    if (!empty($formattedMappingInfo['headerMapping'])) {
                        $this->metadata['headerMapping'] = $formattedMappingInfo['headerMapping'];
                    }

                    $this->clientModel->setAccountMetadata(json_encode($this->metadata));
                }

                $worksheet = BusinessImportWorksheet::findOne(
                    [
                        'id' => $id,
                        'uid' => Yii::$app->user->identity->id,
                        'oid' => Yii::$app->user->identity->oid
                    ]
                );
                if ($worksheet instanceof BusinessImportWorksheet) {
                    $worksheet->status = FileHelper::STATUS_TO_IMPORT;
                    /** @var BusinessImport $fileToImport */
                    $fileToImport = $worksheet->getFile()->one();
                    $worksheet->steps = $fileToImport->steps;
                    $steps = json_decode($fileToImport->steps, true);
                    array_push($steps, 'data-types');
                    $worksheet->steps = json_encode($steps);
                    try {
                        $worksheet->save();
                    } catch (Exception $e) {
                        var_dump($e->getMessage());
                    }
                    $worksheet->save();

                    return $this->redirect(['/tools/wizard/send-mails', 'file' => $file, 'id' => $id]);
                }

            }

            $attributes = array_flip($attributes);

            if (empty($columns)) {
                $formattedArray = DataJSON::getFormattedArray($headersArray);
                if (!empty($formattedArray['columns']) && is_array($formattedArray['columns'])) {
                    foreach ($formattedArray['columns'] as $index => $column) {
                        $formattedArray['columns'][$index]['title'] = $headersNameArray[$column['title']];
                    }
                }
                if (!empty($formattedArray['data']) && is_array($formattedArray['data'])) {
                    foreach ($formattedArray['data'] as $index => $data) {
                        $formattedArray['data'][$index]['display_name'] = $headersNameArray[$data['display_name']];
                    }
                }
                $this->clientModel->setAccountMetadata(json_encode($formattedArray));
                if (array_key_exists('database', $formattedArray) && !empty($formattedArray['database'])) {
                    $newColumns = DataJSON::getFormattedDataToAddColumns($formattedArray['database']);
                    $this->clientModel->updateDataTypes($newColumns);
                }

                $worksheet = BusinessImportWorksheet::findOne(
                    [
                        'id' => $id,
                        'uid' => Yii::$app->user->identity->id,
                        'oid' => Yii::$app->user->identity->oid
                    ]
                );
                if ($worksheet instanceof BusinessImportWorksheet) {
                    $worksheet->status = FileHelper::STATUS_TO_IMPORT;
                    $worksheet->steps = $fileToImport->steps;
                    try {
                        $worksheet->save();
                    } catch (Exception $e) {
                        var_dump($e->getMessage());
                    }

                    return $this->redirect(['/tools/wizard/send-mails', 'file' => $file, 'id' => $id]);
                }
            }

            if (!empty($columns)) {
                $percent = null;
                foreach ($headersArray as $header) {
                    foreach ($columns as $uuid => $name) {
                        if (strpos('set:', $name)) {
                            str_replace('set:', '', $name);
                            $colData = explode('-', $name);
                            $name = $colData[1];
                        }
                        similar_text($headersNameArray[$header['header']], $name, $percent);
                        if (intval($percent) >= 85 && array_key_exists($header['headerInternal'], $attributes)) {
                            $attributes[$header['headerInternal']] = $uuid;
                        }
                    }
                }
            }


            $model = new DynamicModel($attributes);
            $model->addRule($attributes, 'required');
            $model->addRule($attributes, 'string');


            $this->view->title = Translate::_('business', 'Data Types');

            return $this->render(
                '@app/themes/adminlte2/views/site/template',
                [
                    'params' => ArrayHelper::merge
                    (
                        self::$params,
                        [
                            'content' => 'datatypes',
                            'contentParams' =>
                                [
                                    'model' => $model,
                                    'types' => $columns,
                                    'headers' => $headersArray,
                                    'worksheetId' => $id,
                                    'worksheet' => $worksheet,
                                    'file' => $file
                                ]
                        ]
                    )
                ]
            );
        } catch (Exception $e) {
            $flash = [
                'subject' => Translate::_('business', 'error'),
                'message' => Translate::_(
                    'business',
                    'The file is incorrect.'
                )
            ];

            Yii::$app->session->setFlash('error', $flash);

            return $this->redirect(['/']);
        }
    }

    /**
     * @param $file
     * @param $id
     *
     * @return string|Response
     */
    public function actionSendMails($file, $id)
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            if (array_key_exists('DynamicModel', $post)) {
                if (array_key_exists('send_email', $post['DynamicModel'])) {
                    if ($post['DynamicModel']['send_email'] === '1') {
                        $worksheet = BusinessImportWorksheet::findOne(['id' => $id, 'file_id' => $file]);

                        if (!$worksheet instanceof BusinessImportWorksheet) {
                            return $this->redirect(['/tools/wizard/worksheets', 'file' => $file]);
                        }

                        $attributes = json_decode($worksheet->import_attributes, 1);

                        if (array_key_exists('valid_both', $post['DynamicModel'])) {
                            $attributes['valid_both'] = boolval($post['DynamicModel']['valid_both']);
                        } else {
                            $attributes['valid_both'] = false;
                        }

                        if (array_key_exists('phone_code', $post['DynamicModel'])) {
                            $attributes['phone_code'] = $post['DynamicModel']['phone_code'];
                        } else {
                            $attributes['phone_code'] = '0';
                        }
                        $worksheet->send_invitations = true;
                        $worksheet->import_attributes = json_encode($attributes);

                        try {
                            $steps = json_decode($worksheet->steps, true);
                            array_push($steps, 'send-mails');
                            $worksheet->steps = json_encode($steps);
                            $worksheet->save();
                        } catch (Exception $e) {
                            var_dump($e->getMessage());
                        }
                    }

                    try {
                        Yii::$app->session->set('lastIndex', $this->clientModel->findLastId());
                        Import::executeImportsForDb($file, $id);
                    } catch (Exception $e) {
                        var_dump($e->getMessage());
                    }
                }
                unset($post['DynamicModel']['send_email']);
                unset($post['DynamicModel']['valid_both']);
                unset($post['DynamicModel']['phone_code']);

                $this->metadata['PeopleAccessMap'] = $post['DynamicModel'];
                $this->clientModel->setAccountMetadata(json_encode($this->metadata));

                return $this->redirect(['/tools/wizard/law-basis', 'id' => $id, 'file' => $file,]);
            }
        }

        $response = ConfigHelper::prepareMapping($this->metadata);

        $arrayMetadata = $response['arrayMetadata'];
        $model = $response['model'];
        $model->send_email = true;

        if (count($arrayMetadata) < 3) {
            return $this->redirect(['/idbdata/data-client-sets/create']);
        }

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge(
                    self::$params,
                    [
                        'content' => 'map',
                        'contentParams' => [
                            'metadata' => $arrayMetadata,
                            'model' => $model,
                            'file' => $file,
                            'id' => $id,
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @param $id
     * @param $file
     * @return string|Response
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionLawBasis($id, $file)
    {
        $model = new DynamicModel(['messages', 'legal', 'message', 'purposeLimitation']);
        $model->addRule(['message', 'purposeLimitation', 'legal'], 'required');
        $model->addRule(['message', 'purposeLimitation'], 'string');
        if (!empty(Yii::$app->request->post('DynamicModel'))) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $model = new DynamicModel(Yii::$app->request->post('DynamicModel'));
                Metadata::addToGdpr(
                    $this->metadata,
                    [
                        'lawfulBasis' => $model['legal'],
                        'lawfulMessage' => $model['message'],
                        'purposeLimitation' => $model['purposeLimitation']
                    ]
                );
                $this->clientModel->setAccountMetadata(json_encode($this->metadata));
                IdbAuditMessage::saveMessage($model['message']);

                return $this->redirect(['/tools/wizard/dpos', 'id' => $id, 'file' => $file]);
            }
        }

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
            $model['message'] = $messagesObjects[0]->message;
        }

        $model->purposeLimitation = Metadata::getPurposeLimitation($this->metadata);

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge(
                    self::$params,
                    [
                        'content' => 'lawBasis',
                        'contentParams' => [
                            'model' => $model,
                            'file' => $file,
                            'id' => $id,
                            'messages' => $messages,
                            'legal' => $legals,
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @param $id
     * @param $file
     * @return string|Response
     */
    public function actionDpos($id, $file)
    {
        if (!empty(Yii::$app->request->isPost)) {
            if (!empty(Yii::$app->request->post('dpo'))) {
                Metadata::addToGdpr(
                    $this->metadata,
                    ['listDataProcessors' => Yii::$app->request->post('dpo')]
                );
                $this->clientModel->setAccountMetadata(json_encode($this->metadata));
            }

            return $this->redirect(['/tools/wizard/retention-period', 'id' => $id, 'file' => $file]);
        }

        $gdpr = Metadata::getGDPR($this->metadata);
        $gdpr = $gdpr[array_key_first($gdpr)];
        $dpos = ArrayHelper::getValue($gdpr, 'listDataProcessors', []);
        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge(
                    self::$params,
                    [
                        'content' => 'dpos',
                        'contentParams' => compact('file', 'id', 'dpos')
                    ]
                )
            ]
        );
    }

    /**
     * @param $id
     * @param $file
     * @return string|Response
     * @throws Exception
     */
    public function actionRetentionPeriod($id, $file)
    {
        $model = new BusinessRetentionPeriodForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $data = [];
                foreach ($model->attributes() as $attribute) {
                    if (!empty($model->$attribute)) {
                        $data[$attribute] = $model->$attribute;
                    }
                }

                if (empty($data['maximum'])) {
                    unset($data['onExpiry']);
                } else {
                    $data['maximum'] = intval($data['maximum']) * 24;
                    if (!empty($data['minimum'])) {
                        $data['minimum'] = intval($data['minimum']) * 24;
                    }
                    if (!empty($data['reviewCycle'])) {
                        $data['reviewCycle'] = intval($data['reviewCycle']) * 24;
                    }
                }


                if (!empty($data)) {
                    Metadata::addToGdpr(
                        $this->metadata,
                        [
                            'minimum' => $model->minimum,
                            'maximum' => $model->maximum,
                            'explanation' => $model->explanation,
                            'reviewCycle' => $model->reviewCycle,
                            'onExpiry' => $model->onExpiry
                        ]
                    );

                    $this->clientModel->setAccountMetadata(json_encode($this->metadata));
                    $lastId = Yii::$app->session->get('lastIndex', 0);
                    Event::importAddEvents($this->clientModel, $lastId, $data, $this->metadata);
                }

                return $this->redirect(['/tools/wizard/summary', 'id' => $id, 'file' => $file]);
            }
        }

        $gdpr = Metadata::getGDPR($this->metadata);
        if (!empty($gdpr)) {
            $attributes = [
                'minimum',
                'maximum',
                'explanation',
                'reviewCycle',
                'onExpiry'
            ];
            $gdpr = $gdpr[array_key_first($gdpr)];
            foreach ($attributes as $attribute) {
                if (!empty($gdpr[$attribute])) {
                    $model->{$attribute} = $gdpr[$attribute];
                }
            }
        }

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge(
                    self::$params,
                    [
                        'content' => 'retentionPeriod',
                        'contentParams' => [
                            'model' => $model,
                            'file' => $file,
                            'id' => $id
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @param $id
     * @param $file
     *
     * @return string
     * @throws Exception
     */
    public function actionSummary($id, $file)
    {
        $fileObject = BusinessImport::findOne(['id' => $file]);
        $worksheet = BusinessImportWorksheet::findOne(['id' => $id]);

        try {
            $steps = json_decode($worksheet->steps, true);
            array_push($steps, 'summary');
            $worksheet->steps = json_encode($steps);
            $worksheet->save();
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'summary',
                        'contentParams' =>
                            [
                                'file' => $fileObject,
                                'worksheet' => $worksheet
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
