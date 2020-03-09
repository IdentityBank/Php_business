<?php

use app\helpers\Translate;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="info-box">
    <div class="box-tools pull-right">
        <a type="button"
           class="btn btn-box-tool bg-red"
           title="<?= Translate::_('business', 'Edit share') ?>"
           id="change-share"
           data-object-id="<?= $oId ?>">
            <i class="fa fa-user-edit"></i>
        </a>
    </div>
    <span class="info-box-icon bg-red"><i class="fa fa-users-cog"></i></span>
    <div class="info-box-content">
        <span class="info-box-text"><?= Translate::_('business', 'Shared') ?></span>
        <?php if (empty($share)): ?>
            <span class="info-box-number"><?= Translate::_('business', 'Everybody assigned to vault') ?></span>
        <?php else: ?>
            <?= $this->context->renderPartial('_share_view', ['share' => $share]); ?>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="idbStorageShareChange" style="z-index: 9999999;" tabindex="-1" role="dialog"
     aria-labelledby="idbStorageShareLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="idbStorageShareLabel">
                    <?= Translate::_('business', 'Share file') ?>
                </h3>
                <button type="button" style="top: 15px; right: 15px; position:absolute;" class="close"
                        data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="form">

            </div>
            <?php $form = ActiveForm::begin(
                [
                    'action' => Url::toRoute(['change-share'], true),
                    'options' => ['method' => 'post', 'id' => 'shareTemplate']
                ]
            ); ?>
            <div class="modal-body">
                <label>
                    <input type="checkbox" <?= empty($share) ? 'checked' : '' ?> id="share-everyone"
                           name="wholeVault"/>&nbsp;
                    <?= Translate::_(
                        'business',
                        'Share with everybody assigned to the vault'
                    ) ?>
                </label>

                <div id="select-people" <?= empty($share) ? 'style="display: none;"' : '' ?>>
                    <?= $form->field($model, 'people_user')->dropDownList(
                        [],
                        ['multiple' => 'multiple', 'name' => 'people_users[]', 'id' => 'change-share-input']
                    ) ?>
                    <div class="direct-chat-text">
                        <?= Translate::_(
                            'business',
                            'Leave empty if you want share with everybody assigned to the vault'
                        ) ?>
                    </div>
                </div>
                <input type="hidden" name="shareOid" value="<?= $oId ?>"/>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal"><?= Translate::_(
                        'business',
                        'Close'
                    ) ?></button>
                <button type="submit" class="btn btn-primary"><?= Translate::_('business', 'Save') ?></button>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

<?= BusinessConfig::jsOptions(
    [
        'getUsersUrl' => Url::toRoute(['get-users'], true),
        'shared' => json_encode($share),
        'preload' => empty($share) ? false : true
    ]
) ?>

