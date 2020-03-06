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
$this->title = Translate::_('business', 'Create a new password');

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
                        'Title' => Translate::_('business', 'SMS verification')
                    ],
                    [
                        'Icon' => 'glyphicon-pencil',
                        'Title' => Translate::_('business', 'New password')
                    ],
                    3,
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
                                        )
                                    ]
                                ); ?>

                                <?= PasswordGenerator::widget(['model' => $model, 'form' => $form]) ?>


                                <?php if ($model->captchaEnabled) {
                                    echo Yii::$app->signUpCaptcha->config(
                                        [
                                            'inputName' => 'NewPasswordForm[verificationCode]',
                                            'inputId' => 'newpasswordform-verificationcode'
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
                        'Text' => Translate::_('business', 'Continue password recovery'),
                        'Action' => 'Submit',
                        'Help' => Translate::_('business', 'Continue')
                    ],
                    [
                        'Text' => Translate::_('business', 'Cancel password recovery'),
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

    }
    <?php $this->registerJs("initForm();", View::POS_END); ?>
</script>
