<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\idb\assets\ImportWizardAsset;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;

$wizardAsset = ImportWizardAsset::register($this);
?>

<div class="content-wrapper">
    <?= FlashMessage::widget(
        [
            'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success') : null,
            'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : null,
            'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : null,
        ]
    ); ?>

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Create or select a vault for your data')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizard(
                    [
                        'Icon' => 'glyphicon-open',
                        'Title' => Translate::_('business', 'Select a vault')
                    ],
                    [
                        'Icon' => 'glyphicon-open',
                        'Title' => Translate::_('business', 'Select a file to import')
                    ],
                    [
                        'Icon' => 'glyphicon-file',
                        'Title' => Translate::_('business', 'Select worksheet')
                    ],
                    0
                ) ?>
            </div>
        </div>
        <h4><?= Translate::_(
                'business',
                'Select an existing vault or create a new one.'
            ); ?></h4>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?= Html::beginForm([''], 'post'); ?>
                        <div class="form-group">
                            <div class="input-group" style="height: 50px;">
                                    <span class="input-group-addon">
                                        <?= Html::input('radio', 'dbid', 'new', ['checked' => 'checked']); ?>
                                    </span>
                                <label class="form-control" style="height: 50px;">
                                    <?= Html::textInput(
                                        'dbname',
                                        null,
                                        [
                                            'placeholder' => Translate::_('business', 'Type a new vault name'),
                                            'class' => 'form-control'
                                        ]
                                    ); ?>
                                </label>
                            </div>
                            <br style="display:block;margin: 2px 0;line-height:1px;">
                            <?php foreach ($dbsArray as $dbid => $name): ?>
                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <?= Html::input('radio', 'dbid', $dbid, ['id' => $dbid]); ?>
                                        </span>
                                    <?= Html::label($name, $dbid, ['class' => 'form-control']); ?>
                                </div><br style="display:block;margin: 2px 0;line-height:1px;">
                            <?php endforeach; ?>
                        </div>
                        <?= Html::submitButton(
                            Translate::_("business", "Continue"),
                            ['class' => 'btn btn-primary pull-right']
                        ); ?>
                        <?= Html::endForm(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
