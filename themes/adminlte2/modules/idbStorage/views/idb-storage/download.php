<?php

use app\assets\FrontAsset;
use app\assets\SelectAsset;
use app\helpers\Translate;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;
use yii\helpers\Url;

$uploadAssets = FrontAsset::register($this);
$uploadAssets->idbStorageUpload();
$uploadAssets->idbStorageDownload();
$selectAssets = SelectAsset::register($this);

$dbName = BusinessDatabase::findOne(
    ['aid' => Yii::$app->user->identity->aid, 'dbid' => Yii::$app->user->identity->dbid]
);
if ($dbName) {
    $dbName = $dbName->name;
}

$action = Url::previous('idbstorage-summary');

if (empty($action)) {
    $action = Url::previous();
}

$this->title = Html::encode($dbName) . ' - ' . Html::encode($name);

?>

<section class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= $this->title ?>
        </h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-body">
                <?= FlashMessage::widget(
                    [
                        'success' => Yii::$app->session->hasFlash('success')
                            ? Yii::$app->session->getFlash(
                                'success'
                            ) : null,
                        'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash(
                            'error'
                        )
                            : null,
                        'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash(
                            'info'
                        )
                            : null,
                    ]
                ); ?>

                <section class="content">
                    <?= Yii::$app->controller->renderPartial(
                        '@app/themes/adminlte2/views/site/_modalWindow',
                        [
                            'modal' => [
                                'name' => 'cancelFormActionModal',
                                'header' => Translate::_('business', 'Cancel edit your data'),
                                'body' => Translate::_(
                                    'business',
                                    'You have chosen to cancel the edit your data task, your changes will not be saved'
                                ),
                                'question' => Translate::_(
                                    'business',
                                    'If this is not your intention, please click on \'Continue\'.'
                                ),
                                'button' => [
                                    'label' => Translate::_(
                                        'business',
                                        'Back'
                                    ),
                                    'style' => 'margin-bottom: 20px;',
                                    'class' => 'btn btn-primary'
                                ],
                                'leftButton' => [
                                    'label' => Translate::_('business', 'Back'),
                                    'action' => $action,
                                    'style' => 'btn btn-back',
                                ],
                                'rightButton' => [
                                    'label' => Translate::_('business', 'Continue'),
                                    'style' => 'btn btn-primary btn-continue',
                                    'action' => 'data-dismiss'
                                ],
                            ]
                        ]
                    ); ?>
                    <div class="row">
                        <div style="text-align:center;" class="col-md-12">
                            <h2><?= $name ?></h2>
                            <h1 id="download-counter"><?= Translate::_('business', 'Download will start in few seconds.')?></h1>
                            <p><?= Translate::_('business', 'If download doesn\'t start then click button below')?></p>
                            <a download href="<?= $download ?>" class="btn btn-primary"><?= Translate::_('business', 'download') ?></a>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </section>

</section>

<?= BusinessConfig::jsOptions(
    [
        'downloadUrl' => $download,
        'secondsTxt' => ' ' . Translate::_('business', 'second(s)') . '...',
        'downloadTxt' => Translate::_('business', 'Download will start in') . ' ',
        'startedTxt' => Translate::_('business', 'Download started!')
    ]
) ?>
