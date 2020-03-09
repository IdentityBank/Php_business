<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\widgets\FlashMessage;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="content-wrapper">
    <?= FlashMessage::widget(
        [
            'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success') : null,
            'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : null,
            'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : null,
        ]
    ); ?>

    <section class="content-header">
        <h1>
            <?= Html::encode($this->title) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">

                        <div class="form">
                            <?php $form = ActiveForm::begin(); ?>
                            <div class="form-group">
                                <?= Yii::$app->controller->renderPartial(
                                    '@app/themes/adminlte2/views/site/_modalWindow',
                                    [
                                        'modal' => [
                                            'name' => 'cancelFormActionModal',
                                            'header' => Translate::_('business', 'Stop the registration process'),
                                            'body' => Translate::_(
                                                'business',
                                                'You have chosen to stop the registration process task, your changes will not be saved'
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
                                                'label' => Translate::_('business', 'Cancel'),
                                                'action' => Url::toRoute(['start-multi'], true),
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
                                    Translate::_('business', 'Save'),
                                    ['class' => 'btn btn-primary']
                                ) ?>
                            </div>

                            <?php foreach ($model->attributes as $attribute => $value): ?>
                                <?php if ($attribute === 'dbUserId'): ?>
                                    <?= $form->field($model, $attribute)->hiddenInput(['readonly' => true])->label(
                                        false
                                    ) ?>
                                <?php elseif ($attribute === 'wrongData'): ?>
                                    <?= $form->field($model, $attribute)->hiddenInput(['readonly' => true])->label(
                                        false
                                    ) ?>
                                <?php elseif ($attribute === 'bothValid'): ?>
                                    <?= $form->field($model, $attribute)->hiddenInput(['readonly' => true])->label(
                                        false
                                    ) ?>
                                <?php elseif ($attribute === 'language'): ?>
                                    <?= $form->field($model, $attribute)->hiddenInput(['readonly' => true])->label(
                                        false
                                    ) ?>
                                <?php else: ?>
                                    <?= $form->field($model, $attribute)->textInput() ?>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
