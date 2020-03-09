<?php

use app\assets\UploadAsset;
use app\helpers\BusinessConfig;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\enums\AvailableLanguage;
use idbyii2\enums\EmailActionType;
use idbyii2\models\db\BusinessEmailTemplate;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Url;

/** @var BusinessEmailTemplate[] $emailTemplatesByISO */
/** @var string $action */
/** @var string $iso */

$assetUploadBundle = UploadAsset::register($this);
$assetUploadBundle->toolsUpload($this);

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= constant(EmailActionType::class . '::' . $action) ?>: <?= constant(
                AvailableLanguage::class . '::' . $iso
            ) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <?= FlashMessage::widget(
                            [
                                'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash(
                                    'success'
                                ) : null,
                                'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error')
                                    : null,
                                'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info')
                                    : null,
                            ]
                        ); ?>
                        <div class="mail-miniatures">

                            <?php if (!empty($emailTemplatesByISO[$iso])): ?>
                                <?php foreach ($emailTemplatesByISO[$iso] as $template): ?>
                                    <div class="miniature-box">
                                        <div class="miniature-wrap">
                                            <iframe scrolling="no" class="mail-miniature"
                                                    srcdoc='<?= $template['data'] ?>'></iframe>
                                            <div class="miniature-overlay"></div>
                                        </div>
                                        <a
                                                href="<?= Url::toRoute(
                                                    ['active', 'id' => $template['info']->id],
                                                    true
                                                ) ?>"
                                                class="btn btn-primary <?= $template['info']->active ? 'btn-selected'
                                                    : '' ?>"
                                        >
                                            <?= $template['info']->active
                                                ?
                                                Translate::_('business', 'SELECTED')
                                                :
                                                Translate::_('business', 'SELECT')
                                            ?>
                                        </a>
                                        <a
                                                href="<?= Url::toRoute(
                                                    ['delete', 'id' => $template['info']->id],
                                                    true
                                                ) ?>"
                                                class="btn btn-danger"
                                                onclick="return confirm('<?= Translate::_(
                                                    'business',
                                                    'Are you sure you want to delete this template?'
                                                ) ?>');"
                                        >
                                            <?= Translate::_('business', 'DELETE') ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <h4><?= Translate::_('business', 'No template available for this language.') ?></h4>
                                <h4><?= Translate::_('business', 'You can download base template above.') ?></h4>
                            <?php endif; ?>

                            <div class="miniature-box">
                                <div class="miniature-wrap">
                                    <iframe scrolling="no" class="mail-miniature" src="<?= Url::toRoute(
                                        ['get-base-template', 'action' => $action, 'iso' => $iso],
                                        true
                                    ) ?>"></iframe>
                                    <div class="miniature-overlay"></div>
                                </div>
                                <a
                                        href="<?= Url::toRoute(
                                            ['get-base-template', 'action' => $action, 'iso' => $iso],
                                            true
                                        ) ?>"
                                        class="btn btn-danger"
                                        download="BASE-<?= $action . '-' . $iso ?>.html"
                                >
                                    <?= Translate::_('business', 'DOWNLOAD') ?>
                                </a>
                            </div>
                        </div>

                        <div id="upload_dropzone" class="box box-default">
                            <div class="box-body">

                                <div>
                                    <form action="<?= Url::toRoute(['/configuration/email-templates/upload'], true); ?>"
                                          class="dropzone needsclick dz-clickable" id="upload">
                                        <input type="hidden" name="language" value="<?= $iso ?>"/>
                                        <input type="hidden" name="action_type" value="<?= $action ?>"/>
                                        <div class="dz-message needsclick">
                                            <?= Translate::_('business', 'Drop files here or click to upload.') ?><br>
                                            <span class="note needsclick">(<?= Translate::_(
                                                    'business',
                                                    'Use a HTML file'
                                                ) ?>)</span>
                                            <span class="note needsclick">(<?= Translate::_(
                                                    'business',
                                                    'Maximum upload file size: {maxFilesize} MB',
                                                    [
                                                        'maxFilesize' => BusinessConfig::get()
                                                                                       ->getYii2BusinessUploadMaxFilesize(
                                                                                       )
                                                    ]
                                                ) ?>)</span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="upload_dropzone_overlay" class="overlay" style="display:none">
                                <i class="fa fa-refresh fa-spin"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>

    let translation = new (function () {
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

    let dropzone_options_upload = new (function () {
        return {
            maxFilesize: <?= BusinessConfig::get()->getYii2BusinessUploadMaxFilesize() ?>,
            timeout: <?= BusinessConfig::get()->getYii2BusinessUploadTimeout() ?>,
            chunkSize: <?= BusinessConfig::get()->getYii2BusinessUploadChunkSize() ?>,
            acceptedFiles: '.html',
        };
    })();

    <?php if(!BusinessConfig::get()->getYii2BusinessUploadEnabled()) { ?>
    let uploadDisabled = true;
    <?php } ?>

</script>
