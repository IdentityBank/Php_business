<?php

use app\helpers\Translate;

$this->title = Translate::_('business', 'Test Payment');

$paymentsHint = Translate::_(
    'business',
    'Please select your preferred payment method and fill in the details required below.'
);

$params = [
    'paymentType' => 'payment',
    'id' => 'test',
    'paymentAttributes' => $paymentAttributes,
    'paymentsHint' => $paymentsHint,
    'locale' => $locale,
    'response' => $response,
    'originKey' => $originKey,
    'loadingContext' => $loadingContext,
    'paymentsHint' => $paymentsHint,
];

?>

<?= $this->context->renderPartial('_paymentView', $params) ?>
