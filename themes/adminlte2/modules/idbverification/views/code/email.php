<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\assets\AppFormAsset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbAsset;
use app\themes\idb\assets\IdbWizardAsset;
use idbyii2\widgets\FlashMessage;
use idbyii2\widgets\VerificationCodeView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;


?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Email verification')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <?php $form = ActiveForm::begin(
        ['id' => 'signup-form', 'fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField']
    ); ?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body">
                        <div style="clear: both;"></div>
                        <?php if ($model->getErrors()) { ?>
                            <?= Html::tag(
                                'div',
                                $form->errorSummary($model),
                                ['class' => 'alert alert-danger']
                            ) ?>
                        <?php } ?>
                        <?= FlashMessage::widget(
                            [
                                'success' => Yii::$app->session->hasFlash('success')
                                    ? Yii::$app->session->getFlash('success') : null,
                                'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash(
                                    'error'
                                ) : null,
                                'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash(
                                    'info'
                                ) : null,
                            ]
                        ); ?>

                        <div class="form-group">
                            <?= Yii::$app->controller->renderPartial(
                                '@app/themes/adminlte2/views/site/_modalWindow',
                                [
                                    'modal' => [
                                        'name' => 'cancelFormActionModal',
                                        'header' => Translate::_('business', 'Cancel edit your data'),
                                        'body' => Translate::_(
                                            'business',
                                            'You have chosen to cancel the edit your data task, your changes will not be saved'
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
                                            'action' => Yii::$app->session->get('urlRedirect', Url::toRoute(['/idbuser/profile'], true)),
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

                            <button class="btn btn-primary btn-create"><?= Translate::_('business', 'Next') ?></button>
                        </div>

                        <div class="jumbotron" style="background-color: white; text-align:center;">
                            <h2><?= Translate::_(
                                    'business',
                                    'You have been sent a code to the email address you provided.'
                                ) ?></h2>
                            <br>
                            <hr>
                            <br>
                            <h2 align="center"><?= Translate::_(
                                    'business',
                                    'Enter the missing digits from the email code.'
                                ) ?></h2>

                            <?= VerificationCodeView::widget(['code' => [0 => $codeFirst, 2 => $codeThird]]) ?>
                        </div>

                        <?php if ($model->captchaEnabled) {
                            echo Yii::$app->signUpCaptcha->config(
                                [
                                    'inputName' => 'SignUpAuthForm[verificationCode]',
                                    'inputId' => 'signupauthform-verificationcode'
                                ]
                            )->run();
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php ActiveForm::end(); ?>
</div>