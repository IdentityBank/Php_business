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
                        <h3 class="box-title"></h3>
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

                            <?php if ($model->getErrors()) { ?>
                                <?= Html::tag(
                                    'div',
                                    $form->errorSummary($model),
                                    ['class' => 'alert alert-danger']
                                ) ?>
                            <?php } ?>

                            <h2><?= Translate::_(
                                    'business',
                                    'Enter info about DPO:'
                                ) ?></h2>

                            <?= $form->field($model, 'dpoEmail')->textInput(
                                ['placeholder' => $model->getAttributeLabel('dpoEmail')]
                            )->hint(false)?>
                            <?= $form->field($model, 'dpoMobile')->textInput(
                                ['placeholder' => $model->getAttributeLabel('dpoMobile')]
                            )->hint(false) ?>
                            <?= $form->field($model, 'dpoAddress')->textArea(
                                ['rows' => '3', 'placeholder' => $model->getAttributeLabel('dpoAddress')]
                            )?>
                            <?= $form->field($model, 'dpoOther')->textArea(
                                ['rows' => '3', 'placeholder' => $model->getAttributeLabel('dpoOther')]
                            )?>

                            <h2><?= Translate::_(
                                    'business',
                                    'Enter link to your privacy condition:'
                                ) ?></h2>

                            <?= $form->field($model, 'dpoTermsAndCondition')->textInput(
                                ['placeholder' => $model->getAttributeLabel('dpoTermsAndCondition')]
                            ) ?>
                            <?= $form->field($model, 'dpoDataProcessingAgreements')->textInput(
                                ['placeholder' => $model->getAttributeLabel('dpoDataProcessingAgreements')]
                            ) ?>
                            <?= $form->field($model, 'dpoPrivacyNotice')->textInput(
                                ['placeholder' => $model->getAttributeLabel('dpoPrivacyNotice')]
                            ) ?>
                            <?= $form->field($model, 'dpoCookiePolicy')->textInput(
                                ['placeholder' => $model->getAttributeLabel('dpoCookiePolicy')]
                            ) ?>

                            <?php ActiveForm::end(); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
