<?php

use app\assets\AdminLte2Asset;
use app\helpers\ReturnUrl;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\adminlte2\views\yii\widgets\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutDataTable($this);

$url = ReturnUrl::generateUrl();

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Password Policies')) ?>
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
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Translate::_('business', 'Available Policies') ?></h3>
                        </div>

                        <?php if (Yii::$app->user->can('password_policy_create')) : ?>
                            <div class="box-header with-border">
                                <?php if (Yii::$app->user->can('password_policy_create'))
                                    echo Html::a(
                                        '<i class="fa fa-plus-circle"></i>' . Translate::_('business', 'Create'),
                                        ['create'],
                                        ['class' => 'btn btn-app']
                                    ) ?>
                            </div>
                        <?php endif; ?>

                        <div class="box-body">
                            <?php Pjax::begin(
                                [
                                    'id' => 'pjax_id_password_policies',
                                    'options' => [
                                        'class' => 'pjax',
                                        'loaderId' => 'loader_id_password_policies',
                                        'neverTimeout' => true
                                    ]
                                ]
                            ); ?>
                            <?= GridView::widget(
                                [
                                    'id' => 'grid_id_password_policies',
                                    'dataProvider' => $dataProvider,
                                    'tableOptions' => ['id' => 'table_id_password_policies'],
                                    'columns' =>
                                        [
                                            ['class' => 'yii\grid\SerialColumn'],

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

                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'template' => '{view} {update} {delete}',
                                                'visibleButtons' =>
                                                    [
                                                        'view' => Yii::$app->user->can('password_policy_view'),
                                                        'update' => Yii::$app->user->can('password_policy_update'),
                                                        'delete' => Yii::$app->user->can('password_policy_delete'),
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
function initPasswordPoliciesTable()
{
    if($.fn.dataTable.isDataTable( '#table_id_password_policies'))
    {
        table = $('#table_id_password_policies').DataTable();
    }
    else
    {
        table = $('#table_id_password_policies').DataTable(
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
    if($(this).attr('id')=='pjax_id_password_policies')
    {
        initPasswordPoliciesTable();
    }
});
";
if ($dataProvider->getTotalCount() > 0) {
    $script .= "$(function(){initPasswordPoliciesTable();})";
}
$this->registerJs($script, View::POS_END);
?>

