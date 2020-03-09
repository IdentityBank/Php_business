<?php

use app\assets\AdminLte2Asset;
use app\assets\AppFormAsset;
use app\helpers\Translate;
use idbyii2\helpers\StaticContentHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$assetUrl = $assetBundle->getAssetUrl();
$formAsset = AppFormAsset::register($this);

$this->title = Translate::_('business', 'Multi-Factor Authentication');
$userId = ((empty(Yii::$app->user->identity->userId)) ? '' : Yii::$app->user->identity->userId);
?>

<div class="lockscreen-wrapper">

    <div class="lockscreen-logo">
        <b>Identity Bank</b>
    </div>
    <div class="lockscreen-name"><?= $userId ?></div>

    <div class="lockscreen-item">
        <div class="lockscreen-image">
            <img src="<?= $assetUrl . 'idb/img/ico/android-chrome-192x192.png' ?>" alt="User Image">
        </div>

        <?php $form = ActiveForm::begin(
            [
                'fieldConfig' => [
                    'template' => "{input}"
                ],
                'options' => ['class' => 'lockscreen-credentials']
            ]
        ); ?>

        <div class="input-group">
            <?= $form->field($model, 'code')->textInput(
                [
                    'class' => 'form-control',
                    'style' => 'text-align: center;',
                    'placeholder' => Translate::_('business', 'MFA Code')
                ]
            ) ?>
            <div class="input-group-btn">
                <?= Html::submitButton(
                    '<i class="fa fa-arrow-right text-muted"></i>',
                    ['class' => 'btn', 'id' => 'save']
                ) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

    <div class="help-block text-center">
        <?= Translate::_('business', 'Please enter an MFA code to complete login.') ?>
    </div>
    <div class="text-center">
        <?= Html::a(Translate::_('business', 'MFA Recovery'), ['/mfarecovery/email-verification']) ?>
    </div>
    <div class="text-center">
        <?= Html::a(Translate::_('business', 'Or sign in as a different user'), ['logout']) ?>
    </div>

    <div class="lockscreen-footer text-center">
        <p>
            <?= StaticContentHelper::getFooter(['footer_language' => Yii::$app->language]); ?>
        </p>
    </div>

</div>

<script>
    document.body.className += ' ' + 'hold-transition lockscreen';

    function initMfa() {
        $("#dynamicmodel-code").inputmask({"mask": "999 999"});
        $("#dynamicmodel-code").focus();
    }
    <?php $this->registerJs("initMfa();", View::POS_END); ?>
</script>
