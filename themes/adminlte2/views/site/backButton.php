<?php

use app\helpers\ReturnUrl;
use app\helpers\Translate;
use yii\helpers\Html;
use yii\helpers\Url;

$url = ReturnUrl::generateUrl();

?>
<div id="btn-back">
    <?= Html::a(
        Translate::_('business', 'Back to previous action'),
        Url::toRoute($url),
        ['class' => 'btn btn-primary']
    ) ?>
</div>
</br>
