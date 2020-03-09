<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;
use yii\helpers\Html;
use yii\web\View;

$assetBundle = AdminLte2AppAsset::register($this);
$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutMain($this);
$assetBundle->layoutForms($this);
$wizardAsset = IdbWizardAsset::register($this);

$this->title = Translate::_('business', 'Payment successful and account signup complete!');

?>

<div class="container">
    <div class="container-inner">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12" style="float: none;margin: 0 auto; margin-bottom: 16px;">
                    <?= $wizardAsset->generateWizard(
                        [
                            'Icon' => 'glyphicon-euro',
                            'Title' => Translate::_('business', 'Payment')
                        ],
                        [
                            'Icon' => 'glyphicon-eye-open',
                            'Title' => Translate::_('business', 'Auth')
                        ],
                        [
                            'Icon' => 'glyphicon-ok',
                            'Title' => Translate::_('business', 'Success')
                        ],
                        3
                    ) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <div class="sp-column">
                    <div class="sp-module">
                        <div class="sp-module-content" style="color: black;">
                            <div class="invoice">
                                <div class="row">
                                    <div class="col-xs-12 signup-text-color">
                                        <h2 class="page-header">
                                            <b><?= Translate::_('business', 'Thank you for your payment!') ?></b>
                                        </h2>
                                        <?= Translate::_(
                                            'business',
                                            'Your account has been successfully created and is now ready for you to login. Before you login to your account, please take a moment to save your account details as shown below and download, print, and securely store your printed account password recovery token.'
                                        ) ?>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-xs-12 signup-text-color">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-xs-2" align="right">
                                                    <button id="copy-login-button" class="btn btn-app bg-dark">
                                                        <i class="fa fa-copy"></i> <?= Translate::_(
                                                            'business',
                                                            'Copy to clipboard'
                                                        ) ?>
                                                    </button>
                                                </div>
                                                <div class="col-xs-7">
                                                    <p><b><?= Translate::_('business', 'Login Name') ?>
                                                            :</b> <?= $login->userId ?></p>
                                                    <p><b><?= Translate::_('business', 'Account Number') ?>
                                                            :</b> <?= $login->accountNumber ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <p><b><?= Translate::_('business', 'Password Recovery Token') ?>:</b></p>
                                        <div class="row">
                                            <div class="col-xs-3" align="center" style="padding: 15px;">
                                                <?=
                                                Html::a(
                                                    '<i class="fa far fa-save"></i> ' . Translate::_(
                                                        'business',
                                                        'Download'
                                                    ),
                                                    ['get-token', 'id' => $id],
                                                    [
                                                        'class' => 'btn btn-danger btn-block',
                                                        'id' => "id-button-recovery-token",
                                                        'target' => '_blank',
                                                        'data-toggle' => 'tooltip',
                                                        'title' => Translate::_(
                                                            'business',
                                                            'Will open the generated Password token in new window'
                                                        )
                                                    ]
                                                );
                                                ?>
                                            </div>
                                            <div class="col-xs-9" id="id-confirmation-recovery-token"
                                                 style="padding: 15px;">
                                                <label>
                                                    <input type="checkbox" id="id-password-recovery-token-downloaded"
                                                           name="PasswordRecoveryTokenDownloaded"
                                                           aria-required="true" required/>&nbsp;
                                                    <?= Translate::_(
                                                        'business',
                                                        'I have downloaded the Password Recovery Token, printed it out, and securely stored the printed copy. I have permanently deleted all copies of the downloaded PDF file from my devices.'
                                                    ) ?>
                                                    &nbsp;
                                                </label>
                                            </div>
                                        </div>
                                        <hr>
                                        <?php $buttonText = Translate::_(
                                            'business',
                                            'Click here to proceed and login to your account'
                                        ); ?>
                                        <?php $buttonHint = Translate::_(
                                            'business',
                                            'You must confirm the action above and click the check box to continue.'
                                        ); ?>
                                        <?= Html::a(
                                            $buttonText,
                                            ['/login'],
                                            [
                                                'id' => 'login-portal-button',
                                                'class' => 'btn btn-warning btn-lg btn-block',
                                                'style' => 'margin-top: 5px; display: none;'
                                            ]
                                        ) ?>
                                        <?= Html::button(
                                            $buttonText,
                                            [
                                                'id' => 'login-portal-button-not-active',
                                                'class' => 'btn btn-warning btn-lg btn-block',
                                                'style' => 'margin-top: 5px;',
                                                'onmouseover' => 'clickNotActive("onmouseover")',
                                                'onmouseout' => 'clickNotActive("onmouseout")',
                                                'title' => $buttonHint
                                            ]
                                        ) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function clickNotActive(action) {
        if (action == "onmouseover") {
            $('#id-confirmation-recovery-token').addClass("bg-red-active");
        } else {
            $('#id-confirmation-recovery-token').removeClass("bg-red-active");
        }
    }

    function copyLoginData() {
        let textArea = document.createElement("textarea");
        textArea.style.position = 'fixed';
        textArea.style.top = 0;
        textArea.style.left = 0;
        textArea.style.width = '2em';
        textArea.style.height = '2em';
        textArea.style.padding = 0;
        textArea.style.border = 'none';
        textArea.style.outline = 'none';
        textArea.style.boxShadow = 'none';
        textArea.style.background = 'transparent';
        textArea.style.textAlign = 'middle';
        textArea.value = '<?= Translate::_('business', 'Login Name') . ': ' . $login->userId . '\\n' ?>';
        textArea.value += '<?= Translate::_('business', 'Account Number') . ': ' . $login->accountNumber ?>';
        document.body.appendChild(textArea);

        textArea.select();
        document.execCommand('copy');

        document.body.removeChild(textArea);

        $('#copy-login-button').prop('disabled', true);
        $('#copy-login-button').removeClass("bg-dark").addClass("bg-green-active");


        setTimeout(function () {
            $('#copy-login-button').removeClass("bg-green-active").addClass("bg-dark");
            $('#copy-login-button').prop('disabled', false);
        }, 10000);
    }

    function initForm() {
        $('#login-portal-button-not-active').attr("disabled", true);
        $("#copy-login-button").click(function (event) {
            event.preventDefault();
            copyLoginData();
        });
        $('input[type=\"checkbox\"], input[type=\"radio\"]').iCheck({
            checkboxClass: 'icheckbox_flat-orange',
            radioClass: 'iradio_flat-orange'
        });
        $('#id-password-recovery-token-downloaded').on("ifChanged", function () {
            if (this.checked) {
                $('#login-portal-button-not-active').hide();
                $('#login-portal-button').show();
                $('#id-password-recovery-token-downloaded').prop("disabled", true);
            }
        });
    }
    <?php $this->registerJs("initForm();", View::POS_END); ?>
</script>
