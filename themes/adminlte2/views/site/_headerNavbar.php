<?php

use yii\helpers\ArrayHelper;

$params = [
    'assetUrl' => $assetUrl,
];

?>

<nav class="navbar navbar-static-top navbar-size">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <?php if ($skin === 'skin-idb') { ?>
            <i class="fas fa-bars"></i>
        <?php } ?>
    </a>

    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

            <?= Yii::$app->controller->renderPartial(
                '@app/themes/adminlte2/views/site/_headerNavbarHelp',
                $params
            ) ?>
            <?= Yii::$app->controller->renderPartial(
                '@app/themes/adminlte2/views/site/_headerNavbarNotification',
                ArrayHelper::merge($params, ['notifications' => $notifications])
            ) ?>
            <?= Yii::$app->controller->renderPartial(
                '@app/themes/adminlte2/views/site/_headerNavbarUserMenu',
                $params
            ) ?>

        </ul>
    </div>
</nav>
