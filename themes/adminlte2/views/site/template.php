<?php

use app\assets\AdminLte2Asset;

if (empty($params)) {
    Yii::$app->end();
}
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$assetUrl = $assetBundle->getAssetUrl();
$params = array_merge($params, ['assetUrl' => $assetUrl]);
?>
<?= Yii::$app->controller->renderPartial('@app/themes/adminlte2/views/site/_template', $params) ?>
