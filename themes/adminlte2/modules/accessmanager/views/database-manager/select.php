<?php

use app\helpers\ReturnUrl;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$url = ReturnUrl::generateUrl();

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Select vault and user')) ?>
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
                        <?php $form = ActiveForm::begin(
                            [
                                'action' => Url::toRoute(
                                    ['/accessmanager/database-manager/assign-role-database'],
                                    true
                                ),
                                'method' => 'post'
                            ]
                        ); ?>
                        <div class="form-group">
                            <?= Html::submitButton(
                                Translate::_('business', 'Select'),
                                ['class' => 'btn btn-primary']
                            ) ?>
                        </div>
                        <?= $form->field($model, 'select_db')
                                 ->dropDownList($databasesNames)->label(
                                Translate::_('business', 'Select a vault')
                            ); ?>
                        <?= $form->field($model, 'select_user')
                                 ->dropDownList($usersId)->label(
                                Translate::_('business', 'Select a user')
                            ); ?>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
