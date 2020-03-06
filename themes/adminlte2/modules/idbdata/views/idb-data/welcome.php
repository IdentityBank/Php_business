<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Data types')) ?>
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
                        <h3 class="box-title"><?= Translate::_('business', 'Manage IDB data') ?></h3>
                    </div>
                    <div style="display: flex;">
                        <div class="box-body">
                            <?php echo Html::a(
                                '<i class="fa fa-database"></i>' . Translate::_('business', 'Import'),
                                ['/tools/upload'],
                                ['class' => 'btn btn-app']
                            ) ?>
                        </div>
                        <div class="box-body">
                            <?php echo Html::a(
                                '<i class="fa fa-database"></i>' . Translate::_('business', 'Create Data Sets'),
                                ['/idbdata/data-client-sets/create'],
                                ['class' => 'btn btn-app']
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
