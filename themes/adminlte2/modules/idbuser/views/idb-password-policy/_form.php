<?php

use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutForms($this);

?>

<?php $form = ActiveForm::begin(['fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField']); ?>

<div class="box-body">
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

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'lowercase')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'uppercase')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'digit')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'special')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'special_chars_set')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'min_types')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'reuse_count')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'min_recovery_age')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'max_age')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'min_length')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'max_length')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'change_initial')->checkbox(['uncheck' => 0, 'value' => 1]); ?>
    <?= $form->field($model, 'level')->textInput(['type' => 'number', 'step' => 10]) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
function toggleCheckbox(element)
{
    console.log(element.checked)
}
$(function () {
    $('#idbpasswordpolicy-change_initial').onchange=\"toggleCheckbox(this)\";
    $('input[type=\"checkbox\"], input[type=\"radio\"]').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
    })
});
";
$this->registerJs($script, View::POS_END);
?>
