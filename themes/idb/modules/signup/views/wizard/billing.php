<?php

use app\helpers\Translate;

$this->title = (($paymentType === 'other')
    ? ((empty($attributes['paid']))
        ?
        Translate::_('business', 'Request Subscription Payment')
        :
        Translate::_('business', 'Request for payment sent to authorized person'))
    : Translate::_('business', 'Subscription Payment'));

$paymentsHint = Translate::_(
    'business',
    'Please select your preferred payment method and fill in the details required below.'
);

$params = [
    'paymentType' => $paymentType,
    'id' => $id,
    'paymentAttributes' => $paymentAttributes,
    'locale' => $locale,
    'originKey' => $originKey,
    'loadingContext' => $loadingContext,
    'paymentsHint' => $paymentsHint,
];

?>

<?= $this->context->renderPartial('_paymentView', $params) ?>
