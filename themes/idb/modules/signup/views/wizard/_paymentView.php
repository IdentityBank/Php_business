<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;
use idbyii2\widgets\FlashMessage;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$assetBundle->layoutForms($this);
$wizardAsset = IdbWizardAsset::register($this);
$wizardAsset->paymentsAssets();

$params = [
    'id' => $id,
    'paymentType' => $paymentType,
    'paymentAttributes' => $paymentAttributes,
    'locale' => $locale,
    'originKey' => $originKey,
    'loadingContext' => $loadingContext,
];

?>

<div class="container">
    <div class="container-inner">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12" style="float: none;margin: 0 auto; margin-bottom: 16px;">
                    <?= $wizardAsset->generateWizard(
                        [
                            'Icon' => 'glyphicon-th-list',
                            'Title' => Translate::_('business', 'Choose package')
                        ],
                        [
                            'Icon' => 'glyphicon-euro',
                            'Title' => Translate::_('business', 'Payment')
                        ],
                        [
                            'Icon' => 'glyphicon-eye-open',
                            'Title' => Translate::_('business', 'Auth')
                        ],
                        2
                    ) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <div class="sp-column">
                    <div class="sp-module">
                        <div class="sp-module-content" style="color: black;">
                            <div class="invoice">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php if ($paymentType === 'payment') : ?>
                                            <h4 class="page-header signup-text-color" id="page-payment-info">
                                                <?= $paymentsHint ?>
                                            </h4>
                                        <?php endif; ?>
                                        <?= FlashMessage::widget(
                                            [
                                                'success' => Yii::$app->session->hasFlash('success')
                                                    ? Yii::$app->session->getFlash('success') : null,
                                                'error' => Yii::$app->session->hasFlash('error')
                                                    ? Yii::$app->session->getFlash('error') : null,
                                                'info' => Yii::$app->session->hasFlash('info')
                                                    ? Yii::$app->session->getFlash('info') : null,
                                            ]
                                        ); ?>
                                        <?php if (!empty($response)): ?>
                                            <pre>
                                                <?php var_dump($response); ?>
                                            </pre>
                                        <?php endif; ?>
                                        <?= $this->context->renderPartial('_paymentWrapper', $params) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
