<?php

use app\assets\DataTableAsset;
use app\assets\IdbDataAsset;
use app\helpers\ReturnUrl;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$dataTableAsset = DataTableAsset::register($this);
$dataAssets = IdbDataAsset::register($this);
$dataAssets->showAllAssets();

$url = ReturnUrl::generateUrl();

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode($this->title) ?>
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
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                        </div>
                        <div class="box-header with-border">
                            <?= Html::a(
                                '<i class="fa fa-user-plus"></i>' . Translate::_('business', 'Create'),
                                ['create'],
                                ['class' => 'btn btn-app']
                            ) ?>
                        </div>

                        <?php Pjax::begin(); ?>

                        <?= GridView::widget(
                            [
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'name:ntext',
                                    'created_at',

                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{delete}',
                                        'buttons' => [

                                            'delete' => function ($url, $model) {

                                                $aid = Yii::$app->user->identity->aid;

                                                if ($model->aid === $aid) {
                                                    return '';
                                                }

                                                return Yii::$app->controller->renderPartial(
                                                    '@app/themes/adminlte2/views/site/_modalWindow',
                                                    [
                                                        'modal' => [
                                                            'name' => 'cancelFormActionModal',
                                                            'header' => Translate::_('business', 'Delete account'),
                                                            'body' => Translate::_(
                                                                'business',
                                                                'You have chosen to delete an account. This change is irreversible.'
                                                            ),
                                                            'question' => Translate::_(
                                                                'business',
                                                                'If this is not your intention, please click on \'Continue\'.'
                                                            ),
                                                            'button' => [
                                                                'label' => '<span class="glyphicon glyphicon-trash"></span>',
                                                                'class' => 'btn-app-trash'
                                                            ],
                                                            'leftButton' => [
                                                                'label' => Translate::_('business', 'Delete'),
                                                                'action' => Url::toRoute(
                                                                    [
                                                                        '/account-manager/delete',
                                                                        'aid' => $model->aid,
                                                                        'oid' => $model->oid
                                                                    ],
                                                                    true
                                                                )
                                                            ],
                                                            'rightButton' => [
                                                                'label' => Translate::_('business', 'Continue'),
                                                                'style' => 'btn btn-success',
                                                                'action' => 'data-dismiss'
                                                            ],
                                                        ]
                                                    ]
                                                );
                                            },
                                        ]

                                    ],
                                ],
                            ]
                        ); ?>

                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
