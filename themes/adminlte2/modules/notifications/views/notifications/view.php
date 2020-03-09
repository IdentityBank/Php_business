<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model idbyii2\models\db\BusinessNotification */

?>
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Details')) ?>
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
                        <div class="business-notification-view">

                            <div class="form-group">
                                <?= Yii::$app->controller->renderPartial(
                                    '@app/themes/adminlte2/views/site/_modalWindow',
                                    [
                                        'modal' => [
                                            'name' => 'cancelFormActionModal',
                                            'header' => Translate::_('business', 'Stop edit your data'),
                                            'body' => Translate::_(
                                                'business',
                                                'You have chosen to stop the edit your data task, your changes will not be saved'
                                            ),
                                            'question' => Translate::_(
                                                'business',
                                                'If this is not your intention, please click on \'Continue\'.'
                                            ),
                                            'button' => [
                                                'label' => Translate::_(
                                                    'business',
                                                    'Cancel'
                                                ),
                                                'class' => 'btn btn-back'
                                            ],
                                            'leftButton' => [
                                                'label' => Translate::_('business', 'Stop'),
                                                'action' => Yii::$app->session->get('urlRedirect'),
                                                'style' => 'btn btn-back',
                                            ],
                                            'rightButton' => [
                                                'label' => Translate::_('business', 'Continue'),
                                                'style' => 'btn btn-primary',
                                                'action' => 'data-dismiss'
                                            ],
                                        ]
                                    ]
                                ); ?>
                            </div>

                            <h1><?= Html::encode($this->title) ?></h1>

                            <?= Html::a(
                                '<i class="fa fa-edit"></i>' . Translate::_('business', 'Update'),
                                ['update', 'id' => $model->id],
                                ['class' => 'btn btn-app']
                            ) ?>
                            <?= Html::a(
                                '<i class="glyphicon glyphicon-trash"></i>' . Translate::_('business', 'Delete'),
                                ['delete', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-app btn-app-trash',
                                    'data' =>
                                        [
                                            'confirm' => Translate::_(
                                                'business',
                                                'Are you sure you want to delete this item?'
                                            ),
                                            'method' => 'post',
                                        ],
                                ]
                            ) ?>
                        </div>


                        <?= DetailView::widget(
                            [
                                'model' => $model,
                                'attributes' => [
                                    'id',
                                    'uid',
                                    'issued_at',
                                    'expires_at',
                                    'data:ntext',
                                    'type:ntext',
                                    'status',
                                ],
                            ]
                        ) ?>

                    </div>
                </div>
            </div>
        </div>
</div>
</section>
</div>
