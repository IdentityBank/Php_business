<?php

use app\helpers\Translate;
use yii\helpers\Html;

$business_dpo_address = [
    Translate::_('business', "DPO email") => ($data['dpoEmail'] ?? null),
    Translate::_('business', "DPO mobile") => ($data['dpoMobile'] ?? null),
    Translate::_('business', "DPO address") => ($data['dpoAddress'] ?? null),
    Translate::_('business', "DPO Other") => ($data['dpoOther'] ?? null),
];

$business_billing_information = [
    Translate::_('business', "Terms and condition") => ($data['dpoTermsAndCondition'] ?? null),
    Translate::_('business', "Data processing agreements") => ($data['dpoDataProcessingAgreements'] ?? null),
    Translate::_('business', "Privacy Notice") => ($data['dpoPrivacyNotice'] ?? null),
    Translate::_('business', "Cookie policy") => ($data['dpoCookiePolicy'] ?? null),
];

?>

<div class="tab-pane" id="dpo">
    <div class="box-body">
        <?php if (
            Yii::$app->user->can('action_organization_billing_manager')
            or Yii::$app->user->can('action_account_manager')
        ) : ?>
            <?= Html::a(
                '<i class="fa fa-user-edit"></i> ' . Translate::_('business', 'Edit data'),
                ['/idbuser/profile/edit-dpo-information'],
                ['class' => 'btn btn-app']
            ) ?>
        <?php endif; ?>
        <hr>
        <strong><i class="fa fa-user-shield margin-r-5"></i> <?= Translate::_('business', 'Data Protection Officer contact information') ?></strong>
        <hr>
        <p>
        <div class="box box-widget widget-user-2">
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <?php foreach ($business_dpo_address as $itemKey => $itemValue) : ?>
                        <li>
                            <a>
                                <strong><?= $itemKey ?></strong>
                                <span class="pull-right"><?= $itemValue ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <hr>
        <p>
            <strong><i class="fa fa-file-contract margin-r-5"></i> <?= Translate::_('business', 'Privacy conditions') ?></strong>
        <hr>
        <div class="box box-widget widget-user-2">
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <?php foreach ($business_billing_information as $itemKey => $itemValue) : ?>
                        <li>
                            <a>
                                <strong><?= $itemKey ?></strong>
                                <span class="pull-right"><?= $itemValue ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        </p>
    </div>
</div>
