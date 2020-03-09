<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;
use yii\helpers\Html;
use yii\helpers\Url;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$assetBundle->layoutForms($this);
$wizardAsset = IdbWizardAsset::register($this);
$this->title = Translate::_('business', 'Subscription Payment');

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

        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <div class="sp-column">
                    <div class="sp-module">
                        <div class="sp-module-content" style="color: black;">
                            <div class="invoice">
                                <div class="row">
                                    <div class="col-xs-12 signup-text-color">
                                        <h4><?= Translate::_('business', 'Payment Cancelled') ?>!</h4>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h2 class="page-header"></h2>
                                            </div>
                                        </div>
                                        <h4><?= Translate::_(
                                                'business',
                                                'The payment process has been cancelled. If you would like to try again click on the continue button below.'
                                            ) ?></h4>
                                        <?= Html::a(
                                            Translate::_('business', 'Click to retry payment'),
                                            Url::toRoute(['billing', 'id' => $id], true),
                                            ['class' => 'btn btn-warning btn-lg btn-block']
                                        ) ?>
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
