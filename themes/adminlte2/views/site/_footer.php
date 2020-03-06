<?php

use idbyii2\helpers\StaticContentHelper;

?>

<footer id="sp-footer" class="main-footer">
    <p>
        <?= StaticContentHelper::getFooter(['footer_language' => Yii::$app->language]); ?>
    </p>
</footer>
