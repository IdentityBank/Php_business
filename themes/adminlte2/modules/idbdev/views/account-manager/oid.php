<?php

use app\assets\SelectAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\models\db\BusinessOrganization;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$model = new BusinessOrganization();
$oids = BusinessOrganization::find()->asArray()->all();

$assetSelectBundle = SelectAsset::register($this);
$this->registerJs(
    <<< EOT_JS_CODE

    $(document).ready(function() {
        $("#businessorganization-oid").select2({
            width: "100%",
        });
    });

EOT_JS_CODE
);

?>

<style>
    .select2-container .select2-selection--single .select2-selection__rendered {
        margin-top: -7px;
        padding-left: 2px;
        padding-right: 2px;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Business Account Manager')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <?= Translate::_('business', 'Please select an organization') ?>
                    </div>
                    <div class="box-body">

                        <?php $form = ActiveForm::begin(['action' => Url::toRoute(['index'], true)]); ?>

                        <div class="form-group">
                            <?= Html::activeDropDownList($model, 'oid', ArrayHelper::map($oids, 'oid', 'name')) ?>
                        </div>

                        <div class="form-group">
                            <?= Html::submitButton(
                                Translate::_('business', 'Select'),
                                ['class' => 'btn btn-success']
                            ) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
