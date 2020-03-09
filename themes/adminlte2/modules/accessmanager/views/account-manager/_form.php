<?php

use app\helpers\Translate;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model idbyii2\models\db\BusinessAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-account-form">

    <h2><?= Translate::_('business', 'Enter an account name') ?> </h2>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Yii::$app->controller->renderPartial(
            '@app/themes/adminlte2/views/site/_modalWindow',
            [
                'modal' => [
                    'name' => 'cancelFormActionModal',
                    'header' => Translate::_('business', 'Stop create account'),
                    'body' => Translate::_(
                        'business',
                        'You have chosen to stop the create an account task, your changes will not be saved'
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

    <?= $form->field($model, 'name')->textInput() ?>

    <hr>
    <h2><?= Translate::_('business', 'Who will be manager this account?') ?> </h2>

    <?php if (is_null($model->uid)) {
        $model->uid = Yii::$app->user->identity->id;
    } ?>
    <?= $form->field($model, 'uid')->radioList(
        $users,
        [
            'item' => function ($index, $label, $name, $checked, $value) {

                $return = '<label>';
                $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"' . ' ' . ($checked
                        ? "checked" : null) . '>';
                $return .= '<span> &nbsp;' . ucwords($label) . '</span>';
                $return .= '</label><br>' . PHP_EOL;

                return $return;
            }
        ]
    )->label(false); ?>

    <?php ActiveForm::end(); ?>

</div>
