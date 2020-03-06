<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;
use idbyii2\helpers\StaticContentHelper;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$assetBundle->layoutForms($this);
$wizardAsset = IdbWizardAsset::register($this);
$this->title = Translate::_('business', 'Terms and Conditions');

$tacContent = StaticContentHelper::getTermsAndConditions(Yii::$app->language);
$tacDateTime = strtok($tacContent, "\n");
$tacContent = preg_replace('/^.+\n/', '', $tacContent);
const SEND_EMAIL_ENABLED = false;

?>

<div class="container">
    <div class="container-inner">
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizard(
                    [
                        'Icon' => 'glyphicon-user',
                        'Title' => Translate::_('business', 'Form')
                    ],
                    [
                        'Icon' => 'glyphicon-check',
                        'Title' => Translate::_('business', 'T&Cs')
                    ],
                    [
                        'Icon' => 'glyphicon-th-list',
                        'Title' => Translate::_('business', 'Choose package')
                    ],
                    2
                ) ?>
            </div>
        </div>
        <br>
        <?php $form = ActiveForm::begin(['id' => 'tac-form']); ?>
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <div class="sp-column">
                    <div class="sp-module">
                        <div class="sp-module-content" style="color: black;">
                            <div class="invoice">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h2 class="page-header">
                                            &nbsp;
                                            <small class="pull-right"><?= $tacDateTime ?></small>
                                        </h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div id="tac-textarea" style="overflow-y: scroll; height:300px;">
                                                <?= $tacContent ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h2 class="page-header"></h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="alert alert-wizard">
                                        <strong>
                                            <?= Translate::_(
                                                'business',
                                                'Please read our Term and Conditions as given above.'
                                            ) ?>
                                        </strong>
                                        <strong id="tac-scroll-info">
                                            <br>
                                            <?= Translate::_(
                                                'business',
                                                'You have to scroll to the bottom to continue.'
                                            ) ?>
                                        </strong>
                                        <div id="tac-actions" style="display: none;">
                                            <label>
                                                <input type="checkbox" name="TermsAndConditionsAgreement"
                                                       aria-required="true" required/>&nbsp;
                                                <?= Translate::_(
                                                    'business',
                                                    'When you have done this, and are in agreement, click on the checkbox and then the green arrow to proceed.'
                                                ) ?>
                                                &nbsp;
                                            </label>
                                            <?php if (SEND_EMAIL_ENABLED): ?>
                                                <label>
                                                    <?= Translate::_(
                                                        'business',
                                                        'Send me Identity Bank Terms and Conditions via email.'
                                                    ) ?>
                                                    &nbsp;
                                                    <input type="checkbox" name="SendTermsAndConditions" checked/>&nbsp;
                                                </label>
                                            <?php endif ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div id="tac-buttons" class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizardActions(
                    [
                        'Text' => Translate::_('business', 'Continue account signup'),
                        'Action' => 'Submit',
                        'Id' => 'tac-buttons-next',
                        'Help' => Translate::_('business', 'Continue')
                    ],
                    [
                        'Text' => Translate::_('business', 'Cancel account signup'),
                        'Action' => ['/signup'],
                        'Help' => Translate::_(
                            'business',
                            'Your account signup will be stopped. You can completely restart the account signup again at any time. If you didn\'t mean to do this, click on the green arrow to continue.'
                        )
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
        $('#tac-buttons-next').attr("disabled", true);
        $('#tac-textarea').on('scroll', function () {
            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                $('#tac-actions').show();
                $('#tac-scroll-info').hide();
                $('#tac-buttons-next').attr("disabled", false);
            }
        });
        $('input[type=\"checkbox\"], input[type=\"radio\"]').iCheck({
            checkboxClass: 'icheckbox_flat-orange',
            radioClass: 'icheckbox_flat-orange'
        });
    }
    <?php $this->registerJs("dropFooterFixedBottom();initCheckBox();", View::POS_END); ?>
</script>
