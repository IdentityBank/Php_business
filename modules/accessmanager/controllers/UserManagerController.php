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
use app\helpers\Translate;
use idbyii2\enums\EmailActionType;
use idbyii2\helpers\AccessManager;
use idbyii2\helpers\EmailTemplate;
use idbyii2\models\data\IdbBusinessUserDataSearchUserAccountProvider;
use idbyii2\models\db\AuthAssignment;
use idbyii2\models\db\BusinessAccount;
use idbyii2\models\db\BusinessAccountUser;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\models\db\BusinessDatabaseUser;
use idbyii2\models\db\BusinessSignup;
use idbyii2\models\db\BusinessUserData;
use idbyii2\models\db\RolesModel;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Class UserManagerController
 *
 * @package app\modules\accessmanager\controllers
 */
class UserManagerController extends IdbController
{

    private static $params = [
        'menu_active_section' => '[menu][account_administration]',
        'menu_active_item' => '[menu][admin_db][user_manager]',
    ];

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
                    'roles' => ['action_manage_users'],
                ],
            ];
        }

        return $behaviors;
    }


    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        $ids = [];
        $accountUsers = BusinessAccountUser::findAll(['aid' => Yii::$app->user->identity->aid]);

        if (count($accountUsers)) {
            foreach ($accountUsers as $accountUser) {
                array_push($ids, $accountUser->uid);
            }
        }

        $dataProvider = new IdbBusinessUserDataSearchUserAccountProvider();
        $dataProvider->init($ids, ['firstname', 'lastname', 'accountNumber', 'email', 'mobile']);

        $this->view->title = Translate::_('business', 'Manage users');
        $this->view->params['breadcrumbs'][] = '<i class="fa fa-expeditedssl"></i>&ensp;' . $this->view->title;

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
     * @param null $aid
     *
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionForm()
    {
        $url = null;
        $databases = Yii::$app->user->identity->getAllUserDatabases();
        $post = Yii::$app->request->post('DynamicModel');

        if (
            !empty($post['email'])
            && !empty($post['mobile'])
            && !empty($post['dbid'][0])
            && in_array($post['dbid'][0], $databases)
        ) {


            $dbid = $post['dbid'][0];
            if (!AccessManager::checkIfUserExists($post['email'], $post['mobile'])) {
                $post['dbid'] = $dbid;
                $post['aid'] = Yii::$app->user->identity->aid;
                $post['oid'] = Yii::$app->user->identity->oid;
                $post['firstname'] = $post['name'] ?? null;
                $post['lastname'] = $post['surname'] ?? null;

                $signUpModel = new BusinessSignup();
                $signUpModel->setDataFromPost($post);
                $signUpModel->generateUserAuthKey();
                $signUpModel->save();

                $url = Url::toRoute(['/signup/register/index', 'id' => $signUpModel->auth_key], true);

                EmailTemplate::sendEmailByAction(
                    EmailActionType::BUSINESS_START_REGISTER,
                    [
                        'registerLink' => $url,
                        'businessName' => Yii::$app->user->identity->userId,
                        'firstName' => $post['firstname'],
                        'lastName' =>  $post['lastname']
                    ],
                    Translate::_('business', 'You\'re invited to IdentityBank'),
                    $signUpModel->getDataChunk('email'),
                    Yii::$app->language
                );

                Yii::$app->session->setFlash(
                    'success',
                    Translate::_('business', 'Email with registration url send to the user.')
                );

                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    Translate::_('business', 'User with given data already exists.')
                );
            }
        }

        $model = new DynamicModel(['name', 'surname', 'email', 'mobile', 'dbid']);
        $model->addRule(['email', 'mobile', 'dbid'], 'required');

        if (Yii::$app->request->isPost && array_key_exists('DynamicModel', Yii::$app->request->post())) {
            $post = Yii::$app->request->post('DynamicModel');
            $model['name'] = $post['name'];
            $model['surname'] = $post['surname'];
            $model['email'] = $post['email'];
            $model['mobile'] = $post['mobile'];
        }

        $databases = BusinessDatabase::find()->where(['dbid' => $databases])->orderBy(['name' => SORT_ASC])->all();
        $databaseArray = [];
        foreach ($databases as $database) {
            $databaseArray[$database->dbid] = $database->name;
        }

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'form',
                        'contentParams' => [
                            'model' => $model,
                            'databaseArray' => $databaseArray,
                            'url' => $url
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @return mixed
     */
    public function actionRoles()
    {
        $roles = [];
        $roles_desc = [];

        /*
         * That have to be redone as there is no validation if that user can be accessed!!!
         */

        $post = Yii::$app->request->post();
        $uid = $post['uid'] ?? null;
        $firstname = $post['firstname'] ?? null;
        $lastname = $post['lastname'] ?? null;

        $models = RolesModel::find()->where(['type' => 1])
                            ->andWhere(['NOT LIKE', 'name', 'idb_'])
                            ->all();

        if (empty($uid)) {
            Yii::$app->session->setFlash(
                'error',
                Translate::_('business', 'UID cannot be blank')
            );

            return $this->redirect(['index']);
        }

        foreach ($models as $model) {
            $isAssignment = AuthAssignment::findOne(['item_name' => $model->name, 'user_id' => $uid]);

            if ($isAssignment) {
                $roles[$model->name] = 'on';
            } else {
                $roles[$model->name] = 'off';
            }

            $role = RolesModel::findOne(['name' => $model->name]);
            if ($role == null) {
                continue;
            } else {
                $roles_desc[$model->name] = $role->description;
            }
        }

        $this->view->title = Translate::_('business', 'Add role to user');
        $this->view->params['breadcrumbs'][] = '<i class="fa fa-expeditedssl"></i>&ensp;' . $this->view->title;

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'roles',
                        'contentParams' =>
                            [
                                'roles' => $roles,
                                'uid' => $uid,
                                'firstname' => $firstname,
                                'lastname' => $lastname,
                                'roles_desc' => $roles_desc
                            ]
                    ]
                )
            ]
        );

    }

    /**
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionSave()
    {
        $post = Yii::$app->request->post();

        $roles = [];
        $uid = $post['uid'];

        foreach ($post as $key => $value) {
            if (
                $key === '_csrf'
                || $key === 'uid'
            ) {
                continue;
            }
            if (strpos($key, 'idb_') !== 0) {
                $roles[$key] = $value;
            }
        }

        foreach ($roles as $role => $status) {
            if ($status === 'on') {
                $isAssignment = AuthAssignment::findOne(['item_name' => $role, 'user_id' => $uid]);
                if ($isAssignment) {
                    continue;
                } else {
                    $model = new AuthAssignment();
                    $model->user_id = $uid;
                    $model->item_name = $role;
                    if (Yii::$app->user->can('action_manage_users')) {
                        $model->save();
                    }
                }
            } elseif ($status === 'off') {
                $isAssignment = AuthAssignment::findOne(['item_name' => $role, 'user_id' => $uid]);
                if ($isAssignment) {
                    $isAssignment->delete();
                } else {
                    continue;
                }
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionAssignDatabase()
    {
        if (!Yii::$app->request->isPost) {
            Yii::$app->session->setFlash(
                'error',
                Translate::_('business', 'UID cannot be blank')
            );

            return $this->redirect(['index']);
        }

        $post = Yii::$app->request->post();
        $uid = Yii::$app->request->post('uid') ?? $post['DynamicModel']['uid'] ?? null;
        $firstname = Yii::$app->request->post('firstname') ?? null;
        $lastname = Yii::$app->request->post('lastname') ?? null;

        $databases = Yii::$app->user->identity->getAllUserDatabases();

        if (
            !empty($post['DynamicModel']['dbid'])
            && (in_array($post['DynamicModel']['dbid'], $databases))
        ) {
            $dbid = $post['DynamicModel']['dbid'];
            if (!empty($uid)) {
                $assignment = BusinessDatabaseUser::findOne(['uid' => $uid, 'dbid' => $dbid]);

                if ($assignment instanceof BusinessDatabaseUser) {
                    Yii::$app->session->setFlash(
                        'info',
                        Translate::_('business', 'This user has already been assigned to this vault')
                    );
                } else {
                    $assignment = new BusinessDatabaseUser();
                    $assignment->dbid = $dbid;
                    $assignment->uid = $uid;
                    if ($assignment->save()) {
                        Yii::$app->session->setFlash(
                            'success',
                            Translate::_('business', 'The user has been correctly assigned to the vault')
                        );

                        $userDataModel = BusinessUserData::instantiate();
                        $business = BusinessUserData::findOne(
                            ['uid' => $uid, 'key_hash' => $userDataModel->getKeyHash($uid, 'dbid')]
                        );
                        if (empty($business)) {
                            $business = BusinessUserData::instantiate(
                                ['uid' => $uid, 'key' => 'dbid', 'value' => $dbid]
                            );
                        }
                        $business->value = $dbid;
                        if (!$business->save()) {
                            Yii::error('Cannot save vault id!');
                            Yii::error(json_encode($business->getErrors()));
                        }
                    }
                }
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    Translate::_('business', 'UID cannot be blank')
                );
            }

            return $this->redirect(['index']);
        }

        $databases = BusinessDatabase::find()->where(['dbid' => $databases])->orderBy(['name' => SORT_ASC])->all();
        $array = [];

        foreach ($databases as $database) {
            $array[$database->dbid] = $database->name;
        }

        $model = new DynamicModel(['dbid', 'uid']);

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'assign-database',
                        'contentParams' => [
                            'databases' => $array,
                            'firstname' => $firstname,
                            'lastname' => $lastname,
                            'uid' => $uid,
                            'model' => $model
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
