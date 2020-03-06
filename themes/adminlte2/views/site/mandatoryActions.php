<?php

use app\assets\AdminLte2Asset;
use app\helpers\Translate;

$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$assetUrl = $assetBundle->getAssetUrl();
$params = [
    'content' => '_mandatoryActions',
    'menu_active_section' => '[menu][site]',
    'menu_active_item' => '[menu][site][index]',
    'assetUrl' => $assetUrl
];

$this->title = $this->context->getPageTitle(Translate::_('business', 'Mandatory Actions'));
?>
<?= Yii::$app->controller->renderPartial('_template', $params) ?>
