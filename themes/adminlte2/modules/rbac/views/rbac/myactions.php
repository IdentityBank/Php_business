<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\grid\GridView;
use yii\helpers\Html;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'My Actions')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?= GridView::widget(
                            [
                                'dataProvider' => $provider,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'task',
                                    'access',
                                ],
                            ]
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
