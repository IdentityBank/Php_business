<?php

use app\helpers\Translate;
use yii\helpers\Html;

$business_informations_address = [
    Translate::_('business', "Address Line 1") => ($data['addressLine1'] ?? null),
    Translate::_('business', "Address Line 2") => ($data['addressLine2'] ?? null),
    Translate::_('business', "City") => ($data['city'] ?? null),
    Translate::_('business', "Region") => ($data['region'] ?? null),
    Translate::_('business', "Postcode") => ($data['postcode'] ?? null),
    Translate::_('business', "Country") => ($data['country'] ?? null),
];

$business_billing_information = [
    Translate::_('business', "Your VAT number") => ($data['vat'] ?? null),
    Translate::_('business', "Company Registration Number") => ($data['registrationNumber'] ?? null),
];

?>

<div class="tab-pane" id="information">
    <div class="box-body">
        <?php if (
            Yii::$app->user->can('action_organization_billing_manager')
            or Yii::$app->user->can('action_account_manager')
        ) : ?>
            <?= Html::a(
                '<i class="fa fa-edit"></i> ' . Translate::_('business', 'Edit data'),
                ['/idbuser/profile/edit-business-information'],
                ['class' => 'btn btn-app']
            ) ?>
        <?php endif; ?>
        <h4><strong><i class="fa fa-tag margin-r-5"></i> <?= $data['name'] ?></strong></h4>
        <hr>
        <strong><i class="fa fa-money margin-r-5"></i> <?= Translate::_('business', 'Billing information') ?></strong>
        <hr>
        <p>
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
        <hr>
        <p>
            <strong><i class="fa fa-map-marker margin-r-5"></i> <?= Translate::_('business', 'Address') ?></strong>
        <hr>
        <div class="box box-widget widget-user-2">
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <?php foreach ($business_informations_address as $itemKey => $itemValue) : ?>
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
