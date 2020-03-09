<?php

use app\helpers\Translate;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div id="id-loading" class="loading" hidden></div>
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= $this->title ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-database margin-r-5"></i>
                                <?= Html::encode($db['name']) ?>
                            </h3>
                            <?php if (!empty($db['description'])) : ?>
                                <hr>
                                <h5>
                                    <?= Html::encode($db['description']) ?>
                                </h5>
                            <?php endif; ?>
                            <hr>
                            <?php foreach ($buttons as $button) : ?>
                                <?= Html::a($button['buttonTitle'], null, $button) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="box-header with-border">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modal-update-db-attributes">
    <div class="modal-dialog modal-dialog-centered" style="margin-top: 50px;">
        <div class="modal-content">
            <?php $form = ActiveForm::begin(
                [
                    'id' => 'update-db-form',
                    'action' => Url::toRoute(['/accessmanager/database-manager/edit-database'], true),
                    'fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField'
                ]
            ); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 id="update-modal-title" class="modal-title"><?= Translate::_(
                        'business',
                        'Edit vault name and description'
                    ) ?></h4>
            </div>
            <div class="modal-body">
                <?= $form->field($db, 'name')->textInput() ?>
                <?= $form->field($db, 'description')->textarea() ?>
                <?= $form->field($db, 'dbid')->hiddenInput()->label(false); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= Translate::_(
                        'business',
                        'Cancel'
                    ) ?></button>
                <?= Html::submitButton(Translate::_('business', 'Update'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?= Html::hiddenInput('action', 'update') ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<style>

    #modal-update-db-attributes
    .modal-header {
        background-color: #232E6C;
        color: white;
        font-family: ubuntu;
    }

    #modal-update-db-attributes
    .modal-body {
        background-color: #D7F7F5;
        font-family: ubuntu;
    }

    #modal-update-db-attributes
    .modal-footer {
        background-color: #00AEAB;
    }

    #modal-update-db-attributes
    .modal-footer {
        justify-content: space-between;
    }

</style>

<?= Yii::$app->controller->renderPartial(
    '@app/themes/adminlte2/views/site/_modalWindow',
    [
        'modal' => [
            'name' => 'cancelResetActionModal',
            'header' => Translate::_('business', 'Reset vault'),
            'body' => Translate::_(
                'business',
                'This action remove all your data and connected individuals with your business. That action cannot be reverted!'
            ),
            'leftButton' => [
                'label' => Translate::_('business', 'Cancel'),
                'style' => 'btn btn-back',
                'action' => 'data-dismiss'
            ],
            'rightButton' => [
                'label' => Translate::_('business', 'Continue and reset my vault'),
                'style' => 'btn btn-danger',
                'onClickAction' => "showLoading();",
                'action' => Url::toRoute('/accessmanager/database-manager/reset-database', true)
            ],
        ]
    ]
); ?>

<?= Yii::$app->controller->renderPartial(
    '@app/themes/adminlte2/views/site/_modalWindow',
    [
        'modal' => [
            'name' => 'cancelDeleteActionModal',
            'header' => Translate::_('business', 'Delete vault'),
            'body' => Translate::_(
                'business',
                'This action remove all your data and connected individuals with your business. That action cannot be reverted!'
            ),
            'leftButton' => [
                'label' => Translate::_('business', 'Cancel'),
                'style' => 'btn btn-back',
                'action' => 'data-dismiss'
            ],
            'rightButton' => [
                'label' => Translate::_('business', 'Continue and delete this vault'),
                'style' => 'btn btn-danger',
                'onClickAction' => "showLoading();",
                'action' => Url::toRoute('/accessmanager/database-manager/delete-database', true)
            ],
        ]
    ]
); ?>

<script>
    function showLoading() {
        $("#id-loading").show();
        $("#cancelResetActionModal").hide();
        $("#cancelDeleteActionModal").hide();
    }
</script>
