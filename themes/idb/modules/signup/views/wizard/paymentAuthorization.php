<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$assetBundle->layoutForms($this);
$wizardAsset = IdbWizardAsset::register($this);
$this->title = Translate::_('business', 'Payment Authorization');

?>

<div class="container">
    <div class="container-inner">

        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12" style="float: none;margin: 0 auto; margin-bottom: 16px;">
                    <?= $wizardAsset->generateWizard(
                        [
                            'Icon' => 'glyphicon-th-list',
                            'Title' => Translate::_('business', 'Choose package')
                        ],
                        [
                            'Icon' => 'glyphicon-euro',
                            'Title' => Translate::_('business', 'Payment')
                        ],
                        [
                            'Icon' => 'glyphicon-eye-open',
                            'Title' => Translate::_('business', 'Auth')
                        ],
                        2
                    ) ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <div class="sp-column">
                    <div class="sp-module">
                        <div class="sp-module-content">
                            <div style="text-align: center;">
                                <h2>
                                    <b>
                                        <?= Translate::_(
                                            'business',
                                            'Are you authorized to make payments on behalf of your company?'
                                        )
                                        ?>
                                    </b>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizardActions(
                    [
                        'Text' => Translate::_('business', 'Yes, I am authorized to make payments'),
                        'Action' => ['/signup/billing', 'id' => $id, 'paymentType' => 'payment'],
                        'Help' => Translate::_('business', 'Continue')
                    ],
                    [
                        'Text' => Translate::_('business', 'No, a colleague will make the payment'),
                        'Action' => ['/signup/billing', 'id' => $id, 'paymentType' => 'other'],
                        'backButtonAction' => true,
                        'ForceClass' => 'btn btn-lg mid-margin-right wizard-prev pull-left btn-warning'
                    ]
                ) ?>
            </div>
        </div>

    </div>
</div>
