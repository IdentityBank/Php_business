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
    <div class="form-group">
        <?= Yii::$app->controller->renderPartial(
            '@app/themes/adminlte2/views/site/_modalWindow',
            [
                'modal' => [
                    'name' => 'cancelFormActionModal',
                    'header' => Translate::_('business', 'Cancel edit your data'),
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
        <?= Html::submitButton(Translate::_('business', 'Save'), ['class' => 'btn btn-primary', 'id' => 'save']) ?>

    </div>

    <?= $form->field($model, 'userId')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'data-toggle' => 'password']) ?>

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
