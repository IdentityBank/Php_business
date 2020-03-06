<?php

use app\helpers\Translate;
use yii\helpers\Url;

?>

<div class="payment-wrapper">
    <div class="panel with-nav-tabs panel-payment-idb">
        <?php if ($paymentType === 'payment') : ?>
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
        <?php endif; ?>
        <div class="panel-body">
            <div class="tab-content">

                <?php if ($paymentType === 'payment') : ?>
                    <div id="card-form" class="tab-pane fade in active"
                         style="width: 100%; margin: 10px auto;">
                        <?= $this->context->renderPartial(
                            '_paymentCost',
                            ['paymentAttributes' => $paymentAttributes]
                        ) ?>
                        <form method="post" id="card-form">
                            <div id="card"></div>
                            <input type="hidden" id="card-state"
                                   name="paymentState"/>
                            <input type="hidden"
                                   name="<?= Yii::$app->request->csrfParam; ?>"
                                   value="<?= Yii::$app->request->csrfToken; ?>"/>
                            <input type="hidden" name="paymentMethod"
                                   value="scheme"/>
                            <input type="hidden" name="value" value="<?= $paymentAttributes['value'] ?>"/>
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
                        <?= $this->context->renderPartial(
                            '_paymentCost',
                            ['paymentAttributes' => $paymentAttributes]
                        ) ?>
                        <form method="post">
                            <div id="sepa"></div>
                            <input type="hidden" id="sepa-state"
                                   name="paymentState"/>
                            <input type="hidden"
                                   name="<?= Yii::$app->request->csrfParam; ?>"
                                   value="<?= Yii::$app->request->csrfToken; ?>"/>
                            <input type="hidden" name="paymentMethod"
                                   value="sepadirectdebit"/>
                            <input type="hidden" name="value" value="<?= $paymentAttributes['value'] ?>"/>
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
                <?php endif; ?>

                <?php if ($paymentType === 'other') : ?>
                    <div id="other" class="tab-pane fade in active">
                        <div class="row">
                            <form id="payment-request" method="POST"
                                  action=" <?= Url::toRoute(
                                      ['payment-request', 'id' => $id],
                                      true
                                  ) ?>">

                                <div class="col-xs-12">
                                    <h4 class="page-header signup-text-color"><?= Translate::_(
                                            'business',
                                            'Enter the email address of the authorised person and then click on the send payment request button.'
                                        ) ?></h4>
                                    <p class="signup-text-color"
                                       style="margin-bottom: 30px;"><?= Translate::_(
                                            'business',
                                            'The person to whom you send the payment request will be guided to the payment screen. They will not have to go through the signup process from the beginning. When the payment has been processed you will be notified and you can finish the signup process.'
                                        ) ?></p>
                                    <input name="email" placeholder="email"
                                           style="height: 40px; border: 1px solid #999999; width: 100%; margin-bottom: 30px; line-height:1.25; font-size: 2rem; padding: .5rem .75rem; border-radius: .25rem; "
                                           type="email" required/>
                                    <input type="hidden"
                                           name="<?= Yii::$app->request->csrfParam; ?>"
                                           value="<?= Yii::$app->request->csrfToken; ?>"/>
                                    <button type="submit"
                                            class="btn btn-warning"
                                            style="width: 100%;">
                                        <?= Translate::_(
                                            'business',
                                            'Send payment request'
                                        ) ?>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
    const LOCALE = '<?= $locale ?>';
    const ORIGIN_KEY = '<?= $originKey ?>';
    const LOADING_CONTEXT = '<?= $loadingContext ?>';
</script>
