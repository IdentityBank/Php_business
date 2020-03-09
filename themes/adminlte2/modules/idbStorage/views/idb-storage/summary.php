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
$selectAssets = SelectAsset::register($this);

$dbName = BusinessDatabase::findOne(
    ['aid' => Yii::$app->user->identity->aid, 'dbid' => Yii::$app->user->identity->dbid]
);
if ($dbName) {
    $dbName = $dbName->name;
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
                                    'action' => Url::previous('idbstorage'),
                                    'style' => 'btn btn-back',
                                ],
                                'rightButton' => [
                                    'label' => Translate::_('business', 'Continue'),
                                    'style' => 'btn btn-primary',
                                    'action' => 'data-dismiss'
                                ],
                            ]
                        ]
                    ); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= Yii::$app->controller->renderPartial(
                                '_summary_file',
                                ['name' => $name, 'download' => $download]
                            ) ?>
                            <?= Yii::$app->controller->renderPartial(
                                '_summary_info',
                                ['metadata' => $metadata, 'createTime' => $createTime]
                            ) ?>
                            <?= Yii::$app->controller->renderPartial(
                                '_summary_download',
                                ['attributes' => $attributes]
                            ) ?>
                        </div>
                        <div class="col-md-6">

                            <?= Yii::$app->controller->renderPartial(
                                '_summary_share',
                                ['share' => $share, 'oId' => $oId, 'model' => $model]
                            ) ?>
                            <?php if($support): ?>
                            <?= Yii::$app->controller->renderPartial(
                                '_summary_preview',
                                ['oId' => $oId]
                            ) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </section>

</section>
