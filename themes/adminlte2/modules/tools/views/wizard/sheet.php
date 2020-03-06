<?php

use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\idb\assets\ImportWizardAsset;
use idbyii2\helpers\FileHelper;
use idbyii2\helpers\Translate;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$wizardAsset = ImportWizardAsset::register($this);
/** @var \yii\data\ActiveDataProvider $dataProvider */
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Select a worksheet to import')) ?>
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
                        'Title' => Translate::_('business', 'Select a file to import')
                    ],
                    [
                        'Icon' => 'glyphicon-file',
                        'Title' => Translate::_('business', 'Select worksheet')
                    ],
                    [
                        'Icon' => 'glyphicon-edit',
                        'Title' => Translate::_('business', 'Understand your data')
                    ],
                    1
                ) ?>
            </div>
        </div>
        <h4 style="text-align: center"><?= Html::encode(
                Translate::_(
                    'business',
                    'The file you have selected contains multiple worksheets. Select the worksheet you would like to import into your account.'
                )
            ); ?></h4>

        <?php if (!is_null($status) && $status === FileHelper::STATUS_CONVERTED): ?>
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <?= Html::beginForm(['/tools/wizard/worksheets', 'file' => $id], 'post'); ?>
                            <?php Pjax::begin(); ?>

                            <?= GridView::widget(
                                [
                                    'dataProvider' => $dataProvider,
                                    'showHeader' => true,
                                    'columns' => [
                                        [
                                            'class' => 'yii\grid\RadioButtonColumn',
                                            'radioOptions' => function ($model, $key) {
                                                if (
                                                    $model->status == FileHelper::STATUS_ADDED
                                                    || FileHelper::STATUS_ERROR
                                                ) {
                                                    return ['value' => $key];
                                                }

                                                return ['style' => ['display' => 'none']];
                                            }
                                        ],
                                        'file.file_path:ntext',
                                        'file.created_at:date',
                                        'name:ntext',
                                        'status:ntext',
                                    ],
                                ]
                            ); ?>
                            <?php Pjax::end(); ?>
                            <?= Html::submitButton(
                                Translate::_("business", "Continue"),
                                ['class' => 'btn btn-primary pull-right']
                            ); ?>
                            <?= Html::endForm(); ?>
                            <?= Html::a(
                                Translate::_("business", "Back"),
                                ['/tools/wizard/index'],
                                ['class' => 'btn btn-back pull-left']
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <h4 style="text-align: center"><?= Translate::_(
                                    "business",
                                    "Please wait until your file is converted into vaults."
                                ); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>
<script>
    setTimeout(function () {
        window.location.replace(window.location.href);
    }, 30000);
</script>
