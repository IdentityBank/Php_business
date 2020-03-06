<?php

$params = [
    'paymentType' => 'payment',
    'id' => $id,
    'paymentAttributes' => $paymentAttributes,
    'locale' => $locale,
    'originKey' => $originKey,
    'loadingContext' => $loadingContext,
];

?>

<?= $this->context->renderPartial('billing', $params) ?>
