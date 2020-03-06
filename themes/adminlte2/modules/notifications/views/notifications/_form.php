<?php

use app\assets\DateTimePickerAsset;
use app\helpers\Translate;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$assetDatePickerBundle = DateTimePickerAsset::register($this);

?>

<div class="business-notification-form">

    <?php $form = ActiveForm::begin(); ?>

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

    <?= $form->field($model, 'uid')->textInput(['readonly' => true, 'value' => $model->uid]) ?>

    <div>
        <?= $form->field($model, 'title')->textInput() ?>
        <ul style="list-style: none;">
            <li><?= $form->field($model, 'url')->textInput() ?></li>
            <li><?= $form->field($model, 'action_name')->textInput() ?></li>
        </ul>
    </div>

    <?= $form->field($model, 'body')->textArea() ?>

    <?= $form->field($model, 'type')->dropdownList(
        ['red' => 'RED', 'green' => 'GREEN', 'amber' => 'AMBER'],
        ['options' => ['green' => ['selected' => true]]]
    ) ?>

    <?= $form->field($model, 'status')->dropdownList(
        ['0' => 'Off', '1' => 'On'],
        ['options' => [$model->status => ['selected' => true]]]
    ) ?>

    <?= $form->field(
        $model,
        'expires_at',
        [
            'template' => '{label}
                           <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                           {input}
                           <span class="input-group-btn">
                           ' . Html::button(
                    '<i class="glyphicon glyphicon-trash"></i>',
                    [
                        'class' => 'btn btn-trash btn-flat',
                        'onclick' => 'cleanNotificationsFormExpiresAt();'
                    ]
                ) . '
                           </span>
                           </div>
                           {error}{hint}'
        ]
    )->textInput() ?>

    <?php ActiveForm::end(); ?>

</div>

<script>
    function initForm() {
        $('#notificationsform-expires_at').daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            autoApply: true,
            showDropdowns: true,
            locale: {format: 'YYYY/MM/DD HH:mm'}
        });
        <?php if(empty($model->expires_at)) { ?>
        cleanNotificationsFormExpiresAt();
        <?php } ?>
    }

    function cleanNotificationsFormExpiresAt() {
        $('#notificationsform-expires_at').val("");
    }
</script>
<?php $this->registerJs("initForm()", View::POS_END); ?>
