<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\widgets\PasswordGenerator;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutForms($this);

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Change Password')) ?>
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
                                        'header' => Translate::_('business', 'Stop changing password'),
                                        'body' => Translate::_(
                                            'business',
                                            'You have chosen to stop the change your password task, your changes will not be saved'
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
                                            'action' => Url::toRoute(['/idbuser/profile'], true)
                                        ],
                                        'rightButton' => [
                                            'label' => Translate::_('business', 'Continue'),
                                            'style' => 'btn btn-primary',
                                            'action' => 'data-dismiss'
                                        ],
                                    ]
                                ]
                            ); ?>
                            <?= Html::submitButton(Translate::_('business', 'Save'), ['class' => 'btn btn-primary']) ?>
                        </div>

                        <?php if ($model->getErrors()) { ?>
                            <?= Html::tag('div', $form->errorSummary($model), ['class' => 'callout callout-danger']) ?>
                        <?php } ?>

                        <?= $form->field($model, 'oldPassword')->passwordInput(['data-toggle' => 'password']) ?>

                        <?= PasswordGenerator::widget(['model' => $model, 'form' => $form]) ?>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
