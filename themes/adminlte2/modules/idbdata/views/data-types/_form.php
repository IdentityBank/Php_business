<?php

use app\helpers\Translate;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="data-types-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Yii::$app->controller->renderPartial(
            '@app/themes/adminlte2/views/site/_modalWindow',
            [
                'modal' => [
                    'name' => 'cancelFormActionModal',
                    'header' => Translate::_('business', 'Cancel edit your data'),
                    'body' => Translate::_(
                        'business',
                        'You have chosen to cancel the edit your data task, your changes will not be saved'
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
        <?= Html::submitButton(Translate::_('business', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?= $form->field($model, 'internal_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'display_name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'data_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'searchable')->textInput() ?>

    <?= $form->field($model, 'sortable')->textInput() ?>

    <?= $form->field($model, 'sensitive')->textInput() ?>

    <?= $form->field($model, 'required')->textInput() ?>

    <?= $form->field($model, 'tag')->textarea(['rows' => 6]) ?>

    <?php ActiveForm::end(); ?>

</div>