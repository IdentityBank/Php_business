<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
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

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">

                        <div class="form">
                            <?php $form = ActiveForm::begin(
                                ['action' => Url::toRoute(['signup'], true), 'options' => ['method' => 'post']]
                            ); ?>
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
                                                'label' => Translate::_('business', 'Stop'),
                                                'action' => Yii::$app->session->get('urlRedirect'),
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
                                    Translate::_(
                                        'business',
                                        'Start the registration process for the Identity Bank Personal account'
                                    ),
                                    ['class' => 'btn btn-primary']
                                ) ?>
                            </div>

                            <?php foreach ($model->attributes as $attribute => $value): ?>
                                <?php if (!empty($attribute)): ?>
                                    <?php if (
                                    in_array(
                                        $attribute,
                                        [
                                            'businessUserId',
                                            'businessOrgazniationId',
                                            'businessAccountid',
                                            'businessDatabaseId'
                                        ]
                                    )
                                    ): ?>
                                        <?= $form->field($model, $attribute)->hiddenInput()->label(false) ?>
                                    <?php else: ?>
                                        <?php if (
                                            $attribute === 'dbUserId'
                                            || $attribute === 'mobile'
                                            || $attribute === 'email'
                                        ): ?>
                                            <?= $form->field($model, $attribute)->textInput(['readonly' => true]) ?>
                                        <?php else: ?>
                                            <?= $form->field($model, $attribute)->textInput() ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
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
