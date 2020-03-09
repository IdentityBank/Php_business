<?php

use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\helpers\FileHelper;
use idbyii2\helpers\Translate;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var \yii\data\ActiveDataProvider $dataProvider */
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Manage exports')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">

                        <?= GridView::widget(
                            [
                                'dataProvider' => $dataProvider,
                                'showHeader' => true,
                                'columns' => [
                                    'file_name:ntext',
                                    [
                                        'attribute' => 'created_at',
                                        'value' => function ($data) {
                                            if (!is_null($data->created_at)) {
                                                return date('d-m-Y H:i', strtotime($data->created_at));
                                            } else {
                                                return '';
                                            }
                                        },
                                    ],
                                    [
                                        'attribute' => 'downloaded_at',
                                        'value' => function ($data) {
                                            if (!is_null($data->downloaded_at)) {
                                                return date('d-m-Y H:i', strtotime($data->downloaded_at));
                                            } else {
                                                return Translate::_('business', 'Not yet downloaded');
                                            }
                                        },
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'value' => function ($data) {
                                            switch ($data->status) {
                                                case FileHelper::STATUS_ADDED:
                                                    return Translate::_('business', 'Being prepared for download');
                                                case FileHelper::STATUS_TO_DOWNLOAD:
                                                    return Translate::_('business', 'Available for download');
                                                case FileHelper::STATUS_DOWNLOADED:
                                                    return Translate::_('business', 'Already downloaded');
                                            }

                                            return '';
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{download}',
                                        'buttons' => [
                                            'download' => function (
                                                $url,
                                                $data,
                                                $key
                                            ) {     // render your custom button
                                                if (!empty($data->url)) {
                                                    $url = json_decode($data->url, true);

                                                    return Html::a(
                                                        '<i class="glyphicon glyphicon-download"></i> ' . Translate::_(
                                                            'business',
                                                            'Download now'
                                                        ),
                                                        [$url[0], 'id' => $url['id']],
                                                        ['download' => true]

                                                    );

                                                } else {
                                                    return '<i class="glyphicon glyphicon-check"></i>';
                                                }
                                            },
                                        ]
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{delete}',
                                        'contentOptions' => ['style' => 'width: 100px;'],
                                        'buttons' => [
                                            'delete' => function ($url, $model, $key) {     // render your custom button
                                                return Html::a(
                                                    '<i class="glyphicon glyphicon-trash"></i>',
                                                    ['export/delete'],
                                                    [
                                                        'data' => [
                                                            'method' => 'post',
                                                            'confirm' => Translate::_(
                                                                'business',
                                                                'Are you sure you want to delete this item?'
                                                            ),
                                                            'params' => [
                                                                'id' => $model['id']
                                                            ],
                                                        ],
                                                    ]
                                                );
                                            },
                                        ]
                                    ]
                                ],
                            ]
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>

    setTimeout(function () {
        window.location.replace(window.location.href);
    }, 30000);
</script>
