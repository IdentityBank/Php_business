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

namespace app\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\helpers\Translate;
use idbyii2\audit\AuditComponent;
use idbyii2\models\db\BusinessNotification;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

################################################################################
# Class(es)                                                                    #
################################################################################

class IdbController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '@app/views/site/error.php'
            ],
        ];
    }

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
                    'class' => VerbFilter::className(),
                    'actions' => [],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['login', 'idb-login', 'mfa', 'idb-api'],
                            'allow' => true,
                        ],
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                    'denyCallback' => function ($rule, $action) {
                        Yii::$app->user->setReturnUrl(Yii::$app->request->url);
                        $this->redirect(Yii::$app->user->loginUrl)->send();
                    }
                ],
            ]
        );

        return $behaviors;
    }

    /**
     * @param $action
     *
     * @return bool|void
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        /**
         * Allow external IDB requests
         */
        AuditComponent::actionAudit($action);
        if (
            $action->id === 'idb-login'
            || $action->id === 'idb-api'
        ) {
            $this->enableCsrfValidation = false;
        }

        $return = parent::beforeAction($action);

        if ($return) {

            if (Yii::$app->user->isGuest) {
                if (
                    (Yii::$app->request->url !== Url::toRoute(Yii::$app->user->loginUrl))
                    && ($action->id !== 'idb-login')
                    && ($action->id !== 'idb-api')
                ) {

                    Yii::$app->user->setReturnUrl(Yii::$app->request->url);

                    return $this->redirect(Yii::$app->user->loginUrl)->send();
                }
            } else {
                $return = Yii::$app->user->identity->validateMfa();
                if ($return) {
                    // Validate mandatory action for portal user
                    if (
                        ($this->checkMandatoryActions())
                        && (!in_array($action->id, ['mandatory-actions', 'logout']))
                    ) {
                        $this->redirect(['/mandatory-actions']);

                        return false;
                    }

                    // Setup portal notification
                    $this->getNotifications();
                } else {
                    if ($action->id !== 'mfa') {
                        $this->redirect(['/mfa']);
                    }

                    return true;
                }
            }
        }

        return $return;
    }

    private function getNotifications()
    {
        if (!empty(Yii::$app->user->id)) {
            $notifications = BusinessNotification::getNotificationsForUser(Yii::$app->user->id);
            foreach ($notifications as $notification) {
                Yii::$app->view->params['notifications'][$notification->type][] = $notification;
            }
        }
    }

    /**
     * @param $page
     * @return string
     */
    public function getPageTitle($page)
    {
        return Translate::_('business', 'Identity Bank') . " :: $page";
    }

    /**
     * @return bool
     */
    protected function checkMandatoryActions()
    {
        $user = Yii::$app->user->identity;
        $databaseUser = (empty($user)
                || empty($user->oid)
                || empty($user->aid)
                || empty($user->dbid));
        Yii::$app->view->params['status'] = $databaseUser;
        Yii::$app->view->params['userDb'] = $databaseUser;
        return $databaseUser;
    }
}

################################################################################
#                                End of file                                   #
################################################################################
