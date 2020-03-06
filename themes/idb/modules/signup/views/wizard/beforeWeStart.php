<?php

use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\View;

BootstrapPluginAsset::register($this);
$wizardAsset = IdbWizardAsset::register($this);
$this->title = Translate::_('business', 'Open an Identity Bank Business Account');

?>
<div class="container">
    <div class="container-inner">
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizard(
                    [
                        'Icon' => 'glyphicon-circle-arrow-right',
                        'Title' => Translate::_('business', 'Welcome')
                    ],
                    [
                        'Icon' => 'glyphicon-cog',
                        'Title' => Translate::_('business', 'Before we start')
                    ],
                    [
                        'Icon' => 'glyphicon-briefcase',
                        'Title' => Translate::_('business', 'Business Details')
                    ],
                    2
                ) ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <div class="sp-column">
                    <div class="sp-module">
                        <div class="sp-module-content">
                            <div style="text-align: center;">
                                <h2><?= Translate::_(
                                        'business',
                                        'Before starting your account setup we strongly recommend you do these tasks.'
                                    ) ?></h2>
                            </div>
                            <hr style="border:0;padding: 10px;">
                            <div class="row" align="center">
                                <?php if (empty($id)) : ?>
                                    <div class="col-lg-5">
                                        <div>
                                            <div class="panel-body">
                                                <a href="#businessEmailAddressInfoBody"
                                                   onclick="expandAction(event)"
                                                   role="button" data-toggle="collapse"
                                                   id="businessEmailAddressActionPanel"
                                                   aria-expanded="true"
                                                   aria-controls="businessEmailAddressInfoBody"
                                                   style="color: #091973 !important;">
                                                <span class="glyphicon glyphicon-envelope"
                                                      style="font-size: 75px;"></span>
                                                    <hr style="border:0;padding: 2px;">
                                                    <h2 style="font-size:x-large;"><b><?= Translate::_(
                                                                'business',
                                                                'Create a new email address'
                                                            ) ?></b></h2>
                                                </a>
                                            </div>
                                            <div>
                                                <div class="panel-group" role="businessEmailAddress">
                                                    <div>
                                                        <div class="panel-collapse collapse" role="tabpanel"
                                                             id="businessEmailAddressInfoBody"
                                                             aria-labelledby="businessEmailAddressInfoBody"
                                                             aria-expanded="true">
                                                            <h2 style="font-size:x-large;"><?= Translate::_(
                                                                    'business',
                                                                    'This new email should only be used for Identity Bank. This email will be used to create your Identity Bank primary master account. This is why it’s a good idea to keep this account free from other email use.'
                                                                ) ?></h2>
                                                            <h2 style="font-size:x-large;"><?= Translate::_(
                                                                    'business',
                                                                    'Confirmation emails will be sent to this email address during the account signup. This means the <b>email address must be fully operational before proceeding.</b> Your business IT administrator should be able to create a new business email address if you need help to do this.'
                                                                ) ?></h2>
                                                            <h2 style="font-size:x-large;"><?= Translate::_(
                                                                    'business',
                                                                    'You can skip this step if you are happy using your usual business email address.'
                                                                ) ?></h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="<?= ((empty($id)) ? 'col-lg-5 col-md-offset-2'
                                    : 'col-lg-6 col-md-offset-3') ?>">
                                    <div>
                                        <div class="panel-body">
                                            <a href="#businessPasswordManagerInfoBody"
                                               onclick="expandAction(event)"
                                               role="button" data-toggle="collapse"
                                               id="businessPasswordManagerActionPanel"
                                               aria-expanded="true"
                                               aria-controls="businessPasswordManagerInfoBody"
                                               style="color: #091973 !important;">
                                                <span class="glyphicon glyphicon-lock" style="font-size: 75px;"></span>
                                                <hr style="border:0;padding: 2px;">
                                                <h2 style="font-size:x-large;"><b><?= Translate::_(
                                                            'business',
                                                            'Use secure passwords'
                                                        ) ?></b></h2>
                                            </a>
                                        </div>
                                        <div>
                                            <div class="panel-group" role="businessPasswordManager">
                                                <div>
                                                    <div class="panel-collapse collapse" role="tabpanel"
                                                         id="businessPasswordManagerInfoBody"
                                                         aria-labelledby="businessPasswordManagerInfoBody"
                                                         aria-expanded="true">
                                                        <h2 style="font-size:x-large;"><?= Translate::_(
                                                                'business',
                                                                'We strongly recommend using a password manager app to create and store very strong passwords which are impossible to remember.'
                                                            ) ?></h2>
                                                        <h2 style="font-size:x-large;"><?= Translate::_(
                                                                'business',
                                                                'There are many free and commercial options available, for example: Enpass, LastPass, or Dashlane.'
                                                            ) ?></h2>
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
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizardActions(
                    [
                        'Text' => Translate::_('business', 'Start account signup'),
                        'Action' => ((empty($id))
                            ? ['/signup/business-details']
                            : [
                                '/signup/register/form',
                                'id' => $id
                            ]),
                        'Help' => Translate::_('business', 'Continue')
                    ]
                ) ?>
            </div>
        </div>
    </div>
</div>

<script>
    function dropFooterFixedBottom() {
        var footer = document.getElementById("sp-footer");
        footer.classList.remove("navbar-fixed-bottom");
    }

    function initView() {
        dropFooterFixedBottom();
    }

    function expandAction(e) {

        var idValue = '';
        try {
            idValue = e.target.parentElement.attributes.id.value;
        } catch (e) {
        }
        try {
            idValue = e.target.attributes.id.value;
        } catch (e) {
        }

        if (idValue === 'businessEmailAddressActionPanel'
            || idValue === 'businessEmailAddressAction') {
            $('#businessEmailAddressInfo').hide();
        }
        if (idValue === 'businessPasswordManagerActionPanel'
            || idValue === 'businessPasswordManagerAction') {
            $('#businessPasswordManagerInfo').hide();
        }
    }
    <?php $this->registerJs("initView();", View::POS_END); ?>
</script>
