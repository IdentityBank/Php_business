<?php

use app\assets\FrontAsset;
use app\helpers\Translate;
use idbyii2\widgets\FlashMessage;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$uploadAssets = FrontAsset::register($this);

?>
<section class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Translate::_('business', 'GDPR Options') ?>
        </h1>
    </section>

    <section class="content">
        <?= FlashMessage::widget(
            [
                'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash(
                    'success'
                ) : null,
                'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error')
                    : null,
                'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info')
                    : null,
            ]
        ); ?>
        <div class="box">

            <div class="box-body">
                <div class="col-md-12">
                    <?php if (
                        Yii::$app->user->can('action_organization_billing_manager')
                        or Yii::$app->user->can('action_account_manager')
                    ) : ?>
                        <div style="padding-top: 15px;">
                        <?= Html::a(
                            '<i class="fa fa-user-clock"></i> ' . Translate::_('business', 'Edit Retention Period'),
                            ['/gdpr/edit-period'],
                            ['class' => 'btn btn-app']
                        ) ?>

                        <?= Html::a(
                            '<i class="fa fa-user-shield"></i> ' . Translate::_('business', 'Edit Lawful Basis'),
                            ['/gdpr/edit-basis'],
                            ['class' => 'btn btn-app']
                        ) ?>

                        <?= Html::a(
                            '<i class="fa fa-user-tie"></i> ' . Translate::_('business', 'Edit Data Processors'),
                            ['/gdpr/edit-processors'],
                            ['class' => 'btn btn-app']
                        ) ?>
                        </div>
                    <?php endif; ?>
                    <hr>
                    <strong><i class="fa fa-user-shield margin-r-5"></i> <?= Translate::_('business', 'Retention period') ?>
                    </strong>
                    <hr>
                    <p>
                    <div class="box box-widget widget-user-2">
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <li>
                                    <a>
                                        <strong><?= Translate::_('business', 'Minimum days:') ?></strong>
                                        <span class="pull-right">
                                            <?= htmlspecialchars(ArrayHelper::getValue(
                                                $gdpr,
                                                'dataTypes.' . array_key_first($gdpr['dataTypes']) . '.minimum',
                                                Translate::_('business', 'not set')
                                            )); ?>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <strong><?= Translate::_('business', 'Maximum days:') ?></strong>
                                        <span class="pull-right">
                                            <?= htmlspecialchars(ArrayHelper::getValue(
                                                $gdpr,
                                                'dataTypes.' . array_key_first($gdpr['dataTypes']) . '.maximum',
                                                Translate::_('business', 'not set')
                                            )); ?>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <strong><?= Translate::_('business', 'On expiry:') ?></strong>
                                        <span class="pull-right">
                                            <?= htmlspecialchars(ArrayHelper::getValue(
                                                $gdpr,
                                                'dataTypes.' . array_key_first($gdpr['dataTypes']) . '.onExpiry',
                                                Translate::_('business', 'not set')
                                            )); ?>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <strong><?= Translate::_('business', 'Review cycle:') ?></strong>
                                        <span class="pull-right">
                                            <?= htmlspecialchars(ArrayHelper::getValue(
                                                $gdpr,
                                                'dataTypes.' . array_key_first($gdpr['dataTypes']) . '.reviewCycle',
                                                Translate::_('business', 'not set')
                                            )); ?>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <strong><?= Translate::_('business', 'Review cycle explanation:') ?></strong>
                                        <span class="pull-right">
                                            <?= htmlspecialchars(ArrayHelper::getValue(
                                                $gdpr,
                                                'dataTypes.' . array_key_first($gdpr['dataTypes']) . '.explanation',
                                                Translate::_('business', 'not set')
                                            )); ?>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <strong><i class="fa fa-user-clock margin-r-5"></i> <?= Translate::_('business', 'Lawful basis') ?>
                    </strong>
                    <hr>
                    <p>
                    <div class="box box-widget widget-user-2">
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <li>
                                    <a>
                                        <strong><?= Translate::_('business', 'Lawful basis') ?></strong>
                                        <span class="pull-right">
                                            <?= htmlspecialchars(ArrayHelper::getValue(
                                                $gdpr,
                                                'dataTypes.' . array_key_first($gdpr['dataTypes']) . '.lawfulBasis',
                                                Translate::_('business', 'not set')
                                            )); ?>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <strong><?= Translate::_('business', 'Lawful message:') ?></strong>
                                        <span class="pull-right">
                                            <?= htmlspecialchars(ArrayHelper::getValue(
                                                $gdpr,
                                                'dataTypes.' . array_key_first($gdpr['dataTypes']) . '.lawfulMessage',
                                                Translate::_('business', 'not set')
                                            )); ?>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <strong><?= Translate::_('business', 'Purpose limitation:') ?></strong>
                                        <span class="pull-right">
                                            <?= htmlspecialchars(ArrayHelper::getValue(
                                                $gdpr,
                                                'dataTypes.' . array_key_first($gdpr['dataTypes']) . '.purposeLimitation',
                                                Translate::_('business', 'not set')
                                            )); ?>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <strong><i class="fa fa-user-tie margin-r-5"></i> <?= Translate::_('business', 'Data processors:') ?>
                    </strong>
                    <hr>
                    <div class="box box-widget widget-user-2">
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <?php if(!empty($gdpr['dataTypes'][array_key_first($gdpr['dataTypes'])]['listDataProcessors'])): ?>
                                        <?php $counter = 1; ?>
                                        <?php foreach($gdpr['dataTypes'][array_key_first($gdpr['dataTypes'])]['listDataProcessors'] as $processor): ?>
                                        <li>
                                            <a>
                                                <strong><?= $counter ?></strong>
                                                <span class="pull-right"> <?= htmlspecialchars($processor) ?> </span>
                                            </a>
                                        </li>
                                        <?php $counter++; ?>
                                        <?php endforeach; ?>
                                <?php else: ?>
                                    <li>
                                        <a>
                                            <strong></strong>
                                            <span class="pull-right"> <?= Translate::_('business', 'not set') ?> </span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <hr>

                </div>
            </div>
        </div>
    </section>

</section>
