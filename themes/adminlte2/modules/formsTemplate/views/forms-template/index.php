<?php

use app\assets\FrontAsset;
use app\helpers\Translate;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use idbyii2\widgets\FlashMessage;

$frontAssets = FrontAsset::register($this);
$frontAssets->loadEditor();

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Editor')) ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">

                        <?= FlashMessage::widget(
                            [
                                'success' => Yii::$app->session->hasFlash('success')
                                    ? Yii::$app->session->getFlash(
                                        'success'
                                    ) : null,
                                'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash(
                                    'error'
                                )
                                    : null,
                                'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash(
                                    'info'
                                )
                                    : null,
                            ]
                        ); ?>
                        <?php if(!empty($usersToSend)): ?>
                        <div id="editor">
                            <?php $form = ActiveForm::begin(
                                [
                                    'action' => Url::toRoute(['/forms-template/forms-template/save'], true),
                                    'options' => ['method' => 'post']
                                ]
                            ); ?>
                            <div class="form-group">

                                <?= $form->field($model, 'peopleUser')->dropDownList(
                                    $usersToSend,
                                    [
                                        'name' => 'peopleUser',
                                    ]
                                ) ?>
                                <table style="width:100%" class="table table-bordered">
                                    <tr>
                                        <th><?= Translate::_('business', 'Field name') ?></th>
                                        <th><?= Translate::_('business', 'Type') ?></th>
                                        <th><?= Translate::_('business', 'Description') ?></th>
                                        <th><?= Translate::_('business', 'Remove') ?></th>
                                    </tr>
                                    <tr v-for="(row, index) in rows">
                                        <td><?= $form->field($model, 'data[fieldName][]')->textInput(
                                                ['v-model' => 'row.name', 'v-bind:style' => 'inputStyle']
                                            )->label(false) ?></td>
                                        <td>
                                            <select v-bind:style="inputStyle" name="FTemplateForm[data][type][]">
                                                <option v-for="type in types" v-bind:value="type.value">
                                                    {{ type.value }}
                                                </option>
                                            </select>
                                        </td>
                                        <td><?= $form->field($model, 'data[fieldDesc][]')->textInput(
                                                ['v-model' => 'row.description', 'v-bind:style' => 'inputStyle']
                                            )->label(false) ?></td>
                                        <td v-bind:style="centerBtnRow">
                                            <a v-on:click="removeElement(index);" class="btn btn-app-trash"
                                               style="cursor: pointer"><i class="glyphicon glyphicon-trash"></i></a>
                                        </td>
                                    </tr>
                                </table>
                                <div v-bind:style="centerBtnRow">
                                    <?= Html::button(
                                        'Add row',
                                        ['class' => 'btn btn-primary', 'v-on:click' => 'addRow']
                                    ) ?>
                                </div>
                                <hr>
                                <div id="submit">
                                    <?= Html::submitButton(
                                        Translate::_('business', 'Save'),
                                        ['class' => 'btn btn-primary', 'style' => 'float: right;']
                                    ) ?>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                            <?php else: ?>
                                <div class="modal-no-user">
                                    <div class="alert alert-danger" role="alert">
                                        <?= Translate::_(
                                            'business',
                                            'There are no personal account holders connected to this business yet.'
                                        ) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>