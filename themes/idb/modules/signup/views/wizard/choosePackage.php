<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;
use app\themes\idb\components\PackageWidget;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$assetBundle->layoutForms($this);
$wizardAsset = IdbWizardAsset::register($this);
$this->title = Translate::_('business', 'Service Plans');

?>

<div class="container">
    <div class="container-inner">
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizard(
                    [
                        'Icon' => 'glyphicon-check',
                        'Title' => Translate::_('business', 'T&Cs')
                    ],
                    [
                        'Icon' => 'glyphicon-th-list',
                        'Title' => Translate::_('business', 'Choose package')
                    ],
                    [
                        'Icon' => 'glyphicon-euro',
                        'Title' => Translate::_('business', 'Payment')
                    ],
                    2
                ) ?>
            </div>
        </div>
        <br>
        <?php $form = ActiveForm::begin(['id' => 'package-form']); ?>
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <div class="sp-column">
                    <div class="sp-module">
                        <div class="sp-module-content">
                            <h2 style="text-align:center; margin-bottom: 40px;"><?= Translate::_(
                                    'business',
                                    'Which Service Plan is best for me? '
                                ) ?></h2>
                            <?php if (!empty($packages)) : ?>
                                <?php foreach ($packages as $package): ?>
                                    <?php
                                    $package = [
                                        'id' => $package[0],
                                        'name' => $package[4],
                                        'currency' => $package[6],
                                        'price' => $package[5],
                                        'included' => $package[8],
                                        'excluded' => $package[9],
                                    ];
                                    ?>

                                    <div class="col-md-4">
                                        <?= PackageWidget::widget(
                                            [
                                                'id' => $package['id'],
                                                'name' => $package['name'],
                                                'priceCurrency' => $package['currency'],
                                                'priceValue' => $package['price'],
                                                'pricePeriod' => 'Mo',
                                                'included' => explode(', ', $package['included']),
                                                'excluded' => explode(', ', $package['excluded'])
                                            ]
                                        ) ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizardActions(
                    [
                        'Text' => Translate::_('business', 'Continue account signup'),
                        'Action' => 'Submit',
                        'Help' => Translate::_('business', 'Continue')
                    ],
                    [
                        'Text' => Translate::_('business', 'Cancel account signup'),
                        'Action' => ['/signup'],
                        'Help' => Translate::_('business', 'Cancel')
                    ]
                ) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    function dropFooterFixedBottom() {
        var footer = document.getElementById("sp-footer");
        footer.classList.remove("navbar-fixed-bottom");
    }

    function initCheckBox() {
        $('input[name="idb_package"]').on('ifChecked', function (event) {
            var package = $(event.target).parent().parent().parent().parent();
            package.css("background-color", "rgb(252, 248, 227)");
        });
        $('input[name="idb_package"]').on('ifUnchecked', function (event) {
            var package = $(event.target).parent().parent().parent().parent();
            package.css("background-color", "white");
        });
        $('input[type=\"radio\"]').iCheck({
            radioClass: 'iradio_flat-orange'
        })
    }
    <?php $this->registerJs("dropFooterFixedBottom();initCheckBox();", View::POS_END); ?>
</script>
