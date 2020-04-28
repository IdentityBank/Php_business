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

namespace app\modules\gdpr\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use app\helpers\Translate;
use idbyii2\helpers\IdbAccountId;
use idbyii2\helpers\Metadata;
use idbyii2\models\db\IdbAuditMessage;
use idbyii2\models\form\BusinessRetentionPeriodForm;
use idbyii2\models\idb\IdbBankClientBusiness;
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
class DefaultController extends IdbController
{

    private static $params = [
        'menu_active_section' => '[menu][gdpr]',
        'menu_active_item' => '[menu][gdpr][gdpr]'
    ];

    private $clientModel;
    private $metadata;

    /**
     * @param \yii\base\Action $action
     * @return bool|void
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $user = Yii::$app->user->identity;
            $businessId = IdbAccountId::generateBusinessDbId($user->oid, $user->aid, $dbid ?? $user->dbid);
            $this->clientModel = IdbBankClientBusiness::model($businessId);
            $this->metadata = $metadata = json_decode($this->clientModel->getAccountMetadata()['Metadata'], true);

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $gdpr = Metadata::getGDPRWithProcessors($this->metadata);

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge
                (
                    self::$params,
                    [
                        'content' => 'index',
                        'contentParams' => compact('gdpr')
                    ]
                )
            ]
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionEditPeriod()
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
                }

                Yii::$app->session->setFlash(
                    'success',
                    Translate::_('business', 'Data edited successfully')
                );

                return $this->redirect(['/gdpr']);
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
                            'model' => $model
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionEditBasis()
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

                Yii::$app->session->setFlash(
                    'success',
                    Translate::_('business', 'Data edited successfully')
                );

                return $this->redirect(['/gdpr']);
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
                            'messages' => $messages,
                            'legal' => $legals,
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionEditProcessors()
    {
        if (!empty(Yii::$app->request->isPost)) {
            if (!empty(Yii::$app->request->post('dpo'))) {
                $post = Yii::$app->request->post('dpo');
                foreach ($post as $key => $value) {
                    $post[$key] = strip_tags($value);
                }
                Metadata::addToGdpr(
                    $this->metadata,
                    ['listDataProcessors' => $post]
                );
                $this->clientModel->setAccountMetadata(json_encode($this->metadata));
            }

            Yii::$app->session->setFlash(
                'success',
                Translate::_('business', 'Data edited successfully')
            );

            return $this->redirect(['/gdpr']);
        }

        $gdpr = Metadata::getGDPR($this->metadata);
        $gdpr = $gdpr[array_key_first($gdpr)];
        foreach ($gdpr['listDataProcessors'] as $key => $value) {
            $gdpr['listDataProcessors'][$key] = strip_tags($value);
        }
        $dpos = ArrayHelper::getValue($gdpr, 'listDataProcessors', []);
        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge(
                    self::$params,
                    [
                        'content' => 'dpos',
                        'contentParams' => compact( 'dpos')
                    ]
                )
            ]
        );
    }

}

################################################################################
#                                End of file                                   #
################################################################################
