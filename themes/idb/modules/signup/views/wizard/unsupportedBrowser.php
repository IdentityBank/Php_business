<?php

use app\helpers\Translate;
use app\themes\idb\assets\IdbWizardAsset;

$wizardAsset = IdbWizardAsset::register($this);
$this->title = Translate::_('business', 'Your Browser is not supported');

?>
<div class="container">
    <div class="container-inner">
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <div class="sp-column">
                    <div class="sp-module">
                        <div class="sp-module-content">
                            <div style="text-align: center;">
                                <h2>
                                    <strong>
                                        <?= Translate::_(
                                            'business',
                                            'We do not support older versions of browsers for security reasons.'
                                        ) ?>
                                    </strong>
                                </h2>
                            </div>
                            <hr style="border:0px;padding: 3px;">
                            <div style="text-align: center;">
                                <h2 style="text-align: center;">
                                    <?= Translate::_(
                                        'business',
                                        'Please ensure you use a browser that has been kept fully up to date.'
                                    ) ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
