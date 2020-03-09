<?php

use app\assets\IdbDataAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model idbyii2\models\db\BusinessAccount */

$dataAssets = IdbDataAsset::register($this);
$dataAssets->formAssets();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(
                Translate::_(
                    'business',
                    'Select vaults {UserName} can access',
                    ['UserName' => $firstname . ' ' . $lastname]
                )
            ) ?>
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

                        <div class="box-body">
                            <div class="form-group">
                                <?= Yii::$app->controller->renderPartial(
                                    '@app/themes/adminlte2/views/site/_modalWindow',
                                    [
                                        'modal' => [
                                            'name' => 'cancelFormActionModal',
                                            'header' => Translate::_('business', 'Cancel assign vault'),
                                            'body' => Translate::_(
                                                'business',
                                                'You have chosen to cancel the assign vault task, your changes will not be saved'
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
                                <?= Html::submitButton(
                                    Translate::_('business', 'Save'),
                                    ['class' => 'btn btn-primary', 'id' => 'save']
                                ) ?>
                            </div>

                            <?= $form->field($model, 'dbid')->dropDownList($databases)->label(Translate::_('business', 'Select vault')); ?>
                            <?= $form->field($model, 'uid')->hiddenInput(['value' => $uid])->label(false); ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
