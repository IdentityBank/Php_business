<?php

use app\assets\PaymentAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;

$paymentAssets = PaymentAsset::register($this);

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Business Billing Information')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?= FlashMessage::widget(
                            [
                                'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash(
                                    'success'
                                ) : null,
                                'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error')
                                    : null,
                                'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info')
                                    : null,
                            ]
                        ); ?>
                        <div class="payment-wrapper">
                            <div class="panel with-nav-tabs panel-payment-idb">
                                <div class="panel-heading">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a data-toggle="tab" href="#card-form">
                                                <?= Translate::_('business', 'Card') ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#sepa-form">
                                                <?= Translate::_('business', 'SEPA') ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div id="card-form" class="tab-pane fade in active"
                                             style="width: 100%; margin: 10px auto;">
                                            <form method="post" id="card-form">
                                                <div id="card"></div>
                                                <input type="hidden" id="card-state" name="paymentState"/>
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
                                                        'Click here to make your payment'
                                                    ) ?>
                                                </button>
                                            </form>
                                        </div>
                                        <div id="sepa-form" class="tab-pane fade"
                                             style="width: 100%; margin: 10px auto;">
                                            <form method="post">
                                                <div id="sepa"></div>
                                                <input type="hidden" id="sepa-state" name="paymentState"/>
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
                                                        'Click here to make your payment'
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
    </section>
</div>

<script>
    const LOCALE = '<?= $locale ?>';
    const ORIGIN_KEY = '<?= $originKey ?>';
    const LOADING_CONTEXT = '<?= $loadingContext ?>';
</script>
