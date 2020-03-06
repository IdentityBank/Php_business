<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\widgets\FlashMessage;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode($this->title) ?>
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

                        <div class="form">
                            <?php $form = ActiveForm::begin(
                                [
                                    'method' => 'post',
                                    'action' => Url::toRoute(['send-emails'], true),
                                ]
                            ); ?>
                            <div class="form-group">
                                <?= Yii::$app->controller->renderPartial(
                                    '@app/themes/adminlte2/views/site/_modalWindow',
                                    [
                                        'modal' => [
                                            'name' => 'cancelFormActionModal',
                                            'header' => Translate::_(
                                                'business',
                                                'Stop the personal account registration process'
                                            ),
                                            'body' => Translate::_(
                                                'business',
                                                'You have chosen to stop the people registration task, your changes will not be saved'
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
                                    Translate::_('business', 'Accept and send invitations'),
                                    ['class' => 'btn btn-primary']
                                ) ?>
                            </div>

                            <?php if (!empty($sendInvitations)) : ?>
                                <div class="box box-success box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?= Translate::_(
                                                'business',
                                                'Invitations will be sent to the following people'
                                            ) ?>
                                        </h3>
                                        <div class="box-tools pull-right">
                                            <span data-toggle="tooltip" class="badge bg-green">
                                                <?= count($sendInvitations) ?>
                                            </span>
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body bg-gray-light">
                                        <?php foreach ($sendInvitations as $model): ?>
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-green">
                                                        <i class="fa fa-check"></i>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-number">
                                                            <i class="fa fa-user"></i>&nbsp;
                                                            <?= $model->name ?>&nbsp;<?= $model->surname ?>
                                                        </span>
                                                        <hr style="margin: 5px;">
                                                        <span class="info-box-text" style="text-transform: none;">
                                                            <i class="fa fa-envelope"></i>&nbsp;
                                                            <?= $model->email ?>
                                                        </span>
                                                        <span class="info-box-text">
                                                            <i class="fa fa-mobile-phone"></i>&nbsp;
                                                            <?= $model->mobile ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($doNotSendInvitations)) : ?>
                                <div class="box box-danger box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?= Translate::_(
                                                'business',
                                                'Invitations will <b>not</b> be sent to these people'
                                            ) ?>
                                        </h3>
                                        <div class="box-tools pull-right">
                                            <span data-toggle="tooltip" class="badge bg-red">
                                                <?= count($doNotSendInvitations) ?>
                                            </span>
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body bg-gray-light">
                                        <?php foreach ($doNotSendInvitations as $model): ?>
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-red">
                                                        <i class="fa fa-check"></i>
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-number">
                                                            <i class="fa fa-user"></i>&nbsp;
                                                            <?= $model->name ?>&nbsp;<?= $model->surname ?>
                                                        </span>
                                                        <hr style="margin: 5px;">
                                                        <span class="info-box-text" style="text-transform: none;">
                                                            <i class="fa fa-envelope"></i>&nbsp;
                                                            <?= $model->email ?>
                                                        </span>
                                                        <span class="info-box-text">
                                                            <i class="fa fa-mobile-phone"></i>&nbsp;
                                                            <?= $model->mobile ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
