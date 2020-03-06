<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Assign role to secure vault')) ?>
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
                                'action' => Url::toRoute(['/accessmanager/database-manager/assign-role'], true),
                                'method' => 'post'
                            ]
                        ); ?>
                        <div class="form-group">
                            <?= Yii::$app->controller->renderPartial(
                                '@app/themes/adminlte2/views/site/_modalWindow',
                                [
                                    'modal' => [
                                        'name' => 'cancelFormActionModal',
                                        'header' => Translate::_('business', 'Cancel create vault'),
                                        'body' => Translate::_(
                                            'business',
                                            'You have chosen to cancel the create vault task, your changes will not be saved'
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
                                            'action' => Yii::$app->session->get('urlRedirect')
                                        ],
                                        'rightButton' => [
                                            'label' => Translate::_('business', 'Continue'),
                                            'style' => 'btn btn-primary',
                                            'action' => 'data-dismiss'
                                        ],
                                    ]
                                ]
                            ); ?>
                            <?= Html::submitButton(
                                Translate::_('business', 'Select'),
                                ['class' => 'btn btn-primary']
                            ) ?>
                        </div>
                        <h3><span><?= Translate::_('business', 'Vault') ?>: </span><?= $dbName ?></h3>
                        <h3><span><?= Translate::_('business', 'User') ?>: </span><?= $userName ?></h3>

                        <?= $form->field($model, 'assign_role')
                                 ->dropDownList($names)->label(
                                Translate::_('business', 'Select a role:')
                            ); ?>
                        <?= $form->field($model, 'uid')->hiddenInput(['value' => $uid])->label(false); ?>
                        <?= $form->field($model, 'dbid')->hiddenInput(['value' => $dbid])->label(false); ?>
                        <?= $form->field($model, 'user_name')->hiddenInput(['value' => $userName])->label(false); ?>
                        <?= $form->field($model, 'db_name')->hiddenInput(['value' => $dbName])->label(false); ?>

                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
