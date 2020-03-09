<?php

use app\assets\FrontAsset;
use app\helpers\Translate;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$frontAssets = FrontAsset::register($this);
$frontAssets->idbUpdateMessage();
?>
<section class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Translate::_('business', 'Edit law basis') ?>
        </h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="col-md-12">

                    <?php $form = ActiveForm::begin(); ?>
                    <div class="form-group">
                        <?= Yii::$app->controller->renderPartial(
                            '@app/themes/adminlte2/views/site/_modalWindow',
                            [
                                'modal' => [
                                    'name' => 'cancelFormActionModal',
                                    'header' => Translate::_('business', 'Stop create user'),
                                    'body' => Translate::_(
                                        'business',
                                        'You have chosen to stop the create user task, your changes will not be saved'
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
                                        'style' => 'btn btn-back',
                                        'action' => Url::toRoute(['/gdpr'])
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
                            ['class' => 'btn btn-primary', 'id' => 'save']
                        ) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'legal')
                            ->dropDownList($legal)->label(
                                Translate::_('business', "Lawful Basis")
                            ); ?>
                        <?= $form->field($model, 'messages')
                            ->dropDownList($messages)->label(
                                Translate::_('business', "Select your defined message")
                            ); ?>
                        <?= $form->field($model, 'message')->textarea(['rows' => 3])
                            ->label(Translate::_('business', "Message content"))
                            ->hint(Translate::_('business', "At this field you can provide your custom message content."),
                                ['tag' => 'div', 'class' => 'alert alert-info']); ?>

                        <h2><?= Translate::_('business', 'Purpose Limitation:') ?></h2>
                        <?= $form->field($model, 'purposeLimitation')->textarea(['rows' => 3])
                            ->label(Translate::_('business', "Provide: Specified, explicit and legitimate purposes")) ?>
                    </div>
                    <?php ActiveForm::end(); ?>


                </div>
            </div>
        </div>
    </section>

</section>
