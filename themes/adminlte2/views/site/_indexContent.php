<?php

use app\helpers\Translate;
use idbyii2\helpers\Account;
use idbyii2\models\db\AuthAssignment;
use idbyii2\models\db\BusinessDatabaseUser;
use idbyii2\widgets\DashboardElement;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use idbyii2\widgets\FlashMessage;

?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div style="margin: 15px;">
                <div class="alert alert-danger alert-dismissible idb-hidden">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-frown-o"></i> <?= Translate::_(
                            'business',
                            'An error has occured. Please contact your system administrator.'
                        ) ?></h4>
                    <span class="danger-message"></span>
                </div>

                <div class="alert alert-success alert-dismissible idb-hidden">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> <?= Translate::_('business', 'Success.') ?></h4>
                    <span class="success-message"></span>
                </div>

                <?= FlashMessage::widget(
                    [
                        'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash(
                            'success'
                        ) : null,
                        'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error')
                            : null,
                        'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info')
                            : null,
                    ]
                ); ?>

                <?php if (Yii::$app->request->referrer === Url::toRoute('/mfa', true)): ?>
                    <h1 style="text-align: center"><?= Translate::_(
                            'business',
                            'Welcome back to your Identity Bank account!'
                        ) ?></h1>
                <?php endif; ?>

                <?php $form = ActiveForm::begin(
                    [
                        'action' => Url::toRoute(['/'], true),
                        'method' => 'post',
                        'id' => 'database-form',
                        'fieldConfig' => [
                            'options' => [
                                'tag' => false,
                            ],
                        ],
                    ]
                ); ?>
                <div class="col-lg-12 col-xs-12" style="margin-top: 15px; margin-bottom: 15px; padding: 0px;">

                    <div class="input-group input-group-sm">
                        <?php if ($model->dbName): ?>
                            <div class="input-group-btn">
                                <?= Html::submitButton(
                                    null,
                                    [
                                        'class' => 'hidden',
                                        'name' => 'search'
                                    ]
                                ) ?>
                                <?= Html::submitButton(
                                    '<i class="icon fa fa-close"></i>',
                                    [
                                        'class' => 'btn btn-danger',
                                        'name' => 'reset',
                                        'value' => 'reset'
                                    ]
                                ) ?>
                            </div>
                        <?php endif; ?>
                        <?= $form->field(
                            $model,
                            'dbName',
                            [
                                'inputOptions' =>
                                    [
                                        'autofocus' => 'autofocus',
                                        'class' => 'form-control'
                                    ]
                            ]
                        )->textInput(['placeholder' => Translate::_('business', 'Vault name')])->label(false) ?>
                        <div class="input-group-btn">
                            <?= Html::submitButton(
                                Translate::_('business', 'Search'),
                                [
                                    'class' => 'btn btn-info btn-flat',
                                    'name' => 'search'
                                ]
                            ) ?>
                        </div>
                    </div>

                </div>
                <?php ActiveForm::end() ?>

                <div class="row">
                    <?php foreach ($databases as $database): ?>
                        <?= DashboardElement::widget(
                            [
                                'database' => $database,
                                'current' => Yii::$app->user->identity->dbid
                            ]
                        ); ?>
                    <?php endforeach; ?>
                    <div class="col-lg-3 col-xs-6">
                        <?= Html::a(
                            '<span class="info-box-icon bg-yellow"><i class="fa fa-plus-square"></i></span>',
                            ['/tools/wizard/select-db']
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    const successMessage = '<?= Translate::_('business', 'Your data was saved successfully.') ?>';
    const emptyMessage = '<?= Translate::_('business', 'Title can\\\'t be empty. please fill it before save') ?>';
    const dangerMessage = '<?= Translate::_('business', 'An error has occurred, please try again later.') ?>';
    const editURL = '<?= Url::toRoute(['/accessmanager/database-manager/edit-database'], true)?>';
</script>
