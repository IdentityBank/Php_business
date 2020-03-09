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

namespace app\modules\accessmanager\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use app\helpers\AccessManagerHelper;
use app\helpers\Translate;
use ErrorException;
use Exception;
use idbyii2\helpers\AccessManager;
use idbyii2\helpers\IdbAccountId;
use idbyii2\models\db\AuthAssignment;
use idbyii2\models\db\BusinessAccount;
use idbyii2\models\db\BusinessAccountUser;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\models\db\BusinessDatabaseUser;
use idbyii2\models\db\BusinessUserData;
use idbyii2\models\db\RolesModel;
use idbyii2\models\idb\IdbBankClientBusiness;
use idbyii2\validators\IdbNameValidator;
use RuntimeException;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Class DatabaseManagerController
 *
 * @package app\modules\accessmanager\controllers
 */
class DatabaseManagerController extends IdbController
{

    private static $params = [
        'menu_active_section' => '[menu][admin_db]',
        'menu_active_item' => '[menu][admin_db][db_manager]'
    ];
    public $defaultAction = 'manage-database';
    /** @var BusinessIdBankClient */
    private $clientModel;

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (is_array($behaviors['access']['rules'])) {
            $behaviors['access']['rules'] = [
                [
                    'allow' => true,
                    'actions' => [
                        'manage-database',
                        'edit-database',
                        'reset-database',
                        'delete-database'
                    ],
                    'roles' => ['action_database_manager'],
                ],
            ];
        }

        return $behaviors;
    }

    /**
     * @return mixed
     */
    public function actionManageDatabase()
    {
        $this->view->params['contextHelpUrl'] = Translate::_(
            'business',
            'https://www.identitybank.eu/help/business/manage/vault'
        );
        $db = BusinessDatabase::find()->where(
            [
                'dbid' => Yii::$app->user->identity->dbidInfo['dbid'],
                'aid' => Yii::$app->user->identity->dbidInfo['aid']
            ]
        )->one();
        $this->view->title = Translate::_('business', 'Manage vault');

        $buttons = [];
        $buttons[] =
            [
                'id' => "update-db-button",
                'buttonTitle' => '<i class="fa fa-edit"></i>' . Translate::_('business', 'Rename vault'),
                'class' => "btn btn-app",
                'data-toggle' => "modal",
                'data-target' => "#modal-update-db-attributes"
            ];
        $buttons[] =
            [
                'id' => "reset-db-button",
                'buttonTitle' => '<i class="fa fa-undo"></i>' . Translate::_('business', 'Reset vault'),
                'class' => "btn btn-app btn-app-yellow",
                'data-toggle' => "modal",
                'data-target' => "#cancelResetActionModal"
            ];
        $buttons[] =
            [
                'id' => "delete-db-button",
                'buttonTitle' => '<i class="fa fa-trash-o"></i>' . Translate::_('business', 'Delete vault'),
                'class' => "btn btn-app btn-app-red",
                'data-toggle' => "modal",
                'data-target' => "#cancelDeleteActionModal"
            ];

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'manage-database',
                        'contentParams' => [
                            'db' => $db,
                            'buttons' => $buttons
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @return array
     */
    public function actionEditDatabase()
    {
        $request = Yii::$app->request;
        $dbid = $request->post('dbid') ?? ($request->post('BusinessDatabase')['dbid'] ?? null);
        $dbname = $request->post('name') ?? ($request->post('BusinessDatabase')['name'] ?? null);
        $dbdescription = $request->post('description') ?? ($request->post('BusinessDatabase')['description'] ?? null);

        if (!empty($dbid)) {
            $db = BusinessDatabase::findOne(['dbid' => $dbid]);
            if (
                !empty($db)
                && in_array($db->dbid, Yii::$app->user->identity->getAllUserDatabases())
            ) {
                $refreshMenu = false;
                if (!empty($dbname)) {
                    $refreshMenu = $db->name !== $dbname;
                    $db->name = $dbname;
                }

                if (!empty($dbdescription) || $dbdescription === '') {
                    $db->description = $dbdescription;
                }

                $saveStatus = $db->save();

                if ($request->isAjax) {
                    if ($refreshMenu) {
                        return $this->redirect(['/']);
                    } else {
                        return json_encode(['success' => $saveStatus]);
                    }
                }
            }
        }

        if ($request->isAjax) {
            return json_encode(['success' => false]);
        }

        return $this->redirect(['manage-database']);
    }

    /**
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionResetDatabase()
    {
        $business_db_id = IdbAccountId::generateBusinessDbId(
            Yii::$app->user->identity->oid,
            Yii::$app->user->identity->aid,
            Yii::$app->user->identity->dbid
        );

        $resetStatus = AccessManagerHelper::deleteDatabase($business_db_id, true);

        // TODO: Display status - add support here

        try {
            Yii::$app->cacheApc->flush();
        } catch (RuntimeException $e) {
            Yii::error($e->getMessage());
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        } catch (ErrorException $e) {
            Yii::error($e->getMessage());
        }

        return $this->redirect(['manage-database']);
    }

    /**
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteDatabase()
    {
        $business_db_id = IdbAccountId::generateBusinessDbId(
            Yii::$app->user->identity->oid,
            Yii::$app->user->identity->aid,
            Yii::$app->user->identity->dbid
        );

        $resetStatus = AccessManagerHelper::deleteDatabase($business_db_id);
        Yii::$app->user->identity->resetUserDatabases();

        // TODO: Display status - add support here

        try {
            Yii::$app->cacheApc->flush();
        } catch (RuntimeException $e) {
            Yii::error($e->getMessage());
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        } catch (ErrorException $e) {
            Yii::error($e->getMessage());
        }

        return $this->goHome();
    }

}

################################################################################
#                                End of file                                   #
################################################################################
