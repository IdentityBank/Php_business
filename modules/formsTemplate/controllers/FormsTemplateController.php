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

namespace app\modules\formsTemplate\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use app\helpers\Translate;
use idbyii2\components\PortalApi;
use idbyii2\helpers\IdbAccountId;
use idbyii2\models\db\FormTemplate;
use idbyii2\models\form\FTemplateForm;
use idbyii2\models\idb\IdbBankClientBusiness;
use Yii;
use yii\helpers\ArrayHelper;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 *
 * Default controller for the `FormsTemplate` module
 */
class FormsTemplateController extends IdbController
{

    /**
     * @var array
     */
    private static $params = [
        'menu_active_section' => '[menu][template]',
        'menu_active_item' => '[menu][template][editor]'
    ];

    private $version = '1.0';

    /**
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        $model = new FTemplateForm();
        $usersToSend = $this->getUsersToSend();

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'index',
                        'contentParams' => [
                            'model' => $model,
                            'usersToSend' => $usersToSend
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * save data to Database
     * @throws \Exception
     */
    public function actionSave()
    {
        $dataToJson = [];
        $form = Yii::$app->getRequest()->post()['FTemplateForm']['data'];
        $peopleUser = Yii::$app->getRequest()->post()['peopleUser'];
        $userID = Yii::$app->user->identity->getId();

        for ($i = 0; $i < count($form['fieldName']); $i++) {
            if (($form['fieldName'][$i] == '') && ($form['fieldDesc'][$i] == 0)) {
                continue;
            } else {
                $fieldName = $form['fieldName'][$i];
                $type = $form['type'][$i];
                $fieldDesc = $form['fieldDesc'][$i];

                $arr = [
                    'fieldName' => $fieldName,
                    'type' => $type,
                    'fieldDesc' => $fieldDesc
                ];

                $dataToJson[] = $arr;
            }
        }

        $json = json_encode($dataToJson);

        try {
            $model = new FormTemplate();
            $model->business_user = $userID;
            $model->people_user = $peopleUser;
            $model->version_editor = $this->version;
            $model->data_json = $json;
            $model->save();

            $flash = [
                'subject' => Translate::_('business', 'success'),
                'message' => Translate::_(
                    'business',
                    'Form saved and sent correctly.'
                )
            ];

            Yii::$app->session->setFlash('success', $flash);

        } catch (\Exception $e) {
            $flash = [
                'subject' => Translate::_('business', 'error'),
                'message' => Translate::_(
                    'business',
                    'Something went wrong. Try again..'
                )
            ];

            Yii::$app->session->setFlash('error', $flash);
        }

        $this->redirect(['index']);

    }
    
    /**
     * @return array
     * @throws \Exception
     */
    private function getUsersToSend()
    {
        $isEmpty = false;

        $user = Yii::$app->user->identity;
        $businessId = IdbAccountId::generateBusinessDbUserId($user->oid, $user->aid, $user->dbid, '%');
        $clientModel = IdbBankClientBusiness::model($businessId);
        $relations = $clientModel->getRelatedPeoples($businessId);
        if (empty($relations['QueryData'])) {
            $usersName = [];
            $isEmpty = true;
        } else {
            $portalPeopleApi = PortalApi::getPeopleApi();
            $usersName = $portalPeopleApi->requestPeopleInfo($relations['QueryData']);
        }

        $userToSend = [];

        if ($isEmpty == false) {
            foreach ($usersName as $user) {
                $name = $user['name'] . $user['surname'];
                $userToSend[$user['userId']] = $name;
            }
        }

        return $userToSend;
    }
}
