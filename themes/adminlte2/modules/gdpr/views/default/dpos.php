<?php

use app\assets\FrontAsset;
use app\helpers\Translate;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$frontAssets = FrontAsset::register($this);
$frontAssets->idbUpdateDpo();
?>
<section class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Translate::_('business', 'Edit data processors') ?>
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
                    <div class="form-group" style="text-align:center;">
                        <h2><?= Translate::_('business', 'List Data Processors:') ?></h2>

                        <div id="dpo-container">
                            <?php foreach($dpos as $dpo): ?>
                                <div class="dpo-div">
                                    <textarea style="margin-bottom: 20px" class="form-control" name="dpo[]" rows="3"><?= $dpo ?></textarea>
                                    <span class="glyphicon glyphicon-trash"></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button style="margin-bottom: 20px;" id="add-dpo" class="btn btn-primary"><i class="fa fa-plus-square"></i></button>
                        <div class="clear"></div>
                    </div>
                    <?php ActiveForm::end(); ?>


                </div>
            </div>
        </div>
    </section>

</section>
