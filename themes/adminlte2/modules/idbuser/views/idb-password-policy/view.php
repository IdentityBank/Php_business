<?php

use app\assets\AdminLte2AppAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\DetailView;

AdminLte2AppAsset::register($this);
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
                    <div class="box-header with-border">
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
                        <h3 class="box-title"><?= strtoupper($model->name) ?></h3>
                    </div>

                    <?php if (
                        Yii::$app->user->can('password_policy_create') || Yii::$app->user->can('password_policy_update')
                        || Yii::$app->user->can('password_policy_delete')
                    ) : ?>
                        <div class="box-header with-border">
                            <?php if (Yii::$app->user->can('password_policy_create'))
                                echo Html::a(
                                    '<i class="fa fa-plus-circle"></i>' . Translate::_('business', 'Create similar'),
                                    ['create', 'id' => $model->name],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            <?php if (Yii::$app->user->can('password_policy_update'))
                                echo Html::a(
                                    '<i class="fa fa-edit"></i>' . Translate::_('business', 'Update'),
                                    ['update', 'id' => $model->name],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            <?php if (Yii::$app->user->can('password_policy_delete'))
                                echo Html::a(
                                    '<i class="glyphicon glyphicon-trash"></i>' . Translate::_('business', 'Delete'),
                                    ['delete', 'id' => $model->name],
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
                    <?php endif; ?>

                    <div class="box-body">
                        <?= DetailView::widget(
                            [
                                'model' => $model,
                                'attributes' => [
                                    'name',
                                    'lowercase',
                                    'uppercase',
                                    'digit',
                                    'special',
                                    'special_chars_set',
                                    'min_types',
                                    'reuse_count',
                                    'min_recovery_age',
                                    'max_age',
                                    'min_length',
                                    'max_length',
                                    'change_initial',
                                    'level',
                                ],
                            ]
                        ) ?>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
