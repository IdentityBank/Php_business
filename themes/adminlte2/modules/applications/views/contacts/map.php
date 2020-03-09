<?php

use app\helpers\Translate;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode($this->title) ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Yii::$app->user->identity->dbidInfo['name'] ?></h3>
                    </div>
                    <div class="box-body">
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="form-group">
                            <?= Html::submitButton(Translate::_('business', 'Save'), ['class' => 'btn btn-primary']) ?>
                        </div>

                        <?= FlashMessage::widget(
                            [
                                'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash(
                                    'success'
                                ) : null,
                                'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error')
                                    : null,
                                'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info')
                                    : null,
                            ]
                        ); ?>

                        <div id="dataTypes">
                            <div>
                                <?= $form->field($model, 'email_no')
                                         ->dropDownList($metadata)->label(
                                        Translate::_('business', "Select the email safe")
                                    ); ?>
                                <?= $form->field($model, 'mobile_no')
                                         ->dropDownList($metadata)->label(
                                        Translate::_('business', "Select the mobile phone number safe")
                                    ); ?>
                                <?= $form->field($model, 'name_no')
                                         ->dropDownList($metadata)->label(
                                        Translate::_('business', "Select the first name safe")
                                    ); ?>
                                <?= $form->field($model, 'surname_no')
                                         ->dropDownList($metadata)->label(
                                        Translate::_('business', "Select the surname safe")
                                    ); ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
