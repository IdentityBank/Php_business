<?php

use app\helpers\BusinessConfig;
use app\helpers\Translate;
use idbyii2\enums\NotificationType;
use idbyii2\widgets\Loading;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;

////////////////////////////////////////////////////////////////////////////////
// TEMPORARY SOLUTION BEFORE WE WILL FIX ACTION FOR RED NOTIFICATIONS !!!
////////////////////////////////////////////////////////////////////////////////
if (!BusinessConfig::get()->isPortalRedAlertEnabled()) {
    Yii::$app->view->params['notifications'][NotificationType::RED] = null;
}
////////////////////////////////////////////////////////////////////////////////

$notifications = (!empty(Yii::$app->view->params['notifications'])) ? Yii::$app->view->params['notifications'] : [];


$params = [
    'assetUrl' => $assetUrl,
    'menu_active_section' => $menu_active_section,
    'menu_active_item' => $menu_active_item,
];
if (!empty($contentParams) && is_array($contentParams)) {
    foreach ($contentParams as $key => $value) {
        $params[$key] = $value;
    }
}

$allowed = false;
if (
    !empty($notifications[NotificationType::RED])
    && strpos(Url::current(),'/defaultRoute') === false
) {
    foreach ($notifications[NotificationType::RED] as $notification) {
        $data = json_decode($notification->data, true);

        if (
            strpos(Url::toRoute(Yii::$app->request->url, true), $data['url']) !== false
            || strpos(Yii::$app->request->url, 'idbverification') !== false
            || strpos(Yii::$app->request->url, 'restore-account') !== false
        ) {
            $allowed = true;
            break;
        }
    }

    if (!$allowed) {
        return Yii::$app->response->redirect('/');
    }
}

?>

<div class="wrapper">
    <?= Yii::$app->controller->renderPartial(
        '@app/themes/adminlte2/views/site/_header',
        ArrayHelper::merge($params, ['notifications' => $notifications])
    ) ?>
    <?= Yii::$app->controller->renderPartial('@app/themes/adminlte2/views/site/_leftSidebarUserPanel', $params) ?>
    <?= Yii::$app->controller->renderPartial($content, $params) ?>
    <?= Loading::widget(['preventReady' => $params['preventReady'] ?? false,'preventLoading' => $params['preventLoading'] ?? false]) ?>
    <?= Yii::$app->controller->renderPartial('@app/themes/adminlte2/views/site/_footer', $params) ?>
    <?= Yii::$app->controller->renderPartial('@app/themes/adminlte2/views/site/_rightSidebarControlPanel', $params) ?>

    <?php if (!empty($notifications[NotificationType::RED]) && !$allowed): ?>
        <div id="mandatory-overlay">

            <div class="callout callout-danger" style="margin-top: 51px;">
                <?php foreach ($notifications[NotificationType::RED] as $notification):
                    $data = json_decode($notification->data, true);
                    $title = !empty($data['title']) ? $data['title'] : '';
                    $body = !empty($data['body']) ? $data['body'] : '';
                    ?>
                    <h4><i class="fa fa-exclamation-circle text-white"></i>&nbsp;<?= $title ?></h4>
                    <p><?= $body ?></p>
                <?php endforeach; ?>
                <div id="for_overlay_btn"></div>
            </div>

        </div>
    <?php endif; ?>
</div>

<?php if (!empty(Yii::$app->view->params['notifications'][NotificationType::AMBER])): ?>
    <script>
        function showAmber(amberMessage) {

            var wrapper_css = {
                'padding': '20px 20px',
                'background': '#f39c12',
                'color': "white",
                'display': 'none',
                'z-index': '999999',
                'font-size': '16px',
                'font-weight': 600
            };

            var wrapper = $('<div id="notification_amber_message" />').css(wrapper_css);
            wrapper.append(amberMessage);

            $('.content-wrapper').prepend(wrapper);

            wrapper.hide(4).delay(500).slideDown()
        }

        function hideAmber() {
            $('#notification_amber_message').hide();
        }
    </script>

    <?php
    $amberCloseButton = Translate::_('business', "Hide notification");
    $amberMessage = "<div class=\"box-tools pull-right\"><a class=\"float-right\" href=\"#\" onclick=\"hideAmber()\" title=\"$amberCloseButton\" style=\"color: rgb(255, 255, 255); font-size: 20px;\">×</a></div>";
    $amberMessage .= "<ul style=\"border: 0px; padding: 0px; list-style-type: none;\">";
    foreach ($notifications[NotificationType::AMBER] as $notification) {
        $data = json_decode($notification->data, true);
        $title = !empty($data['title']) ? $data['title'] : '';
        $body = !empty($data['body']) ? $data['body'] : '';
        $amberMessage .= "<li>$title - $body</li>";
    }
    $amberMessage .= "</ul>";
    ?>
    <?php $this->registerJs("showAmber('$amberMessage');", View::POS_END); ?>
<?php endif; ?>

