<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$skin = BusinessConfig::get()->getYii2BusinessSkin();

$params = [
    'assetUrl' => $assetUrl,
    'skin' => $skin
];

switch ($skin) {
    case 'skin-idb':
        {
            $mini = "<img style='width: 70%;' src=\"${assetUrl}idb/img/ico/favicon.ico\" alt=\"Identity Bank Logo\" title=\"Identity Bank Logo\"/>";
            $large = "<img src=\"${assetUrl}idb/img/idblogotext.png\" alt=\"Identity Bank Logo\" title=\"Identity Bank Logo\"/>";
        }
        break;
    default:
    {
        $mini = '<b>ID</b><small>B</small>';
        $large = '<b>ID</b><small> Bank</small>';
    }
}

?>

<header class="main-header">
    <a href="<?= Url::home() ?>" class="logo">
        <span class="logo-mini"><?= $mini ?></span>
        <span class="logo-lg"><?= $large ?></span>
    </a>
    <?= Yii::$app->controller->renderPartial(
        '@app/themes/adminlte2/views/site/_headerNavbar',
        ArrayHelper::merge($params, ['notifications' => $notifications])
    ) ?>
</header>
