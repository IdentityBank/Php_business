<?php

use app\helpers\ReturnUrl;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\models\db\RolesModel;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel idbyii2\models\db\RolesModelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$url = ReturnUrl::generateUrl();

?>
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'RBAC')) ?>
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
                        <h3 class="box-title">
                            <i class="fa fa-tasks"></i>&nbsp;<?= Html::encode($this->title) ?>
                        </h3>
                    </div>

                    <div class="box-header with-border">
                        <?= Html::a(
                            '<i class="fa fa-user-plus"></i>' . Translate::_('business', 'Create'),
                            Url::toRoute(['create'], true),
                            ['class' => 'btn btn-app']
                        ) ?>
                    </div>

                    <div class="box-body">

                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                        <?= GridView::widget(
                            [
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'name',
                                    [
                                        'attribute' => 'type',
                                        'filter' => RolesModel::getTypes(),
                                        'value' => function ($model) {
                                            return $model->getTypeName();
                                        }
                                    ],

                                    'description:ntext',
                                    //'rule_name',
                                    //'data',
                                    //'created_at',
                                    //'updated_at',

                                    ['class' => 'yii\grid\ActionColumn'],
                                ],
                            ]
                        ); ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
