<?php

use app\assets\DateTimePickerAsset;
use app\assets\SelectAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

$assetDatePickerBundle = DateTimePickerAsset::register($this);
$assetSelectBundle = SelectAsset::register($this);

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Write a message')) ?>
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
                        <div class="box-header with-border">
                            <?php if (!empty($userToSend)) { ?>
                                <div class="business-notification-form">
                                    <?php $form = ActiveForm::begin(
                                        [
                                            'action' => Url::toRoute(['/btpmessages/send'], true),
                                            'options' => ['method' => 'post']
                                        ]
                                    ); ?>
                                    <div class="form-group">
                                        <?= Html::submitButton(
                                            Translate::_('business', 'Send'),
                                            ['class' => 'btn btn-primary']
                                        ) ?>
                                    </div>

                                    <?= $form->field($model, 'business_user')->textInput(
                                        ['readonly' => true, 'value' => $businessUser]
                                    ) ?>

                                    <?= $form->field($model, 'people_user')->dropDownList(
                                        $userToSend,
                                        ['multiple' => 'multiple', 'name' => 'people_users[]']
                                    ) ?>

                                    <?= $form->field($model, 'subject')->textInput() ?>

                                    <?= $form->field($model, 'message')->textArea(['rows' => '6']) ?>

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
                                                        'class' => 'btn btn-danger btn-flat',
                                                        'onclick' => 'cleanMessageFormExpiresAt();'
                                                    ]
                                                ) . '
                           </span>
                           </div>
                           {error}{hint}'
                                        ]
                                    )->textInput() ?>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            <?php } else {
                                ?>
                                <div class="modal-no-user">
                                    <div class="alert alert-danger" role="alert">
                                        <?= Translate::_(
                                            'business',
                                            'There are no personal account holders connected to this business yet.'
                                        ) ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script>
    function initForm() {
        $('#business2peopleformmodel-expires_at').daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            autoApply: true,
            showDropdowns: true,
            locale: {format: 'YYYY/MM/DD HH:mm'}
        });
        <?php if(empty($model->expires_at)) { ?>
        cleanMessageFormExpiresAt();
        <?php } ?>
        $(document).ready(function () {
            $('#business2peopleformmodel-people_user').select2();
        });
    }

    function cleanMessageFormExpiresAt() {
        $('#business2peopleformmodel-expires_at').val("");
    }
</script>
<?php $this->registerJs("initForm()", View::POS_END); ?>
