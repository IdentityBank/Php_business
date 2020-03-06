<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'People change requests')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>


    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <?php Pjax::begin(); ?>
                <?= GridView::widget(
                    [
                        'dataProvider' => $provider,
                        'showHeader' => true,
                        'columns' => [
                            'people_id:ntext',
                            'new_data:ntext',
                            'old_data:ntext',
                            'created_at:ntext',
                            'status:ntext',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{verify} {reverse} {message} {delete}',
                                'contentOptions' => ['style' => 'width: 100px;'],
                                'buttons' => [
                                    'verify' => function ($url, $model, $key) {     // render your custom button
                                        return Html::a(
                                            '<b class="fa fa-thumbs-up"></b>',
                                            '/change-request/verify',
                                            [
                                                'data' => [
                                                    'method' => 'post',
                                                    'params' => [
                                                        'peopleId' => $model['people_id']
                                                    ],
                                                ],
                                            ]
                                        );
                                    },
                                    'reverse' => function ($url, $model, $key) {     // render your custom button
                                        return Html::a(
                                            '<b class="fa fa-close"></b>',
                                            '/change-request/reverse',
                                            [
                                                'data' => [
                                                    'method' => 'post',
                                                    'params' => [
                                                        'peopleId' => $model['people_id']
                                                    ],
                                                ],
                                            ]
                                        );
                                    },
                                    'message' => function ($url, $model, $key) {     // render your custom button
                                        return Html::a(
                                            '<b class="fa fa-pencil-square-o"></b>',
                                            ['/btpmessages/create']
                                        );
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Yii::$app->controller->renderPartial(
                                            '@app/themes/adminlte2/views/site/_modalWindow',
                                            [
                                                'modal' => [
                                                    'name' => 'cancelFormActionModal',
                                                    'header' => Translate::_(
                                                        'business',
                                                        'Delete change request from people portal'
                                                    ),
                                                    'body' => Translate::_(
                                                        'business',
                                                        'You have chosen to delete a change request from one your business clients. This change is irreversible.'
                                                    ),
                                                    'question' => Translate::_(
                                                        'business',
                                                        'If this is not your intention, please click on \'Continue\'.'
                                                    ),
                                                    'button' => [
                                                        'label' => '<span class="glyphicon glyphicon-trash"></span>',
                                                        'class' => ''
                                                    ],
                                                    'leftButton' => [
                                                        'label' => Translate::_('business', 'Delete'),
                                                        'action' => Url::toRoute(
                                                            ['/change-request/delete', 'id' => $model['people_id']],
                                                            true
                                                        ),
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
                            ]
                        ],
                    ]
                ); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </section>
</div>
