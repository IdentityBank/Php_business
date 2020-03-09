<?php

use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\idb\assets\ImportWizardAsset;
use Hoa\File\File;
use idbyii2\helpers\FileHelper;
use idbyii2\helpers\Translate;
use yii\helpers\Html;

/** @var $file \idbyii2\models\db\BusinessImport */
/** @var $worksheet \idbyii2\models\db\BusinessImportWorksheet */

$wizardAsset = ImportWizardAsset::register($this);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'That’s all!')) ?>
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
                        'Title' => Translate::_('business', 'Understand your data')
                    ],
                    [
                        'Icon' => 'glyphicon-edit',
                        'Title' => Translate::_('business', 'Connect with people')
                    ],
                    [
                        'Icon' => 'glyphicon-list-alt',
                        'Title' => Translate::_('business', 'That’s all!')
                    ],
                    2
                ) ?>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12" style="text-align: center">
                        <h2 style="color: darkblue"><?= Translate::_(
                                'business',
                                'We have all the information we need to create your vault and securely store all your data in safes. This might take a little time so we’ll let you know when it’s ready. You will receive a message in the notification centre when the vault is ready for you to use.'
                            ) ?></h2>
                        <h3><?= Translate::_(
                                'business',
                                "The information we are importing is worksheet '{sheet}' from file '{file}'",
                                ['sheet' => $worksheet->name, 'file' => $file->file_path]
                            ) ?></h3>
                        <hr style="alignment: center">
                        <h3><?= Translate::_('business', 'Current status') ?>: <?= FileHelper::getDisplayStatus(
                                $worksheet->status
                            ) ?></h3>
                        <hr style="alignment: center">

                        <h4><?= Html::a(
                                Translate::_('business', 'Show me my data'),
                                ['/idb-menu', 'dbid' => $worksheet->dbid, 'action' => '/idbdata/idb-data/show-all'],
                                [
                                    'class' => 'btn btn-lg btn-success',
                                    'name' => 'close-button',
                                    'title' => ($worksheet->name ?? '')
                                ]
                            ); ?>
                        </h4>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    setTimeout(function () {
        window.location.replace(window.location.href);
    }, 30000);
</script>
