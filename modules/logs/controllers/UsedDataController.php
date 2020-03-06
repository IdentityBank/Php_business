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

namespace app\modules\logs\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use idbyii2\helpers\IdbAccountId;
use idbyii2\helpers\Translate;
use idbyii2\models\data\IdbAuditLogSearch;
use idbyii2\models\idb\IdbBankClientBusiness;
use Yii;
use yii\helpers\ArrayHelper;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Default controller for the `logs` module
 */
class UsedDataController extends IdbController
{

    private static $params = [
        'menu_active_section' => '[menu][tools]',
        'menu_active_item' => '[menu][tools][logs_used_data]',
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
                    'actions' => ['index'],
                    'roles' => ['action_logs_used_data'],
                ],
            ];
        }

        return $behaviors;
    }

    /**
     * Renders the index view for the module
     *
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        $this->view->title = Translate::_('business', 'Used data logs');
        $this->view->params['breadcrumbs'][] = '<i class="fa fa-sitemap"></i>&ensp;' . $this->view->title;

        $searchModel = new IdbAuditLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $user = Yii::$app->user->identity;
        $businessId = IdbAccountId::generateBusinessDbId($user->oid, $user->aid, $user->dbid);

        $clientModel = IdbBankClientBusiness::model($businessId);
        $businessModel = IdbBankClientBusiness::model($businessId);

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'index',
                        'contentParams' => compact('searchModel', 'dataProvider', 'clientModel', 'businessModel')
                    ]
                )
            ]
        );
    }
}

################################################################################
#                                End of file                                   #
################################################################################
