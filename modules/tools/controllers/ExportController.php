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
use app\helpers\BusinessConfig;
use Exception;
use idbyii2\helpers\Export;
use idbyii2\helpers\FileHelper;
use idbyii2\helpers\IdbAccountId;
use idbyii2\helpers\Translate;
use idbyii2\models\db\BusinessExport;
use idbyii2\models\idb\IdbBankClientBusiness;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Default controller for the `IdbData` module
 */
class ExportController extends IdbController
{

    private $businessId;
    private $clientModel;
    private $metadata;
    private static $params =
        [
            'menu_active_section' => '[menu][tools]',
            'menu_active_item' => '[menu][tools][export]',
        ];

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
                    'roles' => ['action_export'],
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['action_export'],
                ],
                [
                    'allow' => true,
                    'actions' => ['prepare'],
                    'roles' => ['action_export'],
                ],
                [
                    'allow' => true,
                    'actions' => ['download'],
                    'roles' => ['action_export'],
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
        $this->clientModel = IdbBankClientBusiness::model($this->businessId);
        $this->metadata = json_decode($this->clientModel->getAccountMetadata()['Metadata'], true);

        return $return;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider(
            [
                'query' => BusinessExport::find()->where(['uid' => Yii::$app->user->identity->id])
            ]
        );

        $this->view->title = Translate::_('business', 'Export');

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
                            ]
                    ]
                )
            ]
        );
    }

    /**
     * @return \yii\web\Response
     */
    public function actionDelete()
    {
        if (Yii::$app->getRequest()->isPost) {
            $post = Yii::$app->getRequest()->post();
            if (array_key_exists('id', $post)) {
                $file = BusinessExport::findOne(['id' => $post['id']]);

                if ($file instanceof BusinessExport && $file->status != FileHelper::STATUS_IN_PROGRESS) {
                    $file->status = FileHelper::STATUS_TO_REMOVE;
                    $file->save();
                }

                if ($file instanceof BusinessExport && $file->status == FileHelper::STATUS_TO_REMOVE) {
                    $file->delete();
                }
            }
        }

        return $this->redirect(['export/index']);
    }


    /**
     * @return \yii\web\Response
     */
    public function actionPrepare()
    {
        $attributes = [];
        if (Yii::$app->session->get('search')) {
            $attributes['search'] = Yii::$app->session->get('search');
        }
        if (Yii::$app->session->get('sort-by')) {
            $attributes['sort-by'] = Yii::$app->session->get('sort-by');
        }
        if (Yii::$app->session->get('sort-dir')) {
            $attributes['sort-dir'] = Yii::$app->session->get('sort-dir');
        }
        if (array_key_exists('settings', $this->metadata)) {
            $attributes['columns'] = $this->metadata['settings'];
        }

        try {
            $file = new BusinessExport();
            $file->uid = Yii::$app->user->identity->getId();
            $file->oid = Yii::$app->user->identity->oid;
            $file->aid = Yii::$app->user->identity->aid;
            $file->dbid = Yii::$app->user->identity->dbid;
            $file->attributes = json_encode($attributes);
            $file->status = FileHelper::STATUS_ADDED;
            $file->file_name = FileHelper::FILE_NAME . date('YmdHis');
            $file->file_path = BusinessConfig::get()->getYii2BusinessDownloadLocation()
                . Yii::$app->user->identity->getId() . DIRECTORY_SEPARATOR;
            $file->save();

            Export::executeExportsForDb($file->id);
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }


        return $this->redirect(['index']);
    }

    /**
     * @param $id
     *
     * @return \yii\console\Response|\yii\web\Response
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public function actionDownload($id)
    {
        if (!$id) {
            $this->redirect(['index']);
        }

        $file = BusinessExport::findOne(['id' => $id]);

        if ($file instanceof BusinessExport) {
            if (file_exists($file->getFullPath())) {
                $file->status = FileHelper::STATUS_DOWNLOADED;
                $file->downloaded_at = date('Y-m-d H:i:s');
                $file->save();

                return Yii::$app->response->sendContentAsFile(
                    file_get_contents($file->getFullPath()),
                    "$file->file_name.csv",
                    [
                        'mimeType' => 'application/csv',
                        'charset' => 'UTF-8',
                        'inline' => false
                    ]
                );
            }
        }

        return $this->redirect(['index']);
    }
}

################################################################################
#                                End of file                                   #
################################################################################
