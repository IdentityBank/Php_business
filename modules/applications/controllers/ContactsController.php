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

namespace app\modules\applications\controllers;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\controllers\IdbController;
use app\helpers\ConfigHelper;
use app\helpers\Translate;
use Exception;
use idbyii2\helpers\DataHTML;
use idbyii2\helpers\IdbAccountId;
use idbyii2\helpers\Metadata;
use idbyii2\helpers\PeopleAccessHelper;
use idbyii2\models\data\IdbDataProvider;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\models\db\IdbAuditMessage;
use idbyii2\models\idb\IdbBankClientBusiness;
use Yii;
use yii\base\DynamicModel;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\log\Logger;

################################################################################
# Class(es)                                                                    #
################################################################################

class ContactsController extends IdbController
{

    private static $params =
        [
            'menu_active_section' => '[menu][applications]',
            'menu_active_item' => '[menu][applications][contacts]',
        ];
    private $businessId;
    private $dbName;
    private $clientModel;
    private $accountName;
    private $metadata;
    /** @var IdbDataProvider */
    private $dataProvider;

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
                    'roles' => ['action_give_people_access'],
                ],
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
        $this->dbName = BusinessDatabase::findOne($user->dbid);
        if ($this->dbName) {
            $this->dbName = $this->dbName->name;
        }
        $this->clientModel = IdbBankClientBusiness::model($this->businessId);
        $this->accountName = ((empty(Yii::$app->user->identity->accountName)) ? ''
            : Yii::$app->user->identity->accountName);
        $this->metadata = json_decode($this->clientModel->getAccountMetadata()['Metadata'], true);
        $this->dataProvider = new IdbDataProvider($this->businessId);
        $this->dataProvider->init(['isPeopleAccessMap' => true]);

        return $return;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionAccess()
    {
        Yii::$app->session->remove('peopleSelectedToRegister');
        Yii::$app->session->remove('peopleAccessToSkip');
        $tmpMetadata = [];
        try {
            if (!Metadata::hasPeopleAccessMap($this->metadata)) {
                return $this->redirect(["check-mapping"]);
            }
            foreach ($this->metadata['data'] as $key => $value) {
                if (
                    $value['uuid'] == $this->metadata['PeopleAccessMap']['email_no']
                    || $value['uuid'] == $this->metadata['PeopleAccessMap']['mobile_no']
                    || $value['uuid'] == $this->metadata['PeopleAccessMap']['name_no']
                    || $value['uuid'] == $this->metadata['PeopleAccessMap']['surname_no']
                ) {
                    $tmpMetadata[$key] = $value['display_name'];
                }
            }

            $request = Yii::$app->request;

            $perPage = 25;
            if (!empty($request->get('per-page'))) {
                $perPage = $request->get('per-page');
            }

            if (!empty($request->get('sort-by')) && !empty($request->get('sort-dir'))) {
                Yii::$app->session->set('people-access-sort-by', $request->get('sort-by'));
                Yii::$app->session->set('people-access-sort-dir', $request->get('sort-dir'));
                $this->dataProvider->sort = [
                    DataHTML::getUuid(
                        explode('-', $request->get('sort-by')),
                        $this->dataProvider->metadata
                    ) => $request->get('sort-dir')
                ];
            }

            $this->dataProvider->setPagination(
                [
                    'pageSize' => $perPage,
                    'page' => $request->get('page') - 1
                ]
            );

            $data = [
                'metadata' => $this->dataProvider->metadata,
                'dataProvider' => $this->dataProvider,
                'messages' => IdbAuditMessage::find()->where(['portal_uuid' => Yii::$app->user->identity->id])->orderBy(
                    'order'
                )->all(),
                'legal' => IdbAuditMessage::find()->where(['portal_uuid' => 'default'])->all(),
                'search' => null
            ];

            if (!empty($request->post('search'))) {
                Yii::$app->session->set('people-access-search', $request->post('search'));
                $this->dataProvider->prepareSearch(json_decode($request->post('search'), true));
                $data['search'] = json_encode($request->post('search'));
            } elseif (Yii::$app->session->get('people-access-search')) {
                $this->dataProvider->prepareSearch(json_decode(Yii::$app->session->get('people-access-search'), true));
                $data['search'] = json_encode(Yii::$app->session->get('people-access-search'));
            }

            Yii::$app->session['dataReturnURL'] = Yii::$app->request->Url;

            if (Yii::$app->request->isPost && array_key_exists('selection', Yii::$app->request->post())) {
                Yii::$app->session->set('peopleSelectedToRegister', Yii::$app->request->post('selection'));

                return $this->redirect(['start-multi']);
            }

            $this->view->title = Translate::_('business', 'Access to data via people portal');
            $this->view->params['breadcrumbs'][] = '<i class="fa fa-users"></i>&ensp;' . $this->view->title;

            $dbName = BusinessDatabase::findOne(
                ['aid' => Yii::$app->user->identity->aid, 'dbid' => Yii::$app->user->identity->dbid]
            );

            return $this->render(
                '@app/themes/adminlte2/views/site/template',
                [
                    'params' => ArrayHelper::merge(
                        self::$params,
                        [
                            'content' => 'access',
                            'contentParams' => [
                                'accountName' => $this->accountName,
                                'businessId' => $this->businessId,
                                'dbName' => $this->dbName,
                                'metadata' => $this->dataProvider->metadata,
                                'dataProvider' => $this->dataProvider,
                                'dbName' => $dbName->name ?? '',
                                'messages' => IdbAuditMessage::find()->where(
                                    ['portal_uuid' => Yii::$app->user->identity->id]
                                )->orderBy('order')->all(),
                                'legal' => IdbAuditMessage::find()->where(['portal_uuid' => 'default'])->all(),
                                'search' => null
                            ]
                        ]
                    )
                ]
            );
        } catch (Exception $e) {
            $logger = new Logger();
            $message = $e->getFile() . ': ' . $e->getLine() . ": " . $e->getMessage() . "\n";
            $message .= "Stacktrace: \n" . $e->getTraceAsString();
            $logger->log($message, Logger::LEVEL_ERROR);
            var_dump($message);
            die();
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionResetSearch()
    {
        if (Yii::$app->session->get('people-access-search')) {
            Yii::$app->session->remove('people-access-search');
        }

        if (Yii::$app->session->get('people-access-sort-by')) {
            Yii::$app->session->remove('people-access-sort-by');
        }

        if (Yii::$app->session->get('people-access-sort-dir')) {
            Yii::$app->session->remove('people-access-sort-dir');
        }

        return $this->redirect(['access']);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionStartMulti()
    {
        if (empty(Yii::$app->session->get('peopleSelectedToRegister'))) {
            Yii::$app->session->setFlash(
                'error',
                Translate::_('business', 'Select at least one person to send an invitation to.')
            );

            return $this->redirect(Yii::$app->request->referrer);
        } else {
            $post = Yii::$app->session->get('peopleSelectedToRegister');
        }

        $peopleData = [];
        $showIncorrect = true;

        foreach ($post as $key => $id) {
            try {
                $return = PeopleAccessHelper::prepareDataForPeopleAccess($this->clientModel, $this->metadata, $id);
            } catch (Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());

                return $this->redirect(Yii::$app->request->referrer ?? Yii::$app->homeUrl);
            }

            if (!empty($return['error'])) {
                Yii::$app->session->setFlash('error', $return['error']);

                return $this->redirect(Yii::$app->request->referrer ?? Yii::$app->homeUrl);
            }

            $model = $return['model'];

            if (!$model->validate()) {
                $model->wrongData = true;
                $showIncorrect = false;
            }

            array_push($peopleData, $model);
        }

        if ($showIncorrect) {
            return $this->redirect(['/applications/contacts/send-invitation']);
        }

        $dataProvider = new ArrayDataProvider(
            [
                'allModels' => $peopleData,
                'pagination' => [
                    'pageSize' => 25,
                ],
                'sort' => [
                    'attributes' => ['surname', 'name'],
                ],
            ]
        );

        $this->view->title = Translate::_('business', 'People with an invalid email address or mobile phone number');
        $this->view->params['breadcrumbs'][] = '<i class="fa fa-users"></i>&ensp;' . $this->view->title;

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge(
                    self::$params,
                    [
                        'content' => 'incorrect',
                        'contentParams' => [
                            'dataProvider' => $dataProvider,
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @param $id
     *
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        if (is_null($id)) {
            Yii::$app->session->setFlash('error', Translate::_('business', 'Select a person to edit data.'));

            return $this->redirect(Yii::$app->request->referrer);
        }
        try {
            $return = PeopleAccessHelper::prepareDataForPeopleAccess($this->clientModel, $this->metadata, $id);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());

            return $this->redirect(Yii::$app->request->referrer ?? Yii::$app->homeUrl);
        }

        $model = $return['model'];
        $columns = $return['columns'];

        $post = Yii::$app->request->post();

        if (array_key_exists('PeopleAccessHelper', $post)) {
            $model->name = $post['PeopleAccessHelper']['name'];
            $model->surname = $post['PeopleAccessHelper']['surname'];
            $model->email = $post['PeopleAccessHelper']['email'];
            $model->mobile = $post['PeopleAccessHelper']['mobile'];

            if ($model->validate()) {
                $response = $this->clientModel->update(
                    intval($id),
                    [
                        $columns['name'] => $model->name,
                        $columns['surname'] => $model->surname,
                        $columns['email'] => $model->email,
                        $columns['mobile'] => $model->mobile
                    ]
                );

                if (array_key_exists('Query', $response) && $response['Query'] == 1) {
                    Yii::$app->session->setFlash(
                        'success',
                        Translate::_('business', 'People data was successfully changed.')
                    );
                    $model->wrongData = false;

                    return $this->redirect(['/applications/contacts/start-multi']);
                } else {
                    Yii::$app->session->setFlash(
                        'error',
                        Translate::_('business', 'An error occurred while saving people data.')
                    );
                }
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    Translate::_('business', 'An error occurred when validating people data.')
                );
            }
        }

        $this->view->title = Translate::_('business', 'Edit people data');
        $this->view->params['breadcrumbs'][] = '<i class="fa fa-users"></i>&ensp;' . $this->view->title;

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge(
                    self::$params,
                    [
                        'content' => 'edit',
                        'contentParams' => [
                            'model' => $model,
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionSendInvitation()
    {
        $post = Yii::$app->session->get('peopleSelectedToRegister');
        $toSkip = Yii::$app->session->get('peopleAccessToSkip');

        if (is_null($toSkip)) {
            $toSkip = [];
        }

        $sendInvitations = [];
        $doNotSendInvitations = [];
        $emails = [];

        foreach ($post as $key => $id) {
            try {
                $return = PeopleAccessHelper::prepareDataForPeopleAccess($this->clientModel, $this->metadata, $id);
            } catch (Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());

                return $this->redirect(Yii::$app->request->referrer ?? Yii::$app->homeUrl);
            }

            if (!empty($return['error'])) {
                Yii::$app->session->setFlash('error', $return['error']);

                return $this->redirect(Yii::$app->request->referrer ?? Yii::$app->homeUrl);
            }

            $model = $return['model'];

            if (!$model->validate() || in_array($id, $toSkip)) {
                array_push($doNotSendInvitations, $model);
                array_push($toSkip, $id);
                unset($post[$key]);
            } else {
                $emails[] = [
                    'dbId' => $model->dbUserId,
                    'email' => $model->email,
                    'mobile' => $model->mobile,
                    'name' => $model->name,
                    'surname' => $model->surname,
                    'language' => Yii::$app->language,
                ];
                array_push($sendInvitations, $model);
            }
        }

        Yii::$app->session->set('peopleSelectedToRegister', $post);
        Yii::$app->session->set('peopleSelectedToRegisterData', $emails);
        Yii::$app->session->set('peopleAccessToSkip', $toSkip);

        $this->view->title = Translate::_('business', 'Summary');
        $this->view->params['breadcrumbs'][] = '<i class="fa fa-users"></i>&ensp;' . $this->view->title;

        return $this->render(
            '@app/themes/adminlte2/views/site/template',
            [
                'params' => ArrayHelper::merge(
                    self::$params,
                    [
                        'content' => 'summary',
                        'contentParams' => [
                            'sendInvitations' => $sendInvitations,
                            'doNotSendInvitations' => $doNotSendInvitations,
                        ]
                    ]
                )
            ]
        );
    }

    /**
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionSendEmails()
    {
        $post = Yii::$app->session->get('peopleSelectedToRegisterData');
        $user = Yii::$app->user->identity;
        $businessId = IdbAccountId::generateBusinessDbId(
            $user->oid,
            $user->aid,
            $user->dbid
        );

        $data = [];
        try {
            foreach ($post as $people) {
                $people['language'] = Yii::$app->language;
                $data[] = $people;
            }
            $data = [
                'businessId' => $businessId,
                'data' => json_encode($data),
                'uid' => Yii::$app->user->identity->id
            ];

            PeopleAccessHelper::executeSendInvitations($data);
        } catch (Exception $e) {
            Yii::$app->session->setFlash(
                'error',
                Translate::_('business', 'Cannot process your data. Please contact your system administrator.')
            );

            return $this->redirect(['access']);
        }

        Yii::$app->session->remove('peopleSelectedToRegisterData');
        Yii::$app->session->remove('peopleSelectedToRegister');
        Yii::$app->session->remove('peopleAccessToSkip');

        $flash = [
            'subject' => Translate::_('business', 'That’s all!'),
            'message' => Translate::_(
                'business',
                'The invitations are now being created and sent out. We will notify you when all this is done.'
            )
        ];

        Yii::$app->session->setFlash('success', $flash);

        return $this->redirect(['access']);
    }
}

################################################################################
#                                End of file                                   #
################################################################################
