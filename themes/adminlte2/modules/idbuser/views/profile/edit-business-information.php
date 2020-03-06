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
            <?= Html::encode(Translate::_('business', 'Edit business information')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= $model->name ?></h3>
                    </div>
                    <div class="box-body">

                        <div class="business-information-form">

                            <?php $form = ActiveForm::begin(); ?>

                            <?php if ($model->getErrors()) { ?>
                                <?= Html::tag(
                                    'div',
                                    $form->errorSummary($model),
                                    ['class' => 'alert alert-danger']
                                ) ?>
                            <?php } ?>
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
                                                'action' => Url::toRoute(['/idbuser/profile']),
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
                                    ['class' => 'btn btn-primary']
                                ) ?>
                            </div>

                            <?= $form->field($model, 'VAT')->textInput(
                                ['placeholder' => $model->getAttributeLabel('VAT')]
                            ) ?>
                            <?= $form->field($model, 'registrationNumber')->textInput(
                                ['placeholder' => $model->getAttributeLabel('registrationNumber')]
                            ) ?>
                            <?= $form->field($model, 'addressLine1')->textInput(
                                ['placeholder' => $model->getAttributeLabel('addressLine1')]
                            ) ?>
                            <?= $form->field($model, 'addressLine2')->textInput(
                                ['placeholder' => $model->getAttributeLabel('addressLine2')]
                            ) ?>
                            <?= $form->field($model, 'city')->textInput(
                                ['placeholder' => $model->getAttributeLabel('city')]
                            ) ?>
                            <?= $form->field($model, 'region')->textInput(
                                ['placeholder' => $model->getAttributeLabel('region')]
                            ) ?>
                            <?= $form->field($model, 'postcode')->textInput(
                                ['placeholder' => $model->getAttributeLabel('postcode')]
                            ) ?>
                            <?= $form->field($model, 'country')->textInput(
                                ['placeholder' => $model->getAttributeLabel('country')]
                            ); ?>

                            <?php ActiveForm::end(); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
