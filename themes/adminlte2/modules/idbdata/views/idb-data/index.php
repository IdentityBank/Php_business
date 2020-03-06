<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'IDB Data')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Translate::_('business', 'Manage IDB data') ?></h3>
                        </div>
                        <div style="display: flex;">
                            <div class="box-body">
                                <?php echo Html::a(
                                    '<i class="fa fa-database"></i>' . Translate::_('business', 'Data Types'),
                                    ['/idbdata/data-types'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            </div>
                            <div class="box-body">
                                <?php echo Html::a(
                                    '<i class="fa fa-database"></i>' . Translate::_('business', 'Data Attributes'),
                                    ['/idbdata/data-attributes'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            </div>
                            <div class="box-body">
                                <?php echo Html::a(
                                    '<i class="fa fa-database"></i>' . Translate::_(
                                        'business',
                                        'Data Additional Attributes'
                                    ),
                                    ['/idbdata/data-additional-attributes'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            </div>
                            <div class="box-body">
                                <?php echo Html::a(
                                    '<i class="fa fa-database"></i>' . Translate::_('business', 'Data Sets'),
                                    ['/idbdata/data-sets'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            </div>
                            <div class="box-body">
                                <?php echo Html::a(
                                    '<i class="fa fa-database"></i>' . Translate::_('business', 'Data Set Objects'),
                                    ['/idbdata/data-set-objects'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            </div>
                            <div class="box-body">
                                <?php echo Html::a(
                                    '<i class="fa fa-database"></i>' . Translate::_('business', 'Client Sets Object'),
                                    ['/idbdata/data-client-sets'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            </div>
                            <?php if ($metadataIsSet): ?>
                                <div class="box-body">
                                    <?php echo Html::a(
                                        '<i class="fa fa-database"></i>' . Translate::_('business', 'Client Data '),
                                        ['/idbdata/idb-data/show-all'],
                                        ['class' => 'btn btn-app']
                                    ) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
