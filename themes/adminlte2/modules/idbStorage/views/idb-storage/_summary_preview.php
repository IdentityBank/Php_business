<?php

use yii\helpers\Html;
use app\helpers\Translate;

?>

<?= Html::a(Translate::_('business', 'Preview file'), ['preview', 'oid' => $oId], ['class' => 'btn btn-primary btn-lg btn-block']) ?>