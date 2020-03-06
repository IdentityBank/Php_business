<?php

use app\assets\UploadAsset;
use app\helpers\BusinessConfig;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\idb\assets\ImportWizardAsset;
use idbyii2\helpers\FileHelper;
use idbyii2\widgets\FlashMessage;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$wizardAsset = ImportWizardAsset::register($this);
$assetUploadBundle = UploadAsset::register($this);
$assetUploadBundle->toolsUpload($this);

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(($dbidInfo['name'] ?? '')) . ' - ' . Translate::_('business', 'Select a file to import') ?>
        </h1>

        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <?= FlashMessage::widget(
        [
            'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success') : null,
            'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : null,
            'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : null,
        ]
    ); ?>

    <section class="content">
        <div class="row">
            <div class="col-lg-12" style="float: none;margin: 0 auto;">
                <?= $wizardAsset->generateWizard(
                    [
                        'Icon' => 'glyphicon-open',
                        'Title' => Translate::_('business', 'Select a vault')
                    ],
                    [
                        'Icon' => 'glyphicon-open',
                        'Title' => Translate::_('business', 'Select a file to import')
                    ],
                    [
                        'Icon' => 'glyphicon-file',
                        'Title' => Translate::_('business', 'Select worksheet')
                    ],
                    $backButton ? 1 : 0
                ) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h2>
                    <?= Html::encode(Translate::_('business', 'Select a file to import')) ?>
                </h2>

                <div id="status_success" class="callout callout-success" style="display:none">
                    <?php $url = Url::toRoute(['import/index'], true); ?>
                    <h4><?= Translate::_('business', 'File successfully sent.') ?></h4>
                    <p id="success_msg"><?= Translate::_('business', 'Your file has been uploaded correctly.') ?></p>
                    <p id="success_msg"><?= Translate::_('business', 'Here is the list of all your files: ') ?><a
                                href="<?= $url; ?>"><?= Translate::_('business', 'List of files'); ?></a></p>
                </div>

                <div id="staus_error" class="callout callout-danger" style="display:none">
                    <h4><?= Translate::_('business', 'File send failed!') ?></h4>
                    <p id="error_msg"></p>
                    <p id="retry_button"><?= Html::a(
                            '<i class="fa fa-repeat"></i>&nbsp;' . Translate::_('business', 'Retry'),
                            ['/tools/wizard/index'],
                            ['class' => 'btn btn-block btn-danger btn-flat']
                        ) ?></p>
                </div>

                <div id="upload_dropzone" class="box box-default">
                    <div class="box-body">

                        <div>
                            <form action=<?= Url::toRoute(['/tools/wizard/file'], true); ?> class="dropzone needsclick
                                  dz-clickable
                            " id="upload">
                            <div class="dz-message needsclick">
                                <?= Translate::_('business', 'Drop files here or click to upload.') ?><br>
                                <span class="note needsclick">(<?= Translate::_(
                                        'business',
                                        'Use CSV or Excel files.'
                                    ) ?>)</span>
                                <span class="note needsclick">(<?= Translate::_(
                                        'business',
                                        'Maximum upload file size: {maxFilesize} MB',
                                        ['maxFilesize' => BusinessConfig::get()->getYii2BusinessUploadMaxFilesize()]
                                    ) ?>)</span>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div id="upload_dropzone_overlay" class="overlay" style="display:none">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
                <div id="progress_block" class="box box-default" style="display:none">
                    <div class="box-header with-border">
                        <h3 id="progress_message" class="box-title"></h3>
                    </div>
                    <div class="box-body">
                        <div class="progress">
                            <div class="progress-bar progress-bar-primary progress-bar-striped" id="pb"
                                 role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                 style="width: 0%">
                                0%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($dataProvider->getTotalCount() > 0) : ?>
            <div class="col-xs-12">

                <div class="box-body">
                    <h2>
                        <?= Html::encode(Translate::_('business', 'Or use a previously imported file')) ?>
                    </h2>
                    <?= Html::beginForm([''], 'post'); ?>
                    <?php Pjax::begin(); ?>

                    <?= GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'showHeader' => true,
                            'columns' => [
                                [
                                    'class' => 'yii\grid\RadioButtonColumn',
                                    'radioOptions' => function ($model, $key) {
                                        if (
                                            $model->status == FileHelper::STATUS_ADDED
                                            || FileHelper::STATUS_ERROR
                                        ) {
                                            return ['value' => $key];
                                        }

                                        return ['style' => ['display' => 'none']];
                                    }
                                ],
                                'file_path:ntext',
                                'created_at:date',
                                [
                                    'attribute' => 'status',
                                    'content' => function ($model) {
                                        return FileHelper::getDisplayStatus($model->status);
                                    }
                                ],
                            ],
                        ]
                    ); ?>
                    <?php Pjax::end(); ?>
                    <?= Html::submitButton(
                        Translate::_("business", "Continue"),
                        ['class' => 'btn btn-primary pull-right']
                    ); ?>
                    <?= Html::endForm(); ?>
                    <?php if ($backButton): ?>
                        <?= Html::a(
                            \idbyii2\helpers\Translate::_("business", "Back"),
                            ["/tools/wizard/$backButton"],
                            ['class' => 'btn btn-back pull-left']
                        ); ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<script>

    var translation = new (function () {
        return {
            'progress_message_checksum': '<?= Translate::_('business', "Calculating file checksum.") . " "
            . Translate::_('business', "Please wait") . " ..." ?>',
            'progress_message_upload': '<?= Translate::_('business', "Uploading file to the server.") . " "
            . Translate::_('business', "Please wait") . " ..." ?>',
            'error_msg': '<?= Translate::_(
                'business',
                "A problem was encountered while attempting to upload your file. Please try again."
            ) ?>',
            'error_msg_empty_file': '<?= Translate::_('business', "You cannot upload empty file.") ?>',
            'error_msg_file_to_big': '<?= Translate::_(
                'business',
                "Uploaded file is too big. The maximum allowed size is {maxFilesize} MiB.",
                ['maxFilesize' => BusinessConfig::get()->getYii2BusinessUploadMaxFilesize()]
            ) ?>',
            'error_msg_accepted_files': '<?= Translate::_(
                'business',
                "You can\'t upload files of this type. Supported file types: {acceptedFiles}",
                [
                    'acceptedFiles' => str_replace(
                        '.',
                        '',
                        BusinessConfig::get()->getYii2BusinessUploadAcceptedFilesString()
                    )
                ]
            ) ?>',
            'upload_disabled_msg': '<?= Translate::_('business', "Upload is disabled.") ?>',
        };
    })();

    var dropzone_options_upload = new (function () {
        return {
            maxFilesize: <?= BusinessConfig::get()->getYii2BusinessUploadMaxFilesize() ?>,
            timeout: <?= BusinessConfig::get()->getYii2BusinessUploadTimeout() ?>,
            chunkSize: <?= BusinessConfig::get()->getYii2BusinessUploadChunkSize() ?>,
            acceptedFiles: '<?= BusinessConfig::get()->getYii2BusinessUploadAcceptedFilesString() ?>',
        };
    })();

    <?php if(!BusinessConfig::get()->getYii2BusinessUploadEnabled()) { ?>
    var uploadDisabled = true;
    <?php } ?>

</script>
