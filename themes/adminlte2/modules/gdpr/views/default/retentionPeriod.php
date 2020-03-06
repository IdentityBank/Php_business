<?php

use app\assets\FrontAsset;
use app\helpers\Translate;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$frontAssets = FrontAsset::register($this);
$frontAssets->updateRetentionPeriod();
?>
<section class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Translate::_('business', 'Edit retention period') ?>
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
                        <h2><?= Translate::_('business', 'Storage time limitations') ?></h2>

                        <?= $form->field($model, 'maximum')->input('number')
                            ->label(Translate::_('business', "Maximum in Days")) ?>

                        <div class="after-maximum <?= empty($model->maximum)?'idb-hidden': ''?>">
                            <?= $form->field($model, 'minimum')->input('number')
                                ->label(Translate::_('business', "Minimum in Days")) ?>

                            <?= $form->field($model, 'onExpiry')
                                ->dropDownList([
                                    'destruction' => 'Destruction',
                                    'pseudonymization' => 'Pseudonymization'
                                ])->label(
                                    Translate::_('business', "On Expiry")
                                ); ?>

                            <?= $form->field($model, 'reviewCycle')->input('number')
                                ->label(Translate::_('business', "Review cycle in Days"))
                                ->hint(Translate::_('business', "If you think you would like to keep data for longer provide review cycle value and we will send reminders."),
                                    ['tag' => 'div', 'class' => 'alert alert-info']) ?>
                        </div>
                        <div class="after-review <?= empty($model->reviewCycle)?'idb-hidden': ''?>">
                            <?= $form->field($model, 'explanation')->textarea(['rows' => 3])
                                ->label(Translate::_('business', "Explanation")) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>


                </div>
            </div>
        </div>
    </section>

</section>
