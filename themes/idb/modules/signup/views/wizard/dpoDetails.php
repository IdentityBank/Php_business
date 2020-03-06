<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\assets\AppFormAsset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$formAsset = AppFormAsset::register($this);
$wizardAsset = IdbWizardAsset::register($this);
$this->title = Translate::_('business', 'Business Details');

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
                        'Icon' => 'glyphicon-briefcase',
                        'Title' => Translate::_('business', 'Business Details')
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

                                <h2><?= Translate::_(
                                        'business',
                                        'Enter info about Data Protection Officer:'
                                    ) ?></h2>

                                <?= $form->field($model, 'dpoEmail')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('dpoEmail')]
                                ) ?>
                                <?= $form->field($model, 'dpoMobile')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('dpoMobile')]
                                ) ?>
                                <?= $form->field($model, 'dpoAddress')->textArea(
                                    ['rows' => '3', 'placeholder' => $model->getAttributeLabel('dpoAddress')]
                                ) ?>
                                <?= $form->field($model, 'dpoOther')->textArea(
                                    ['rows' => '3', 'placeholder' => $model->getAttributeLabel('dpoOther')]
                                ) ?>

                                <h2><?= Translate::_(
                                        'business',
                                        'Provide your business web links:'
                                    ) ?></h2>

                                <?= $form->field($model, 'dpoTermsAndCondition')->textInput(
                                    [
                                        'placeholder' => Translate::_('business', 'Web URL to ')
                                            . $model->getAttributeLabel('dpoTermsAndCondition')
                                    ]
                                ) ?>
                                <?= $form->field($model, 'dpoDataProcessingAgreements')->textInput(
                                    [
                                        'placeholder' => Translate::_('business', 'Web URL to ')
                                            . $model->getAttributeLabel('dpoDataProcessingAgreements')
                                    ]
                                ) ?>
                                <?= $form->field($model, 'dpoPrivacyNotice')->textInput(
                                    [
                                        'placeholder' => Translate::_('business', 'Web URL to ')
                                            . $model->getAttributeLabel('dpoPrivacyNotice')
                                    ]
                                ) ?>
                                <?= $form->field($model, 'dpoCookiePolicy')->textInput(
                                    [
                                        'placeholder' => Translate::_('business', 'Web URL to ')
                                            . $model->getAttributeLabel('dpoCookiePolicy')
                                    ]
                                ) ?>


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

    <?php $this->registerJs("dropFooterFixedBottom();", View::POS_END); ?>

</script>
