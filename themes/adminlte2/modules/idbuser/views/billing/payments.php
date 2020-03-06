<?php

use app\helpers\Translate;
use idbyii2\widgets\FlashMessage;
use yii\grid\GridView;
use yii\helpers\Html;

?>
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Payments')) ?>
        </h1>
    </section>

    <?= FlashMessage::widget(
        [
            'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success') : null,
            'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : null,
            'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : null,
        ]
    ); ?>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-widget bg-gray-light">
                    <div class="box-body">
                        <?= GridView::widget(
                            [
                                'dataProvider' => $payments,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'attribute' => 'timestamp',
                                        'label' => Translate::_('business', 'Timestamp')
                                    ],
                                    [
                                        'attribute' => 'payment_method',
                                        'label' => Translate::_('business', 'Payment Method'),
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $payment_data = $model["payment_method"];
                                            $payment_data = json_decode($payment_data, true);
                                            if ($payment_data['type'] === 'sepadirectdebit') {
                                                return "SEPA/Direct Debit";
                                            } elseif ($payment_data['type'] === 'scheme') {
                                                return "Credit Card";
                                            } else {
                                                return "Unknown payment method";
                                            }
                                        }
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'label' => Translate::_('business', 'Status')
                                    ],
                                    [
                                        'attribute' => 'amount',
                                        'label' => 'Amount'
                                    ],
                                    [
                                        'attribute' => 'downloads',
                                        'label' => Translate::_('business', 'Downloads')
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{download}',
                                        'buttons' => [
                                            'download' => function ($url, $model) {
                                                return Html::a(
                                                    Translate::_('business', 'Download invoice'),
                                                    [
                                                        '/billing/invoice',
                                                        'id' => $model['id']
                                                    ],
                                                    ['target' => '_blank', 'class' => 'box_button fl download_link']
                                                );
                                            }
                                        ],
                                    ],
                                ],
                            ]
                        ); ?>
                    </div>
                </div>
            </div>
    </section>
</div>
