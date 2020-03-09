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

namespace app\modules\notifications\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use app\helpers\Translate;
use idbyii2\enums\NotificationType;
use idbyii2\models\db\BusinessNotification;
use idbyii2\models\db\BusinessNotificationSearch;
use idbyii2\models\form\NotificationsForm;
use Yii;
use yii\web\NotFoundHttpException;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * NotificationsController implements the CRUD actions for BusinessNotification model.
 */
class NotificationsController extends IdbController
{

    public $defaultAction = 'index';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = array_merge_recursive(
            $behaviors,
            [
                'verbs' => [
                    'actions' => [
                        'delete' => ['post', 'read'],
                    ],
                ],
            ]
        );
        if (is_array($behaviors['access']['rules'])) {
            $behaviors['access']['rules'] = [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['notifications'],
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => ['notifications'],
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => ['notifications_update'],
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['notifications_delete'],
                ],
                [
                    'allow' => true,
                    'actions' => ['view'],
                    'roles' => ['notifications'],
                ]
                ,
                [
                    'allow' => true,
                    'actions' => ['read'],
                    'roles' => ['@'],
                ]
            ];
        }

        return $behaviors;
    }

    /**
     * Deletes an existing BusinessNotification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param $id
     *
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BusinessNotification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return BusinessNotification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusinessNotification::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRead()
    {
        if (
            !empty(Yii::$app->user->id)
            && !empty(Yii::$app->request->post()['id'])
        ) {
            $id = Yii::$app->request->post()['id'];
            $model_notification = $this->findModel($id);
            if (
                $model_notification
                && $model_notification->uid === Yii::$app->user->id
                && $model_notification->type === NotificationType::GREEN
            ) {
                $model_notification->status = intval(0);
                $model_notification->update();
            }
        }
    }
}

################################################################################
#                                End of file                                   #
################################################################################
