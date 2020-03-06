<?php

use app\helpers\Translate;
use idbyii2\widgets\LineChart;
use yii\helpers\Html;

if(!empty($businessPackage)){
    $creditsData =
        [
            Translate::_('business', "Start date") => $businessPackage[8],
            Translate::_('business', "End date") => $businessPackage[9],
            Translate::_('business', "Duration") => $businessPackage[7] . ' ' . Translate::_('business', "Months"),
            Translate::_('business', "Credits left") => $businessPackage[4],
            Translate::_('business', "Base credits") => $businessPackage[5],
            Translate::_('business', "Additional credits") => $businessPackage[6],
            Translate::_('business', "Last payment date") => $businessPackage[10],
            Translate::_('business', "Next payment date") => $businessPackage[11]
        ];
}

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Billing details')) ?>
        </h1>
    </section>

    <section class="content">

        <div class="box box-widget bg-gray-light" style="padding-left: 20px; padding-right: 20px;">
            <?php if (!empty($package)): ?>
                <?php
                $package = [
                    'id' => $package[0],
                    'name' => $package[4],
                    'currency' => $package[6],
                    'price' => $package[5],
                    'included' => $package[8],
                    'excluded' => $package[9],
                ];
                ?>
                <div class="box-header with-border">
                    <h3><?= Translate::_('business', "Subscription details") ?></h3>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-bookmark"></i></span>

                            <div class="info-box-content">
                            <span class="info-box-number">
                                <strong style="font-size: 30px;">
                                    <?= $package['name']; ?>
                                </strong>
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-money"></i></span>

                            <div class="info-box-content">
                            <span class="info-box-number">
                                <small style="font-size: 50px;">
                                    <sup><?= $package['currency']; ?></sup>
                                    <strong><?= $package['price']; ?></strong>
                                    <sub style="font-size: 15px;">/<?= 'Mo'; ?></sub>
                                </small>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="box-header with-border">
                <h3><?= Translate::_('business', "Credits") ?></h3>
            </div>
            <div class="row">
                <div class="box box-widget widget-user-2">
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            <?php if(!empty($creditsData)): ?>
                                <?php foreach ($creditsData as $itemKey => $itemValue) : ?>
                                    <li>
                                        <a>
                                            <strong><?= $itemKey ?></strong>
                                            <span class="pull-right"><?= $itemValue ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="box-header with-border">
                <h3><?= Translate::_('business', "Credits usage:") ?></h3>
            </div>
            <div class="row">
                <?= LineChart::widget(['data' => $creditsBurned, 'options' => ['style' => 'max-width: 800px']]) ?>
            </div>
        </div>

    </section>

</div>
