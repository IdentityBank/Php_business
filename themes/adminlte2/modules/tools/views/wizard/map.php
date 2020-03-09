<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\idb\assets\ImportWizardAsset;
use idbyii2\models\db\BusinessImportWorksheet;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$wizardAsset = ImportWizardAsset::register($this);
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Connect with people')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizard(
                    [
                        'Icon' => 'glyphicon-edit',
                        'Title' => Translate::_('business', 'Understand your data')
                    ],
                    [
                        'Icon' => 'glyphicon-edit',
                        'Title' => Translate::_('business', 'Connect with people')
                    ],
                    [
                        'Icon' => 'glyphicon-edit',
                        'Title' => Translate::_('business', 'Lawfull basis')
                    ],
                    1
                ) ?>
            </div>
        </div>
        <h4 style="text-align: center"><?= Translate::_(
                'business',
                'Identity Bank can automatically invite all new people in your vault to connect to your business.'
            ); ?>
        </h4>
        <h4 style="text-align: center"><?= Translate::_(
                'business',
                'The significant advantage for you is that people can manage their own data, rather than you having to do this.'
            ); ?>
        </h4>
        <h4 style="text-align: center"><?= Translate::_(
                'business',
                'Please select safes in your vault that contain the following:'
            ); ?>
        </h4>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?php $form = ActiveForm::begin(); ?>

                        <div id="dataTypes">
                            <div>
                                <?= $form->field($model, 'email_no')
                                         ->dropDownList($metadata)->label(
                                        Translate::_('business', "Select the email safe")
                                    ); ?>
                                <?= $form->field($model, 'mobile_no')
                                         ->dropDownList($metadata)->label(
                                        Translate::_('business', "Select the mobile phone number safe")
                                    ); ?>
                                <?= $form->field(
                                    $model,
                                    'phone_code',
                                    [
                                        'template' => '{label}{input}{error}{hint}'
                                    ]
                                )
                                         ->dropDownList(
                                             [
                                                 0 => 'Not assign anything',
                                                 '+48' => 'Polska (48)',
                                                 '+31' => 'Netherlands (31)'
                                             ]
                                         )->label(
                                        Translate::_(
                                            'business',
                                            'Provide the country code to use if it is missing from mobile phone numbers'
                                        )
                                    ); ?>
                                <?= $form->field($model, 'name_no')
                                         ->dropDownList($metadata)->label(
                                        Translate::_('business', "Select the first name safe")
                                    ); ?>
                                <?= $form->field($model, 'surname_no')
                                         ->dropDownList($metadata)->label(
                                        Translate::_('business', "Select the surname safe")
                                    ); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $form->field(
                                $model,
                                'send_email',
                                [
                                    'template' => '{input}{label}{error}{hint}'
                                ]
                            )
                                     ->checkbox(['checked' => true, 'label' => false])->label(
                                            Translate::_('business', 'Send an invitation to people to connect to your business')
                                ); ?>
                            <?= $form->field(
                                $model,
                                'valid_both',
                                [
                                    'template' => '{input}{label}{error}{hint}'
                                ]
                            )
                                     ->checkbox(['checked' => true, 'label' => false])->label(
                                        Translate::_(
                                            'business',
                                            'Invitations are only sent when both the email address and mobile phone number are valid.'
                                        )
                                ); ?>
                            <?= Html::submitButton(
                                Translate::_('business', 'Continue'),
                                ['class' => 'btn btn-primary pull-right']
                            ) ?>
                            <?php $worksheet = BusinessImportWorksheet::findOne(['id' => $id]); ?>
                            <?= Html::a(
                                Translate::_("business", "Back"),
                                $worksheet->getBackUrl(),
                                ['class' => 'btn btn-back pull-left']
                            ); ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
