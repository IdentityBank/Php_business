<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\assets\AppFormAsset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;
use idbyii2\widgets\PasswordGenerator;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$formAsset = AppFormAsset::register($this);
$formAsset->passwordGenerator($this);
$wizardAsset = IdbWizardAsset::register($this);
$this->title = Translate::_('business', 'Your account details');

?>

<div class="container">
    <div class="container-inner">
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizard(
                    [
                        'Icon' => 'glyphicon-circle-arrow-right',
                        'Title' => Translate::_('business', 'Start')
                    ],
                    [
                        'Icon' => 'glyphicon-user',
                        'Title' => Translate::_('business', 'Form')
                    ],
                    [
                        'Icon' => 'glyphicon-check',
                        'Title' => Translate::_('business', 'T&Cs')
                    ],
                    2,
                    $model->getErrors()
                ) ?>
            </div>
        </div>
        <br>
        <?php $form = ActiveForm::begin(
            ['id' => 'signup-register-form', 'fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField']
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
                                <h2><?= Translate::_(
                                        'business',
                                        'Check the following data and if it is correct continue with registration.'
                                    ) ?>
                                </h2>

                                <?= $form->field($model, 'userId', ['enableClientValidation' => false])->textInput(
                                    ['value' => $model->userId]
                                ) ?>
                                <?= $form->field($model, 'firstname')->textInput(['value' => $model->firstname]) ?>
                                <?= $form->field($model, 'lastname')->textInput(['value' => $model->lastname]) ?>
                                <p class="alert alert-wizard"><?= Translate::_(
                                        'business',
                                        'If your email address or phone number is incorrect, please contact the person who initiated the registration process to update the data. You cannot correct this information yourself.'
                                    ) ?></p>
                                <?= $form->field($model, 'email')->hiddenInput(['value' => $model->email]) ?>
                                <p class="info-form-sigup-value"><?= $model->email ?></p>
                                <?= $form->field($model, 'mobile')->hiddenInput(['value' => $model->mobile]) ?>
                                <p class="info-form-sigup-value"><?= $model->mobile ?></p>

                                <?= PasswordGenerator::widget(['model' => $model, 'form' => $form]) ?>

                                <?php if ($model->captchaEnabled) {
                                    echo Yii::$app->signUpCaptcha->config(
                                        [
                                            'inputName' => 'SignUpForm[verificationCode]',
                                            'inputId' => 'signupform-verificationcode'
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
                        'Text' => Translate::_('business', 'Continue account signup'),
                        'Action' => 'Submit',
                        'Help' => Translate::_('business', 'Continue')
                    ],
                    [
                        'Text' => Translate::_('business', 'Cancel account signup'),
                        'Action' => ['/signup'],
                        'Help' => Translate::_('business', 'Cancel')
                    ]
                ) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    function dropFooterFixedBottom() {
        let footer = document.getElementById("sp-footer");
        footer.classList.remove("navbar-fixed-bottom");
    }

    function initForm() {
        dropFooterFixedBottom();
    }
    <?php $this->registerJs("initForm();", View::POS_END); ?>
</script>
