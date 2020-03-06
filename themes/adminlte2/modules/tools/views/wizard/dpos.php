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
$frontAssets->idbUpdateDpo();

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Define data processors for your data to process the data in the vault')) ?>
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
                        'Icon' => 'glyphicon-tasks',
                        'Title' => Translate::_('business', 'Lawful basis')
                    ],
                    [
                        'Icon' => 'glyphicon-book',
                        'Title' => Translate::_('business', 'Data processors')
                    ],
                    [
                        'Icon' => 'glyphicon-time',
                        'Title' => Translate::_('business', 'Storage time limitations')
                    ],
                    1
                ) ?>
            </div>
        </div>
        <h4 style="text-align: center"><?= Translate::_(
                'business',
                'You must determine your data processors for your data if you have any, otherwise "Continue" to the next step.'
            ); ?>
        </h4>
        <br>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="form-group" style="text-align:center;">
                            <h2><?= Translate::_('business', 'List Data Processors:') ?></h2>

                            <div id="dpo-container">
                                <?php foreach($dpos as $dpo): ?>
                                    <div class="dpo-div">
                                        <textarea style="margin-bottom: 20px" class="form-control" name="dpo[]" rows="3"><?= $dpo ?></textarea>
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button style="margin-bottom: 20px;" id="add-dpo" class="btn btn-primary"><i class="fa fa-plus-square"></i></button>
                            <div class="clear"></div>
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

<?= BusinessConfig::jsOptions(
    [
        'dayMessage' => Translate::_('business', 'days'),
        'hourMessage' => Translate::_('business', 'hours'),
    ]
) ?>
