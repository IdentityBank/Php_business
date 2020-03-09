<?php

use app\assets\DataTableAsset;
use app\assets\IdbDataAsset as aIdbDataAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\widgets\FlashMessage;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$dataTableAsset = DataTableAsset::register($this);
$dataAssets = aIdbDataAsset::register($this);
$dataAssets->showAllAssets();

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Complete the contact data')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <?= FlashMessage::widget(
        [
            'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success') : null,
            'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : null,
            'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : null,
        ]
    ); ?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Translate::_('business', 'Connect with people') ?></h3>
                    </div>
                    <div class="box-body">
                        <?= Html::beginForm(UrL::toRoute(['contacts/send-invitation'], true), 'post'); ?>
                        <div class="form-group">
                            <?= Yii::$app->controller->renderPartial(
                                '@app/themes/adminlte2/views/site/_modalWindow',
                                [
                                    'modal' => [
                                        'name' => 'cancelFormActionModal',
                                        'header' => Translate::_('business', 'Stop sending invitations to people'),
                                        'body' => Translate::_(
                                            'business',
                                            'You have chosen to stop the send invitations to people task, your changes will not be saved'
                                        ),
                                        'question' => Translate::_(
                                            'business',
                                            'If this is not your intention, please click on \'Continue\'.'
                                        ),
                                        'button' => [
                                            'label' => Translate::_(
                                                'business',
                                                'Cancel'
                                            ),
                                            'class' => 'btn btn-back'
                                        ],
                                        'leftButton' => [
                                            'label' => Translate::_('business', 'Stop'),
                                            'action' => Url::toRoute(['access'], true),
                                            'style' => 'btn btn-back',
                                        ],
                                        'rightButton' => [
                                            'label' => Translate::_('business', 'Continue'),
                                            'style' => 'btn btn-primary',
                                            'action' => 'data-dismiss'
                                        ],
                                    ]
                                ]
                            ); ?>
                            <?= Html::submitButton(
                                Translate::_('business', 'Continue'),
                                ['class' => 'btn btn-primary',]
                            );
                            ?>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="info-box bg-gray-light">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-info"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-number"><?= Translate::_(
                                            'business',
                                            'One or more people have incomplete contact data. Here you have the opportunity to complete this data. People with incomplete contact data will not receive an invitation. Once done then click on continue.'
                                        ); ?></span>

                                    <hr style="margin: 5px;">
                                    <span class="progress-description">
                                        <?= Translate::_(
                                            'business',
                                            'Ensure that the email addresses and mobile phone numbers are correct. The phone numbers must start with a ‘+’ followed by the country code and phone number.'
                                        ); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php Pjax::begin(); ?>
                        <?= GridView::widget(
                            [
                                'dataProvider' => $dataProvider,
                                'showHeader' => true,
                                'columns' => [
                                    'name:ntext',
                                    'surname:ntext',
                                    'email:ntext',
                                    'mobile:ntext',
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{edit} {skip}',
                                        'contentOptions' => ['style' => 'width: 100px;'],
                                        'buttons' => [
                                            'edit' => function ($url, $model, $key) {
                                                if ($model->wrongData) {
                                                    return Html::a(
                                                        '<b class="fa fa-edit"></b>',
                                                        ['edit', 'id' => $model->dbUserId]
                                                    );
                                                } else {
                                                    return '<b class="fa fa-check" style="color: green"></b>';
                                                }
                                            },

                                            'skip' => function ($url, $model, $key) {
                                                if (
                                                    is_array(Yii::$app->session->get('peopleAccessToSkip'))
                                                    && in_array(
                                                        $model->dbUserId,
                                                        Yii::$app->session->get('peopleAccessToSkip')
                                                    )
                                                ) {
                                                    return '<b class="fa fa-ban" style="color: darkslategrey"></b>';
                                                } else {
                                                    return Html::a(
                                                        '<b class="glyphicon glyphicon-trash" style="color: red"></b>',
                                                        ['skip', 'id' => $model->dbUserId]
                                                    );
                                                }
                                            },
                                        ]
                                    ]
                                ],
                            ]
                        ); ?>
                        <?php Pjax::end(); ?>
                        <?= Html::endForm(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
