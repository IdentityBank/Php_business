<?php

use app\helpers\Translate;
use yii\helpers\Html;

$business_contact = [
    Translate::_('business', "Firstname") => ($data['firstname'] ?? null),
    Translate::_('business', "Lastname") => ($data['lastname'] ?? null),
    Translate::_('business', "Initials") => ($data['initials'] ?? null),
    Translate::_('business', "Mobile") => ($data['mobile'] ?? null),
    Translate::_('business', "E-mail") => ($data['email'] ?? null),
];

?>

<div class="tab-pane" id="contact">
    <div class="box-body">
        <h4><strong><i class="fa fa-user margin-r-5"></i>
                <?= $business_contact[Translate::_('business', "Firstname")] ?>&nbsp;<?= $business_contact[Translate::_(
                    'business',
                    "Lastname"
                )] ?>
            </strong>
        </h4>
        <hr>
        <strong><i class="fa fa-envelope margin-r-5"></i> <?= Translate::_('business', 'Contact details') ?></strong>
        <hr>
        <p>
        <div class="box box-widget widget-user-2">
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <?php foreach ($business_contact as $itemKey => $itemValue) : ?>
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

        <?= Html::a(
            Translate::_('business', 'Change Email or Mobile number'),
            ['change-contact'],
            ['class' => 'btn btn-primary input-block-level form-control']
        ) ?>
        </p>
    </div>
</div>
