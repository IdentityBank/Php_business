<?php

use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$assetBundle = AdminLte2Asset::register($this);

?>

<?php $form = ActiveForm::begin(['fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField']); ?>

<div class="box-body">

    <?= $form->field($model, 'userId')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'data-toggle' => 'password']) ?>
    <?php if (count($databaseArray) > 1): ?>
        <?= $form->field($model, 'dbid[]')->dropDownList($databaseArray)->label("Select vaults"); ?>
    <?php endif; ?>

    <div class="box-footer">
        <?php if (!empty($cancel_action)) {
            echo Html::a(Translate::_('business', 'Cancel'), $cancel_action, ['class' => 'btn btn-default']);
        } ?>
        <?= Html::submitButton(
            Translate::_('business', 'Save'),
            ['class' => 'btn btn-primary pull-right', 'id' => 'save']
        ) ?>
    </div>

</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
$(function () {
    $('#idbusercreateform-password').password('hide');
});
";
$this->registerJs($script, View::POS_END);
?>
