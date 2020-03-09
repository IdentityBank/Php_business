<?php

use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use idbyii2\models\db\BusinessAuthlog;

$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$assetUrl = $assetBundle->getAssetUrl();
$authLogModels = BusinessAuthlog::findAllByUid(Yii::$app->user->identity->id, 10);

if (empty($content)) {
    if (count($authLogModels) === 1) {
        $content = '_firstLoginContent';
    } else {
        $content = '_indexContent';
    }
}

$params = [
    'content' => $content,
    'contentParams' => $params['contentParams'],
    'menu_active_section' => '[menu][site]',
    'menu_active_item' => '[menu][site][index]',
    'assetUrl' => $assetUrl
];

$this->title = $this->context->getPageTitle(Translate::_('business', 'Control panel'));
?>
<?= Yii::$app->controller->renderPartial('_template', $params) ?>
