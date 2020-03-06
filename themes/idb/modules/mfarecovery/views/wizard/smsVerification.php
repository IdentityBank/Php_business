<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\assets\AppFormAsset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbAsset;
use app\themes\idb\assets\IdbWizardAsset;
use idbyii2\widgets\FlashMessage;
use idbyii2\widgets\VerificationCodeView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$formAsset = AppFormAsset::register($this);
$this->title = Translate::_('business', 'Check your phone');
$idbAsset = IdbAsset::register($this);
$wizardAsset = IdbWizardAsset::register($this);

?>

<div class="container">
    <div class="container-inner">
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizard(
                    [
                        'Icon' => 'glyphicon-check',
                        'Title' => Translate::_('business', 'Email verification')
                    ],
                    [
                        'Icon' => 'glyphicon-check',
                        'Title' => Translate::_('business', 'Email verification')
                    ],
                    [
                        'Icon' => 'glyphicon-check',
                        'Title' => Translate::_('business', 'SMS verification')
                    ],
                    3
                ) ?>
            </div>
        </div>
        <br>
        <?php if ($tryCount > 0): ?>
            <?php $form = ActiveForm::begin(
                ['id' => 'signup-form', 'fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField']
            ); ?>
            <div class="row">
                <div class="col-lg-10" style="float: none;margin: 0 auto;">
                    <div class="sp-column">
                        <div class="sp-module">
                            <div class="sp-module-content">
                                <div>
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
                                            'error' => Yii::$app->session->hasFlash('error')
                                                ? Yii::$app->session->getFlash('error') : null,
                                            'info' => Yii::$app->session->hasFlash('info')
                                                ? Yii::$app->session->getFlash('info') : null,
                                            'message' => Translate::_('business', 'Try left') . ': ' . $tryCount
                                        ]
                                    ); ?>

                                    <div class="jumbotron" style="background-color: white;">
                                        <h2><?= Translate::_(
                                                'business',
                                                'You have been sent an SMS code to the mobile phone number you provided.'
                                            ) ?>

                                            <br>
                                            <hr>
                                            <br>
                                            <h2 align="center"><?= Translate::_(
                                                    'business',
                                                    'Enter the missing digits from the SMS code'
                                                ) ?></h2>

                                            <?= VerificationCodeView::widget(
                                                ['code' => [0 => $codeFirst, 2 => $codeThird]]
                                            ) ?>
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
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12" style="float: none;margin: 0 auto;">
                    <?= $wizardAsset->generateWizardActions(
                        [
                            'Text' => Translate::_('business', 'Continue MFA recovery'),
                            'Action' => 'Submit',
                            'Help' => Translate::_('business', 'Continue')
                        ],
                        [
                            'Text' => Translate::_('business', 'Cancel MFA recovery'),
                            'Action' => ['/signup'],
                            'Help' => Translate::_('business', 'Cancel')
                        ]
                    ) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-12" style="float: none;margin: 0 auto;">
                    <div class="sp-column">
                        <div class="sp-module">
                            <div class="sp-module-content" style="color: black;">
                                <div class="invoice">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h2 class="page-header">
                                                <i class="fa fa-globe"></i> <?= Translate::_(
                                                    'business',
                                                    'You have exceeded the number of attempts allowed.'
                                                ) ?>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function dropFooterFixedBottom() {
        var footer = document.getElementById("sp-footer");
        footer.classList.remove("navbar-fixed-bottom");
    }

    function initForm() {
        dropFooterFixedBottom();
    }
    <?php
    $this->registerJs(
        '$("#sms_field").inputmask({"mask": "999 999"});
    $("#email_field").inputmask({"mask": "999 999"});',
        View::POS_END
    ) ?>

    <?php $this->registerJs("initForm();", View::POS_END); ?>
</script>

<style>
    footer {
        position: fixed;
        bottom: 0;
        width: 100%;
    }
</style>
