<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\helpers\Localization;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model idbyii2\models\db\RolesModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
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
                    <div class="box-header with-border">
                        <div class="form-group">
                            <?= Yii::$app->controller->renderPartial(
                                '@app/themes/adminlte2/views/site/_modalWindow',
                                [
                                    'modal' => [
                                        'name' => 'cancelFormActionModal',
                                        'header' => Translate::_('business', 'Cancel edit your data'),
                                        'body' => Translate::_(
                                            'business',
                                            'You have chosen to cancel the edit your data task, your changes will not be saved'
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
                                            'label' => Translate::_('business', 'Cancel'),
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
                        <h3 class="box-title">
                            <i class="fa fa-tasks"></i>&nbsp;<?= Html::encode($this->title) ?>
                        </h3>
                    </div>

                    <div class="box-header with-border">
                        <?= Html::a(
                            '<i class="fa fa-edit"></i>' . Translate::_('business', 'Update'),
                            Url::toRoute(['update', 'id' => $model->name], true),
                            ['class' => 'btn btn-app']
                        ) ?>
                        <?= Html::a(
                            '<i class="glyphicon glyphicon-trash"></i>' . Translate::_('business', 'Delete'),
                            Url::toRoute(['delete', 'id' => $model->name], true),
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

                    <div class="box-body">

                        <?= DetailView::widget(
                            [
                                'model' => $model,
                                'template' => function ($attribute, $index, $widget) {
                                    if (!empty($attribute['value'])) {
                                        return "<tr><th>{$attribute['label']}</th><td>{$attribute['value']}</td></tr>";
                                    }
                                },
                                'attributes' => [
                                    'name',
                                    [
                                        'attribute' => 'type',
                                        'value' => function ($model) {
                                            return $model->getTypeName();
                                        }
                                    ],
                                    'description:ntext',
                                    'rule_name',
                                    'data',
                                    [
                                        'attribute' => 'created_at',
                                        'value' => function ($model) {
                                            return DateTime::createFromFormat('U', $model->created_at)->format(
                                                Localization::getDateTimeLogFormat()
                                            );
                                        }
                                    ],
                                    [
                                        'attribute' => 'updated_at',
                                        'value' => function ($model) {
                                            return DateTime::createFromFormat('U', $model->updated_at)->format(
                                                Localization::getDateTimeLogFormat()
                                            );
                                        }
                                    ],
                                ],
                            ]
                        ) ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
