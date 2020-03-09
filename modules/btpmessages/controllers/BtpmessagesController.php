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

namespace app\modules\btpmessages\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use app\helpers\Translate;
use idbyii2\components\PortalApi;
use idbyii2\helpers\IdbAccountId;
use idbyii2\models\form\Business2PeopleFormModel;
use idbyii2\models\idb\IdbBankClientBusiness;
use Yii;
use yii\helpers\ArrayHelper;

################################################################################
# Class(es)                                                                    #
################################################################################

class BtpmessagesController extends IdbController
{

    private static $params = [
        'menu_active_section' => '[menu][applications]',
        'menu_active_item' => '[menu][applications][b2p_messages]',
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
                    'actions' => ['create', 'send', 'messagesent'],
                    'roles' => ['action_b2p_messages'],
                ],
            ];
        }

        return $behaviors;
    }


    public function actionCreate()
    {
        $businessUser = Yii::$app->user->identity->userId;
        $model = new Business2PeopleFormModel;

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
        $this->view->title = Translate::_('business', 'Write a message');
        $this->view->params['breadcrumbs'][] = '<i class="fa fa-edit"></i>&ensp;' . $this->view->title;

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'create',
                        'contentParams' => [
                            'businessUser' => $businessUser,
                            'model' => $model,
                            'userToSend' => $userToSend
                        ],
                    ]
                ),
            ]
        );
    }

    public function actionSend()
    {
        $portalPeopleApi = PortalApi::getPeopleApi();
        $portalPeopleApi->requestBusiness2PeopleMessageInfo($_POST);

        return $this->redirect(['/btpmessages/messagesent']);
    }

    public function actionMessagesent()
    {
        $this->view->title = Translate::_('business', 'Message sent');
        $this->view->params['breadcrumbs'][] = '<i class="fa fa-check-circle"></i>&ensp;' . $this->view->title;

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'sent'
                    ]
                ),
            ]
        );
    }
}

################################################################################
#                                End of file                                   #
################################################################################
