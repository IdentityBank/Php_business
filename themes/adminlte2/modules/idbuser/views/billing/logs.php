<?php

use app\helpers\Translate;
use yii\grid\GridView;
use yii\helpers\Html;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'User activity logs')) ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-widget bg-gray-light">
                    <div class="box-body">
                        <?= GridView::widget(
                            [
                                'dataProvider' => $logs,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    [
                                        'attribute' => 'action_date',
                                        'label' => Translate::_('business', 'Action Date')
                                    ],
                                    [
                                        'attribute' => 'action_name',
                                        'label' => Translate::_('business', 'Action Name')
                                    ],
                                    [
                                        'attribute' => 'action_type',
                                        'label' => Translate::_('business', 'Action Type')
                                    ],
                                    [
                                        'attribute' => 'cost',
                                        'label' => Translate::_('business', 'Cost')
                                    ],
                                    [
                                        'attribute' => 'credits_before',
                                        'label' => Translate::_('business', 'Credits Before')
                                    ],
                                    [
                                        'attribute' => 'additional_credits_before',
                                        'label' => Translate::_('business', 'Additional Credits Before')
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
