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
$formAsset->passwordGenerator($this);
$wizardAsset = IdbWizardAsset::register($this);
$this->title = Translate::_('business', 'Business Details');

?>

<script>
    const passwordPolicyJson = '<?= html_entity_decode($model->passwordPolicy) ?>';
</script>

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

                                <?= $form->field($model, 'name', ['enableClientValidation' => false])->textInput(
                                    ['placeholder' => $model->getAttributeLabel('name')]
                                ) ?>
                                <?= $form->field($model, 'VAT')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('VAT')]
                                ) ?>
                                <?= $form->field($model, 'registrationNumber')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('registrationNumber')]
                                ) ?>
                                <?= $form->field($model, 'addressLine1')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('addressLine1')]
                                ) ?>
                                <?= $form->field($model, 'addressLine2')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('addressLine2')]
                                ) ?>
                                <?= $form->field($model, 'city')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('city')]
                                ) ?>
                                <?= $form->field($model, 'region')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('region')]
                                ) ?>
                                <?= $form->field($model, 'postcode')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('postcode')]
                                ) ?>
                                <?= $form->field($model, 'country')->textInput(
                                    ['placeholder' => $model->getAttributeLabel('country')]
                                ); ?>

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

    <?php $this->registerJs("dropFooterFixedBottom();", View::POS_END); ?>

</script>
