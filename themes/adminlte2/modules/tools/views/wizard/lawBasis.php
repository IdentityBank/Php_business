<?php

use app\assets\FrontAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\idb\assets\ImportWizardAsset;
use idbyii2\models\db\BusinessImportWorksheet;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$wizardAsset = ImportWizardAsset::register($this);
$frontAssets = FrontAsset::register($this);
$frontAssets->idbUpdateMessage();

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Define Lawful Basis for your data to process the data in the vault')) ?>
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
                        'Title' => Translate::_('business', 'Connect with people')
                    ],
                    [
                        'Icon' => 'glyphicon-tasks',
                        'Title' => Translate::_('business', 'Lawful basis')
                    ],
                    [
                        'Icon' => 'glyphicon-book',
                        'Title' => Translate::_('business', 'Data processors')
                    ],
                    1
                ) ?>
            </div>
        </div>
        <h4 style="text-align: center"><?= Translate::_(
                'business',
                'You must determine your lawful basis before you can store your data.'
            ); ?>
        </h4>
        <br>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="form-group">
                            <?= $form->field($model, 'legal')
                                ->dropDownList($legal)->label(
                                    Translate::_('business', "Lawful Basis")
                                ); ?>
                            <?= $form->field($model, 'messages')
                                ->dropDownList($messages)->label(
                                    Translate::_('business', "Select your defined message")
                                ); ?>
                            <?= $form->field($model, 'message')->textarea(['rows' => 3])
                                ->label(Translate::_('business', "Message content"))
                                ->hint(Translate::_('business', "At this field you can provide your custom message content."),
                                    ['tag' => 'div', 'class' => 'alert alert-info']); ?>

                            <h2><?= Translate::_('business', 'Purpose Limitation:') ?></h2>
                            <?= $form->field($model, 'purposeLimitation')->textarea(['rows' => 3])
                                ->label(Translate::_('business', "Provide: Specified, explicit and legitimate purposes")) ?>

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
