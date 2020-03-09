<?php

use app\assets\AdminLte2Asset;
use app\assets\AppFormAsset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbAsset;
use idbyii2\widgets\FlashMessage;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$assetUrl = $assetBundle->getAssetUrl();
$formAsset = AppFormAsset::register($this);
IdbAsset::register($this)->getMfaAsset();

$this->title = $title;

?>

<div class="lockscreen-wrapper box box-default"
     style="max-width: 900px; margin-top: 0px; padding-left: 20px; padding-right: 20px;">

    <br>

    <?= FlashMessage::widget(
        [
            'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success') : null,
            'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : null,
            'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : null,
        ]
    ); ?>

    <div class="help-block text-center" style="color:#091973;font-size: larger;">
        <?= Translate::_(
            'business',
            'Follow steps 1 & 2 to authenticate the login details you have entered for your account'
        ) ?>
    </div>
    <br>

    <div class="row">
        <div class="col-lg-6 spacer-vertical-right">

            <div class="row lockscreen-logo" style="font-size: 20px;">
                <div style="display:inline-block;"><b><?= Translate::_('business', 'Step') ?></b></div>
                <div class="numberCircle" style="display:inline-block;">1</div>
            </div>

            <div class="help-block text-center" style="color:#091973;">
                <?= Translate::_(
                    'business',
                    'Install an authenticator app on your smartphone then use it to scan the code shown below. There are many free and commercial options available, for example: Google Authenticator, LastPass or Authy.'
                ) ?>
            </div>

            <div class="lockscreen-item">
                <div class="row">
                    <div class="col-md-6" style="min-height:50px;">
                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                           class="mfa-store-logo" style="width:135px;"
                           id="targurlgoogle" target="_blank">
                            <img src="<?= $googleImg ?>"
                                 width="161" height="62"
                                 alt="Google Authenticator - Android Apps on Google Play">
                        </a>
                    </div>
                    <div class="col-md-6" style="min-height:50px;">
                        <a href="https://itunes.apple.com/app/google-authenticator/id388497605"
                           class="mfa-store-logo"
                           id="targurlmac" target="_blank">
                            <img src="<?= $appleImg ?>"
                                 width="110" height="31"
                                 alt="Google Authenticator on the App Store">
                        </a>
                    </div>
                </div>
            </div>

            <div class="lockscreen-name text-center" style="color:black;font-weight: 600;"><?= $userId ?></div>

            <div class="lockscreen-item">
                <?= Html::img($mfaQr, ['alt' => 'MFA QR']) ?>
            </div>

        </div>

        <div class="col-lg-6" style="padding-left: 20px;padding-right: 20px;">

            <div class="row lockscreen-logo" style="font-size: 20px;">
                <div style="display:inline-block;"><b><?= Translate::_('business', 'Step') ?></b></div>
                <div class="numberCircle" style="display:inline-block;">2</div>
            </div>

            <div class="help-block text-center" style="color:#091973;">
                <?= Translate::_(
                    'business',
                    'Now enter two consecutive codes from your authenticator app.'
                ) ?>
            </div>

            <div class="text-center">&nbsp;</div>

            <div class="lockscreen-item">
                <?php $form = ActiveForm::begin(
                    [
                        'fieldConfig' => [
                            'template' => "{input}"
                        ]
                    ]
                ); ?>
                <?php if ($model->getErrors()) { ?>
                    <?= Html::tag(
                        'div',
                        $form->errorSummary($model),
                        ['class' => 'alert alert-danger']
                    ) ?>
                <?php } ?>
                <div>
                    <?= $form->field($model, 'code')->textInput(
                        [
                            'placeholder' => Translate::_('business', 'Authentication Code 1'),
                            'style' => 'text-align: center;'
                        ]
                    ) ?>
                    <?= $form->field($model, 'code_next')->textInput(
                        [
                            'placeholder' => Translate::_('business', 'Authentication Code 2'),
                            'style' => 'text-align: center;'
                        ]
                    ) ?>
                    <?= Html::submitButton(
                        Translate::_('business', 'Continue'),
                        ['class' => 'btn btn-success', 'style' => 'text-align: center; width: 100%;', 'id' => 'save']
                    ) ?>

                    <?= $form->field($model, 'mfa')->hiddenInput()->label(false); ?>
                </div>
                <?php ActiveForm::end(); ?>

                <?php if (BusinessConfig::get()->isMfaSkipEnabled()) : ?>
                    <?php $form = ActiveForm::begin(['id' => 'skip-mfa-form']); ?>
                    <div class="text-center"><?= Translate::_('business', 'OR ') ?></div><br>
                    <div>
                        <?= Html::hiddenInput('action', 'skip-mfa') ?>
                        <?= Html::submitButton(
                            Translate::_('business', 'Skip MFA'),
                            [
                                'title' => Translate::_(
                                    'business',
                                    'We strongly recommend turning on Multi-Factor Authentication to protect your account.'
                                ),
                                'class' => 'btn btn-danger',
                                'style' => 'text-align: center; width: 100%;',
                                'id' => 'skip'
                            ]
                        ) ?>
                    </div><br>
                    <?php ActiveForm::end(); ?>
                <?php endif; ?>

                <div class="text-center"><?= Translate::_('business', 'OR ') ?></div>
                <br>
                <div>
                    <?= Html::a(
                        Translate::_('business', 'Cancel and return to the login page'),
                        ['logout'],
                        ['class' => 'btn btn-warning', 'style' => 'text-align: center; width: 100%;', 'id' => 'cancel']
                    ) ?>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
    document.body.className += ' ' + 'hold-transition lockscreen';

    function initMfa() {
        $("#dynamicmodel-code").inputmask({"mask": "999 999"});
        $("#dynamicmodel-code").focus();
        $("#dynamicmodel-code_next").inputmask({"mask": "999 999"});
    }
    <?php $this->registerJs("initMfa();", View::POS_END); ?>
</script>
