<?php

use app\assets\AdminLte2Asset;
use app\helpers\ReturnUrl;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\adminlte2\views\yii\widgets\grid\GridView;
use yii\bootstrap\ActiveForm;
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
                        <h3 class="box-title"><?= Translate::_('business', 'Users') ?></h3>
                    </div>

                    <?php if (
                        Yii::$app->user->can('user_manager_create') || Yii::$app->user->can('user_manager_admin')
                    ) : ?>
                        <div class="box-header with-border">
                            <?php if (Yii::$app->user->can('user_manager_create'))
                                echo Html::a(
                                    '<i class="fa fa-user-plus"></i>' . Translate::_('business', 'Create'),
                                    ['create'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            <?php if (Yii::$app->user->can('user_manager_admin'))
                                echo Html::a(
                                    '<i class="fa fa-users"></i>' . Translate::_('business', 'Admin'),
                                    ['admin'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('user_manager_search')) : ?>
                        <div class="box-header with-border">
                            <div class="box-body">
                                <?php $form = ActiveForm::begin(
                                    [
                                        'id' => 'user-search-form',
                                        'method' => 'get',
                                        'action' => ['search'],
                                        'fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField'
                                    ]
                                ); ?>
                                <div class="form-group field-idbusersearchform-key required">
                                    <label class="control-label"
                                           for="idbusersearchform-key"><?= $model->getAttributeLabel(
                                            'key'
                                        ) ?></label>
                                    <div class="input-group input-group">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                <?= Translate::_('business', "Select") ?>
                                                <span class="fa fa-caret-down"></span></button>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($searchKeys as $searchKey => $searchKeyDisplay) { ?>
                                                    <li><?= Html::a(
                                                            $searchKeyDisplay,
                                                            null,
                                                            ['onclick' => "$('#idbusersearchform-key').val('$searchKey');"]
                                                        ) ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <input id="idbusersearchform-key" class="form-control"
                                               name="IdbUserSearchForm[key]"
                                            <?= ((empty($model->key) ? null : ('value="' . $model->key . '"'))) ?>
                                               aria-required="true" type="text">
                                        <p class="help-block help-block-error"></p>
                                    </div>
                                </div>
                                <?= $form->field($model, 'value')->textInput() ?>
                                <div class="box-footer">
                                    <?= Html::submitButton(
                                        Translate::_('business', 'Search'),
                                        ['class' => 'btn btn-primary pull-left', 'id' => 'search']
                                    ) ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($model->key) && !empty($model->value)) : ?>
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
                                            [
                                                'attribute' => 'uid',
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    return Html::a(
                                                        $model->uid,
                                                        ['view', 'uid' => (string)$model->uid]
                                                    );
                                                }
                                            ],
                                            [
                                                'label' => Translate::_('business', 'User info'),
                                                'value' => function ($model) {
                                                    return $model->userInfo;
                                                }
                                            ],
                                        ],
                                ]
                            ); ?>
                            <?php Pjax::end(); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </section>

</div>

<?php
$script = "
function initUserManagerTable()
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
        initUserManagerTable();
    }
});
";
if ($dataProvider->getTotalCount() > 0) {
    $script .= "$(function(){initUserManagerTable();})";
}
$this->registerJs($script, View::POS_END);
?>
