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

namespace app\modules\signup\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\helpers\BusinessConfig;
use idbyii2\helpers\AccessManager;
use idbyii2\models\db\AuthAssignment;
use idbyii2\models\db\BusinessAccountUser;
use idbyii2\models\db\BusinessAuthlog;
use idbyii2\models\db\BusinessDatabaseUser;
use idbyii2\models\db\BusinessSignup;
use idbyii2\models\form\IdbBusinessUserSignUpForm;
use idbyii2\models\identity\IdbBusinessUser;
use Yii;
use yii\web\BadRequestHttpException;

################################################################################
# Class(es)                                                                    #
################################################################################

class RegisterController extends WizardController
{

    /**
     * @param $action
     *
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            BusinessAuthlog::logout(Yii::$app->user->id);
            Yii::$app->user->logout();
        }
        switch ($action->id) {
            case 'form':
                return $this->updateSignupModel($action, Yii::$app->request->get('id'));
                break;
        }

        return parent::beforeAction($action);
    }

    /**
     * @return mixed
     */
    public function actionIndex($id)
    {
        return $this->actionWelcome($id);
    }

    /**
     * @return mixed
     */
    public function actionWelcome($id = null)
    {
        if (empty($id)) {
            $this->redirect(['/signup']);
        }

        return $this->render('/wizard/welcome', ['id' => $id]);
    }

    /**
     * @return string
     */
    public function actionBeforeWeStart($id = null)
    {
        if (empty($id)) {
            $this->redirect(['/signup']);
        }

        return $this->render('/wizard/beforeWeStart', ['id' => $id]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function actionForm($id)
    {
        $model = new IdbBusinessUserSignUpForm();
        $model->firstname = $this->signUpModel->getDataChunk('firstname');
        $model->lastname = $this->signUpModel->getDataChunk('lastname');
        $model->userId = $this->signUpModel->getDataChunk('userId');
        $model->mobile = $this->signUpModel->getDataChunk('mobile');
        $model->mobile = preg_replace('/\s+/', '', $model->mobile);
        $model->email = $this->signUpModel->getDataChunk('email');
        $model->email = preg_replace('/\s+/', '', $model->email);
        $model->captchaEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaEnabled();

        if (!empty(Yii::$app->request->post()['IdbBusinessUserSignUpForm'])) {
            $model->userId = Yii::$app->request->post()['IdbBusinessUserSignUpForm']['userId'];
            $model->password = Yii::$app->request->post()['IdbBusinessUserSignUpForm']['password'];
            $model->repeatPassword = Yii::$app->request->post()['IdbBusinessUserSignUpForm']['repeatPassword'];
            $model->captchaEnabled = BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaEnabled();
            if ($model->validate()) {
                $this->signUpModel->setDataChunk('signup-register', true);
                $this->signUpModel->setDataChunk('userId', $model->userId);
                $this->signUpModel->setDataChunk('name', $model->userId);
                $this->signUpModel->setDataChunk('firstname', $model->firstname);
                $this->signUpModel->setDataChunk('lastname', $model->lastname);
                $this->signUpModel->setDataChunk('password', $model->password);
                $createIdbUserStatus = IdbBusinessUser::createFromSignUpModel($this->signUpModel);
                $this->signUpModel->setDataChunk('uid', $createIdbUserStatus['uid']);
                $this->signUpModel->setDataChunk('password', $model->password);
                $this->signUpModel->save();

                $this->appendSignUpTables($this->signUpModel);

                $this->signUpModel->setDataChunk('currentState', 'email-verification');
                $this->signUpModel->save();

                return $this->redirect(['email-verification', 'id' => $id]);
            }
        }

        return $this->render('form', ['model' => $model]);
    }

    /**
     * @param \idbyii2\models\db\BusinessSignup $signUpModel
     *
     * @throws \yii\web\BadRequestHttpException
     */
    private function appendSignUpTables(BusinessSignup $signUpModel)
    {
        $uid = $signUpModel->getDataChunk('uid');
        $oid = $signUpModel->getDataChunk('oid');
        $aid = $signUpModel->getDataChunk('aid');
        $dbid = $signUpModel->getDataChunk('dbid');

        if (
            !empty($uid)
            && !empty($oid)
            && !empty($aid)
            && !empty($dbid)
        ) {
            $authAssignment = new AuthAssignment();
            $authAssignment->item_name = "organization_user";
            $authAssignment->user_id = $uid;
            $authAssignment->save();

            BusinessDatabaseUser::create(['uid' => $uid, 'dbid' => $dbid]);
            BusinessAccountUser::create(['uid' => $uid, 'aid' => $aid]);
            AccessManager::changeDatabase($uid, $oid, $aid, $dbid);
        } else {
            Yii::error(
                "ERROR!!! - We cannot assign user to organization database. We have to break registration process. Attributes: [{attributes}]",
                ['attributes' => $signUpModel->getData()]
            );
            throw new BadRequestHttpException(Translate::_('business', 'Your registration was cancelled!'));
        }
    }
}

################################################################################
#                                End of file                                   #
################################################################################
