<?php

use app\assets\AdminLte2Asset;
use app\helpers\Translate;

$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutError($this);
$assetUrl = $assetBundle->getAssetUrl();
$params = [
    'content' => '_errorContent',
    'menu_active_section' => '[menu][error]',
    'menu_active_item' => '[menu][error]',
    'contentParams' => '_errorContent',
    'assetUrl' => $assetUrl
];

$exception = Yii::$app->errorHandler->exception;

if ($exception) {
    $statusCode = ((empty($exception->statusCode)) ? null : $exception->statusCode);
    $name = $exception->getName();
    $message = $exception->getMessage();

    $params['contentParams'] =
        [
            'exception' => $exception,
            'statusCode' => $statusCode,
            'name' => $name,
            'message' => $message
        ];
}

$this->title = $this->context->getPageTitle(Translate::_('business', 'Error') . " - $name");
?>
<?= Yii::$app->controller->renderPartial('_template', $params) ?>
