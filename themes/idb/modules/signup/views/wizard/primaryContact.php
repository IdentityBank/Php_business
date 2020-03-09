<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\assets\AppFormAsset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;
use idbyii2\widgets\FlashMessage;
use idbyii2\widgets\PasswordGenerator;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$formAsset = AppFormAsset::register($this);
$wizardAsset = IdbWizardAsset::register($this);
$this->title = Translate::_('business', 'Primary account contact');

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
            [
                'id' => 'signup-form',
                'fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField',
                'action' => ['primary-contact'],
                'options' => ['method' => 'post']
            ]
        ); ?>
        <div class="row">
            <div class="col-lg-10" style="float: none;margin: 0 auto;">
                <div class="sp-column">
                    <div class="sp-module">
                        <div class="sp-module-content">
                            <div>
                                <?php if ($model->getErrors()): ?>
                                    <?= Html::tag(
                                        'div',
                                        $form->errorSummary($model),
                                        ['class' => 'alert alert-danger']
                                    ) ?>
                                <?php endif; ?>

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
                                        'message' => Translate::_(
                                            'business',
                                            'Contact with administrator, or signup here'
                                        ),
                                    ]
                                ); ?>

                                <h2><?= Translate::_(
                                        'business',
                                        'Enter information about the person who will be the primary account and billing contact for your business'
                                    ) ?></h2>

                                <?= $form->field($model, 'firstname')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('firstname')]
                                ) ?>
                                <?= $form->field($model, 'lastname')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('lastname')]
                                ) ?>
                                <?= $form->field($model, 'initials')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('initials')]
                                ) ?>
                                <?= $form->field($model, 'email')->input(
                                    'email',
                                    ['placeholder' => $model->getAttributeLabel('email')]
                                ) ?>
                                <?= $form->field($model, 'mobile', ['enableClientValidation' => false])->textInput(
                                    ['placeholder' => Translate::_('business', '+(44) 1234 123456')]
                                ) ?>

                                <?= PasswordGenerator::widget(['model' => $model, 'form' => $form]) ?>

                                <?= $form->field($model, 'name', ['enableClientValidation' => false])->hiddenInput(
                                    ['value' => $model->name]
                                )->label(false) ?>
                                <?= $form->field($model, 'VAT', ['enableClientValidation' => false])->hiddenInput(
                                    ['value' => $model->VAT]
                                )->label(false) ?>
                                <?= $form->field($model, 'registrationNumber', ['enableClientValidation' => false])
                                         ->hiddenInput(['value' => $model->registrationNumber])
                                         ->label(false) ?>
                                <?= $form->field($model, 'addressLine1', ['enableClientValidation' => false])
                                         ->hiddenInput(['value' => $model->addressLine1])
                                         ->label(false) ?>
                                <?= $form->field($model, 'dpo', ['enableClientValidation' => false])
                                    ->hiddenInput(['value' => json_encode($model->dpo)])
                                    ->label(false) ?>
                                <?= $form->field($model, 'addressLine2', ['enableClientValidation' => false])
                                         ->hiddenInput(['value' => $model->addressLine2])
                                         ->label(false) ?>
                                <?= $form->field($model, 'city', ['enableClientValidation' => false])->hiddenInput(
                                    ['value' => $model->city]
                                )->label(false) ?>
                                <?= $form->field($model, 'region', ['enableClientValidation' => false])->hiddenInput(
                                    ['value' => $model->region]
                                )->label(false) ?>
                                <?= $form->field($model, 'postcode', ['enableClientValidation' => false])->hiddenInput(
                                    ['value' => $model->postcode]
                                )->label(false) ?>
                                <?= $form->field($model, 'country', ['enableClientValidation' => false])->hiddenInput(
                                    ['value' => $model->country]
                                )->label(false) ?>

                                <?php if ($model->authenticatorEnabled) { ?>
                                    <h2><?= Translate::_('business', 'Authentication') ?></h2>
                                    <?= $form->field($model, 'authenticatorCode')->textInput(
                                        ['placeholder' => $model->getAttributeLabel('authenticatorCode')]
                                    ); ?>
                                <?php } ?>
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
        var footer = document.getElementById("sp-footer");
        footer.classList.remove("navbar-fixed-bottom");
    }

    function initForm() {
        dropFooterFixedBottom();
        <?php if($model->authenticatorEnabled) { ?>
        $("#idbbusinesssignupform-authenticatorcode").inputmask({"mask": "999 999"});
        <?php } ?>
        $("#idbbusinesssignupform-mobile").inputmask({
            "mask": "<?= Translate::_(
                'business',
                "+(99) 999 999 999 999"
            ) ?>"
        });
    }
    <?php $this->registerJs("initForm();", View::POS_END); ?>
</script>
