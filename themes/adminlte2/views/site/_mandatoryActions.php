<?php

use app\helpers\Translate;

$user = Yii::$app->user->identity;

?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
        </div>
    </section>
</div>

<?php if (!empty(Yii::$app->view->params['status'])): ?>
    <div id="mandatory-overlay">
        <div class="callout callout-danger" style="margin-top: 51px;">
            <?php if (Yii::$app->view->params['userDb']):
                $title = Translate::_('business', "Missing business vault setup.");
                $body = Translate::_('business', "Register or select your active vault.");
                ?>
                <h4><i class="fa fa-exclamation-circle text-white"></i>&nbsp;<?= $title ?></h4>
                <p><?= $body ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
