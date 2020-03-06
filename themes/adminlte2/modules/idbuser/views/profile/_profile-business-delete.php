<?php

use app\helpers\Translate;
use yii\helpers\Html;

$script = "
$('#continue-delete').click(function() {
    $(\"#btn-delete\").toggle(this.checked);
});
$('.show-modal').click(function(){
$('#modal-default').modal('show')});
";
$this->registerJs($script, yii\web\View::POS_END);


?>

<div class="tab-pane" id="delete">
    <div class="box-body">
        <h1><?= Translate::_('business', 'Delete account'); ?></h1>
            <?= Html::button('Continue to delete my account', ['class' => 'btn btn-primary input-block-level form-control show-modal']) ?>
    </div>
</div>

<div class="modal fade" id="modal-default" style="display: none; z-index: 99999; background-color: rgba(215, 247, 245, 0.97);">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align: center;">
                    <p class="bold-msg"><?= Translate::_(
                            'business',
                            'You have decided to close your Identity Bank account.'
                        ); ?></p>
                </h4>
            </div>
            <div class="modal-body">
                <p><?= Translate::_(
                        'business',
                        'You are granted a grace period during which time you can consider if you really want to proceed and permanently delete the account or you wish the account to remain open. The grace period is 30 days from the time you take the action to close your account.'
                    ); ?></p>
                <p><?= Translate::_(
                        'business',
                        '<b>Note</b>: You will be charged and billed for your account until it is ultimately closed after expiry of the grace period. See our Terms and Conditions. '
                    ); ?></p>
                <p><?= Translate::_(
                        'business',
                        'Should you wish to reinstate your account you will need to contact our <a href="mailto:customer.services@identitybank.eu">customer services</a> department to arrange this. '
                    ); ?></p>
                <p>
                    <input type="checkbox" id="continue-delete" name="continue">
                    <label for="continue-delete"><?= Translate::_(
                            "business",
                            "I understand the terms and conditions under which my account will be closed."
                        ) ?></label>
                </p>
                <?= Html::a(
                    Translate::_('business', 'Continue to delete my account'),
                    ['delete-account'],
                    [
                        'class' => 'btn btn-danger input-block-level form-control',
                        'id' => 'btn-delete',
                        'style' => 'display: none;',
                    ]
                ) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success input-block-level form-control" data-dismiss="modal"><?= Translate::_('business', 'Keep my account') ?></button>
            </div>
        </div>
    </div>
</div>