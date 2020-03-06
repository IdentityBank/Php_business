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
$wizardAsset->paymentsAssets();
$this->title = Translate::_('business', 'Payment successful!');

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
                        3
                    ) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <div class="sp-column">
                    <div class="sp-module">
                        <div class="sp-module-content" style="color: black;">
                            <div class="invoice">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4 class="page-header  signup-text-color"><?= Translate::_(
                                                'business',
                                                'Thank you for your payment!'
                                            ) ?> </h4>
                                        <h5 class="signup-text-color"><?= Translate::_(
                                                'business',
                                                'An email has now been sent to the person who sent you this payment request saying they can now complete the signup process.'
                                            ) ?></h5>
                                        <h5 class="signup-text-color"><?= Translate::_(
                                                'business',
                                                'There is no further action you need to take.'
                                            ) ?></h5>
                                        <h5 class="signup-text-color"><?= Translate::_(
                                                'business',
                                                'You can now close your browser window.'
                                            ) ?></h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

