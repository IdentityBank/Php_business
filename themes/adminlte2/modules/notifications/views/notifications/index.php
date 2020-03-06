<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BusinessNotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Notifications Manager')) ?>
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
                            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="business-notification-index">

                                <?= GridView::widget(
                                    [
                                        'dataProvider' => $dataProvider,
                                        'filterModel' => $searchModel,
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],

                                            'id',
                                            'uid',
                                            'issued_at',
                                            'expires_at',
                                            'data:ntext',
                                            [
                                                'attribute' => 'type',
                                                'filter' => ['green' => 'Green', 'amber' => 'Amber', 'red' => 'Red']

                                            ],
                                            [
                                                'attribute' => 'status',
                                                'filter' => [0 => 'off', 1 => 'on']
                                            ],

                                            ['class' => 'yii\grid\ActionColumn'],
                                        ],
                                    ]
                                ); ?>
                            </div>
                            <div class="box-body">
                            </div>
                        </div>
                    </div>
                </div>
    </section>
</div>
