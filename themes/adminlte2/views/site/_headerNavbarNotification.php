<?php

use app\helpers\AdminLteViewHelper;
use app\helpers\Translate;
use idbyii2\enums\NotificationType;
use yii\helpers\Url;
use yii\web\View;

?>

<?php
foreach ([NotificationType::GREEN, NotificationType::AMBER, NotificationType::RED] as $type):

    switch ($type) {
        case NotificationType::GREEN:
            {
                $labelColor = 'label-success';
                $notificationIcon = 'fa-info text-green';
            }
            break;
        case NotificationType::AMBER:
            {
                $labelColor = 'label-warning';
                $notificationIcon = 'fa-warning text-yellow';
            }
            break;
        case NotificationType::RED:
            {
                $labelColor = 'label-danger';
                $notificationIcon = 'fa-exclamation-circle text-red';
            }
            break;
    }
    if (!empty($notifications[$type])):
        $countNotifications = count($notifications[$type]);
        ?>
        <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell bell-not"></i>
                <span class="label <?= $labelColor ?>"><?= $countNotifications ?></span>
            </a>
            <ul class="dropdown-menu">
                <li class="header">
                    <?= AdminLteViewHelper::getNotificationTitle($countNotifications) ?>
                <li>
                    <ul class="menu">
                        <?php foreach ($notifications[$type] as $notification): ?>
                            <?php //TODO: Rewrite this peace of code ?>
                            <?php $id = $notification->id; ?>
                            <?php $data = json_decode($notification->data, true); ?>
                            <?php $title = !empty($data['title']) ? $data['title'] : ''; ?>
                            <?php $body = !empty($data['body']) ? $data['body'] : ''; ?>
                            <?php $url = !empty($data['url']) ? $data['url'] : ''; ?>
                            <?php $action_name = !empty($data['action_name']) ? $data['action_name'] : ''; ?>
                            <?php if ($notification->type === 'red') {
                                ?>
                                <input type="hidden" id="for_overlay" data-id="<?= base64_encode(
                                    json_encode(['url' => Url::toRoute($url, true), 'action' => $action_name])
                                ) ?>">
                                <?php
                            }
                            ?>
                            <li>
                                <?php if (is_null($url) || is_null($action_name)): ?>
                                    <a id="link-notification" data-id="<?= base64_encode(
                                        json_encode(
                                            [
                                                'id' => $id,
                                                'type' => $type,
                                                'title' => $title,
                                                'body' => $body,
                                            ]
                                        )
                                    ) ?>" }" data-toggle="modal" href="#modal-notification">
                                    <i class="fa <?= $notificationIcon ?>"></i> <?= $title ?>
                                    </a>
                                <?php else: ?>
                                    <a id="link-notification" data-id="<?= base64_encode(
                                        json_encode(
                                            [
                                                'id' => $id,
                                                'type' => $type,
                                                'title' => $title,
                                                'body' => $body,
                                                'url' => Url::toRoute($url),
                                                'action' => $action_name
                                            ]
                                        )
                                    ) ?>" }" data-toggle="modal" href="#modal-notification">
                                    <i class="fa <?= $notificationIcon ?>"></i> <?= $title ?>
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </li>
    <?php endif; ?>
<?php endforeach; ?>

<div class="modal fade in" id="modal-notification" style="padding-right: 12px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="<?= Translate::_('business', 'Close') ?>">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="modal-notification-title"></h4>
            </div>
            <div class="modal-body" id="modal-notification-body"></div>
            <div class="modal-body" id="modal-notification-button"></div>
            <div class="modal-footer">
                <button id="remove-notification-button" type="button" class="btn btn-danger btn-outline pull-left"
                        data-dismiss="modal"><?= Translate::_(
                        'business',
                        'Remove'
                    ) ?></button>
                <button type="button" class="btn btn-outline pull-right" data-dismiss="modal"><?= Translate::_(
                        'business',
                        'Close'
                    ) ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    function removeNotification(dataId) {
        $.ajax({
            url: '<?= Url::toRoute(['/notifications/read'], true) ?>',
            type: 'post',
            data: {
                id: dataId,
                _csrf: yii.getCsrfToken()
            },
            success: function (data) {
                location.reload();
            }
        });
    }

    function initNavbarNotification() {
        $(document).on("click", "#link-notification", function () {
            var dataId = $(this).data('id');
            var data = JSON.parse(atob(dataId));
            $("#modal-notification-title").text(data["title"]);
            $("#modal-notification-body").text(data["body"]);

            if (data["url"] && data["action"]) {
                if ($('#btn-notifcation').length == 0) {
                    var $input = $('<a id="btn-notifcation" type="button"  class="btn btn-primary" href="' + data['url'] + '"/>');
                    $input.appendTo($("#modal-notification-button"));
                    $("#btn-notifcation").text(data["action"]);
                }
            } else {
                $("#modal-notification-button").remove();
            }
            $("#modal-notification").removeAttr('class');
            $("#modal-notification").addClass('modal fade in');
            $('#remove-notification-button').attr('onClick', 'removeNotification("' + data['id'] + '");');

            switch (data["type"]) {
                case "<?= NotificationType::GREEN ?>":
                    $("#modal-notification").addClass('modal-success');
                    $("#remove-notification-button").show();
                    break;
                case "<?= NotificationType::AMBER ?>":
                    $("#modal-notification").addClass('modal-warning');
                    $("#remove-notification-button").hide();
                    break;
                case "<?= NotificationType::RED ?>":
                default:
                    $("#modal-notification").addClass('modal-danger');
                    $("#remove-notification-button").hide();
                    break;
            }
        });
    }

    function ifOverlay() {
        if ($('#mandatory-overlay').length > 0) {
            var dataId = $("#for_overlay").data('id');
            var data = JSON.parse(atob(dataId));
            var input = $('<a id="btn-overlay" type="button" class="btn btn-primary" href="' + data['url'] + '"/>');
            console.log(data);
            input.appendTo($("#for_overlay_btn"));
            $("#btn-overlay").text(data["action"]);
        }
    }

    function check() {
        let url = '<?= Yii::$app->request->url; ?>';
        let btn = $("#btn-overlay").attr("href");
        if (url === btn) {
            $("#mandatory-overlay").hide();
        }
    }

</script>

<?php $this->registerJs("initNavbarNotification();", View::POS_END); ?>
<?php $this->registerJs("ifOverlay();", View::POS_END); ?>
<?php $this->registerJs("check();", View::POS_END); ?>
