<?php

use app\helpers\ReturnUrl;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\widgets\FlashMessage;
use yii\grid\GridView;
use yii\helpers\Html;

$url = ReturnUrl::generateUrl();

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Configuration: send information')) ?>
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
                        <?= GridView::widget(
                            [
                                'dataProvider' => $provider,
                                'columns' => [
                                    'name',
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '{config}',
                                        'buttons' => [
                                            'config' => function ($url, $model, $key) {
                                                if ($model['dbid'] === Yii::$app->session->get('currentDb')) {
                                                    $class = "btn btn-primary";
                                                } else {
                                                    $class = "btn btn-default";
                                                }

                                                return Html::a(
                                                    '<span class="' . $class
                                                    . '"><b class="glyphicon glyphicon-cog"></b></span>',
                                                    [
                                                        '/configuration/configuration/switcher',
                                                        'dbid' => $model['dbid']
                                                    ]
                                                );
                                            },

                                        ]
                                    ]
                                ]
                            ]
                        ) ?>
                        <span class="btn btn-primary"><b class="glyphicon glyphicon-cog"></b></span> - <?= Translate::_(
                            'business',
                            'Currently selected vault'
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
