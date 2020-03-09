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
$frontAssets->updateRetentionPeriod();
?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Define storage time limitations for your data')) ?>
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
                        'Icon' => 'glyphicon-book',
                        'Title' => Translate::_('business', 'Data processors')
                    ],
                    [
                        'Icon' => 'glyphicon-time',
                        'Title' => Translate::_('business', 'Storage time limitations')
                    ],
                    [
                        'Icon' => 'glyphicon-list-alt',
                        'Title' => Translate::_('business', 'That’s all!')
                    ],
                    1
                ) ?>
            </div>
        </div>
        <h4 style="text-align: center"><?= Translate::_(
                'business',
                'The key point remains that you must not keep data for longer than you need it.'
            ); ?>
        </h4>
        <h4 style="text-align: center"><?= Translate::_(
                'business',
                'You need to think about – and be able to justify – how long you keep personal data. This will depend on your purposes for holding the data.'
            ); ?>
        </h4>
        <h4 style="text-align: center"><?= Translate::_(
                'business',
                'You must carefully consider any challenges to your retention of data. Individuals have a right to erasure if you no longer need the data.'
            ); ?>
        </h4>
        <br>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="form-group">
                            <h2><?= Translate::_('business', 'Storage time limitations') ?></h2>

                            <?= $form->field($model, 'maximum')->input('number')
                                ->label(Translate::_('business', "Maximum in Days")) ?>

                            <div class="after-maximum <?= empty($model->maximum)?'idb-hidden': ''?>">
                            <?= $form->field($model, 'minimum')->input('number')
                                ->label(Translate::_('business', "Minimum in Days")) ?>

                            <?= $form->field($model, 'onExpiry')
                                ->dropDownList([
                                    'destruction' => 'Destruction',
                                    'pseudonymization' => 'Pseudonymization'
                                ])->label(
                                    Translate::_('business', "On Expiry")
                                ); ?>

                            <?= $form->field($model, 'reviewCycle')->input('number')
                                ->label(Translate::_('business', "Review cycle in Days"))
                                ->hint(Translate::_('business', "If you think you would like to keep data for longer provide review cycle value and we will send reminders."),
                                    ['tag' => 'div', 'class' => 'alert alert-info']) ?>
                            </div>
                            <div class="after-review <?= empty($model->reviewCycle)?'idb-hidden': ''?>">
                                <?= $form->field($model, 'explanation')->textarea(['rows' => 3])
                                    ->label(Translate::_('business', "Explanation")) ?>
                            </div>
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
