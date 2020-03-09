<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;
use idbyii2\widgets\FlashMessage;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$wizardAsset = IdbWizardAsset::register($this);
$assetBundle->layoutMain($this);
$assetBundle->layoutForms($this);
$wizardAsset->paymentsAssets();
$this->title = Translate::_('business', 'Subscription Payment');

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
                                        <?php if (!empty($response)): ?>

                                            <pre>
                                            <?php var_dump($response); ?>
                                        </pre>

                                        <?php endif; ?>
                                        <h4 class="page-header"><?= Translate::_(
                                                'business',
                                                'Please select your preferred payment method for the Service Plan you have selected and fill in the required payment details.'
                                            ) ?>: </h4>

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

                                        <div class="payment-wrapper">
                                            <div class="panel with-nav-tabs panel-payment-idb">
                                                <div class="panel-heading">
                                                    <ul class="nav nav-tabs">
                                                        <li class="active"><a data-toggle="tab"
                                                                              href="#card-form"><?= Translate::_(
                                                                    'business',
                                                                    'Card'
                                                                ) ?></a>
                                                        </li>
                                                        <li><a data-toggle="tab"
                                                               href="#sepa-form"><?= Translate::_(
                                                                    'business',
                                                                    'SEPA'
                                                                ) ?></a>
                                                        </li>

                                                    </ul>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="tab-content">
                                                        <div id="card-form" class="tab-pane fade in active"
                                                             style="width: 100%; margin: 10px auto;">
                                                            <form method="post" id="card-form">
                                                                <div id="card"></div>
                                                                <input type="hidden" id="card-state"
                                                                       name="paymentState"/>
                                                                <input type="hidden"
                                                                       name="<?= Yii::$app->request->csrfParam; ?>"
                                                                       value="<?= Yii::$app->request->csrfToken; ?>"/>
                                                                <input type="hidden" name="paymentMethod"
                                                                       value="scheme"/>
                                                                <input type="hidden" name="value" value="1000"/>
                                                                <hr/>
                                                                <button id="card-button" class="btn btn-success"
                                                                        style="width: 100%;"
                                                                        disabled
                                                                        type="submit"><?= Translate::_(
                                                                        'business',
                                                                        'Pay'
                                                                    ) ?>
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div id="sepa-form" class="tab-pane fade"
                                                             style="width: 100%; margin: 10px auto;">
                                                            <form method="post">
                                                                <div id="sepa"></div>
                                                                <input type="hidden" id="sepa-state"
                                                                       name="paymentState"/>
                                                                <input type="hidden"
                                                                       name="<?= Yii::$app->request->csrfParam; ?>"
                                                                       value="<?= Yii::$app->request->csrfToken; ?>"/>
                                                                <input type="hidden" name="paymentMethod"
                                                                       value="sepadirectdebit"/>
                                                                <input type="hidden" name="value" value="1000"/>
                                                                <hr/>
                                                                <button id="sepa-button" class="btn btn-success"
                                                                        style="width: 100%;"
                                                                        disabled
                                                                        type="submit"><?= Translate::_(
                                                                        'business',
                                                                        'Pay'
                                                                    ) ?>
                                                                </button>
                                                            </form>
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
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const LOCALE = '<?= $locale ?>';
    const ORIGIN_KEY = '<?= $originKey ?>';
    const LOADING_CONTEXT = '<?= $loadingContext ?>';
</script>



