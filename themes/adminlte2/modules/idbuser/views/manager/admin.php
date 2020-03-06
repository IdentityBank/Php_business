<?php

use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\adminlte2\views\yii\widgets\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutDataTable($this);

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
                        <h3 class="box-title"><?= Translate::_('business', 'Users') ?></h3>
                    </div>

                    <?php if (
                        Yii::$app->user->can('user_manager_create') || Yii::$app->user->can('user_manager_search')
                    ) : ?>
                        <div class="box-header with-border">
                            <?php if (Yii::$app->user->can('user_manager_create'))
                                echo Html::a(
                                    '<i class="fa fa-user-plus"></i>' . Translate::_('business', 'Create'),
                                    ['create'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            <?php if (Yii::$app->user->can('user_manager_search'))
                                echo Html::a(
                                    '<i class="fa fa-search"></i>' . Translate::_('business', 'Search'),
                                    ['search'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                        </div>
                    <?php endif; ?>

                    <div class="box-body">
                        <?php Pjax::begin(
                            [
                                'id' => 'pjax_id_pjax_id_user_manager',
                                'options' => [
                                    'class' => 'pjax',
                                    'loaderId' => 'loader_id_pjax_id_user_manager',
                                    'neverTimeout' => true
                                ]
                            ]
                        ); ?>
                        <?= GridView::widget(
                            [
                                'id' => 'grid_id_user_manager',
                                'dataProvider' => $dataProvider,
                                'tableOptions' => ['id' => 'table_id_user_manager'],
                                'columns' =>
                                    [
                                        ['class' => 'yii\grid\SerialColumn'],

                                        'uid',
                                        [
                                            'label' => Translate::_('business', 'Login Name'),
                                            'value' => function ($model) {
                                                return $model->getUserDataProviderValue('userId');
                                            }
                                        ],
                                        [
                                            'label' => Translate::_('business', 'Account Number'),
                                            'value' => function ($model) {
                                                return $model->getUserDataProviderValue('accountNumber');
                                            }
                                        ],
                                        [
                                            'label' => Translate::_('business', 'Email'),
                                            'value' => function ($model) {
                                                return $model->getUserDataProviderValue('email');
                                            }
                                        ],
                                        [
                                            'label' => Translate::_('business', 'Phone'),
                                            'value' => function ($model) {
                                                return $model->getUserDataProviderValue('phone');
                                            }
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'template' => '{view} {update} {delete}',
                                            'urlCreator' => function ($action, $model, $key, $index) {
                                                return Url::toRoute(
                                                    ["manager/$action", 'uid' => (string)$model->uid],
                                                    true
                                                );
                                            },
                                            'visibleButtons' =>
                                                [
                                                    'view' => Yii::$app->user->can('user_manager_view'),
                                                    'update' => Yii::$app->user->can('user_manager_update'),
                                                    'delete' => Yii::$app->user->can('user_manager_delete'),
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

<?php
$script = "
function initUsersTable()
{
    if($.fn.dataTable.isDataTable( '#table_id_user_manager'))
    {
        table = $('#table_id_user_manager').DataTable();
    }
    else
    {
        table = $('#table_id_user_manager').DataTable(
        {
            'paging'      : false,
            'lengthChange': false,
            'searching'   : false,
            'ordering'    : false,
            'info'        : false,
            'autoWidth'   : false,
            'scrollX'     : true
        });
    }
}

$('.pjax').on('pjax:complete', function()
{
    if($(this).attr('id')=='pjax_id_pjax_id_user_manager')
    {
        initUsersTable();
    }
});
";
if ($dataProvider->getTotalCount() > 0) {
    $script .= "$(function(){initUsersTable();})";
}
$this->registerJs($script, View::POS_END);
?>

