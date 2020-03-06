<?php

use app\assets\FrontAsset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;

$wizardAsset = IdbWizardAsset::register($this);
$frontAsset = FrontAsset::register($this);
$frontAsset->checkBrowser();
$this->title = Translate::_('business', 'Welcome to Identity Bank');

?>
<div class="container">
    <div class="container-inner">
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizard(
                    [
                        'Icon' => 'glyphicon-circle-arrow-right',
                        'Title' => Translate::_('business', 'Welcome')
                    ],
                    [
                        'Icon' => 'glyphicon-cog',
                        'Title' => Translate::_('business', 'Before we start')
                    ],
                    [
                        'Icon' => 'glyphicon-briefcase',
                        'Title' => Translate::_('business', 'Business Details')
                    ],
                    1
                ) ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <div class="sp-column">
                    <div class="sp-module">
                        <div class="sp-module-content">
                            <div style="text-align: center;">
                                <h2><b><?= Translate::_(
                                            'business',
                                            'Welcome and thank you for choosing to open an Identity Bank Business account!'
                                        ) ?></b></h2>
                            </div>
                            <hr style="border:0px;padding: 3px;">
                            <div style="text-align: center;">
                                <h2 style="text-align: center;">
                                    <?= Translate::_(
                                        'business',
                                        'Account setup will take about 5 minutes to complete.'
                                    ) ?>
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
                        'Text' => Translate::_('business', 'Start account signup'),
                        'Action' => ((empty($id))
                            ? ['/signup/before-we-start']
                            : [
                                '/signup/register/before-we-start',
                                'id' => $id
                            ]),
                        'Help' => Translate::_('business', 'Continue')
                    ]
                ) ?>
            </div>
        </div>
    </div>
</div>
