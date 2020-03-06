<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\idb\assets\ImportWizardAsset;
use idbyii2\helpers\DataJSON;
use idbyii2\models\db\BusinessImportWorksheet;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var array $headers */
/** @var array $types */

$wizardAsset = ImportWizardAsset::register($this);
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Add or exclude safes from a vault')) ?>
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
                        'Icon' => 'glyphicon-file',
                        'Title' => Translate::_('business', 'Select worksheet')
                    ],
                    [
                        'Icon' => 'glyphicon-edit',
                        'Title' => Translate::_('business', 'Understand your data')
                    ],
                    [
                        'Icon' => 'glyphicon-edit',
                        'Title' => Translate::_('business', 'Connect with people')
                    ],
                    1
                ) ?>
            </div>
        </div>
        <h4 style="text-align: center"><?= Html::encode(
                \idbyii2\helpers\Translate::_(
                    'business',
                    'The worksheet you have selected to import contains the following columns. In your account your worksheet becomes a vault and the columns in your worksheet become the safes in the vault.'
                )
            ); ?>
        </h4>
        <h4 style="text-align: center"><?= Html::encode(
                \idbyii2\helpers\Translate::_(
                    'business',
                    'For the columns shown, you can now: create a new safe or exclude safes from the vault.'
                )
            ); ?>
        </h4>
        <h4 style="text-align: center"><?= Html::encode(
                \idbyii2\helpers\Translate::_(
                    'business',
                    'You will be able to change the name of the safe later after your worksheet has been imported'
                )
            ); ?>
        </h4>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <div class="data-types-create">
                            <div class="data-types-form">
                                <?php $form = ActiveForm::begin(); ?>
                                <?php $firsthalf = array_slice($headers, 0, count($headers) / 2); ?>
                                <?php $secondhalf = array_slice($headers, count($headers) / 2); ?>
                                <?php $options = [
                                    DataJSON::NEW_COLUMN => Translate::_('business', 'Create new safe'),
                                    DataJSON::SKIP_COLUMN => Translate::_(
                                        'business',
                                        'Do not import data from this column'
                                    )
                                ];
                                ?>
                                <div class="col-xs-6">
                                    <?php foreach ($firsthalf as $header): ?>
                                        <p><?= $form->field($model, $header['headerInternal'])->dropDownList(
                                                array_merge($options, $types)
                                            )->label($header['header']) ?></p>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-xs-6">
                                    <?php foreach ($secondhalf as $header): ?>
                                        <p><?= $form->field($model, $header['headerInternal'])->dropDownList(
                                                array_merge($options, $types)
                                            )->label($header['header']) ?></p>
                                    <?php endforeach; ?>
                                </div>


                                <div class="form-group">
                                    <?php /** @var BusinessImportWorksheet $worksheet */ ?>
                                    <?= Html::submitButton(
                                        Translate::_("business", "Continue"),
                                        ['class' => 'btn btn-primary pull-right']
                                    ); ?>
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
            </div>
        </div>
    </section>
</div>
