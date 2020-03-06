<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\widgets\FlashMessage;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model idbyii2\models\db\BusinessAccount */

?>

<div class="content-wrapper">
    <?= FlashMessage::widget(
        [
            'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success') : null,
            'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : null,
            'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : null,
        ]
    ); ?>
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Create new user')) ?>
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
                            ['fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField']
                        ); ?>

                        <div class="form-group">
                            <?= Yii::$app->controller->renderPartial(
                                '@app/themes/adminlte2/views/site/_modalWindow',
                                [
                                    'modal' => [
                                        'name' => 'cancelFormActionModal',
                                        'header' => Translate::_('business', 'Stop create user'),
                                        'body' => Translate::_(
                                            'business',
                                            'You have chosen to stop the create user task, your changes will not be saved'
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
                                            'style' => 'btn btn-back',
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
                                Translate::_('business', 'Save'),
                                ['class' => 'btn btn-primary', 'id' => 'save']
                            ) ?>
                        </div>

                        <div class="box-body">

                            <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(Translate::_('business','Name')) ?>
                            <?= $form->field($model, 'surname')->textInput(['maxlength' => true])->label(Translate::_('business','Surname')) ?>
                            <?= $form->field($model, 'mobile')->textInput(['maxlength' => true])->label(Translate::_('business','Mobile')) ?>
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true])->label(Translate::_('business','Email')) ?>
                            <?php if (count($databaseArray) > 0): ?>
                                <?= $form->field($model, 'dbid[]')->dropDownList($databaseArray)->label(
                                    Translate::_('business', 'Select vaults')
                                ); ?>
                            <?php endif; ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
