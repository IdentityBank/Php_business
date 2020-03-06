<?php

use app\assets\FrontAsset;
use app\helpers\Translate;
use yii\helpers\Html;

if($type === 'pdf') {
    $frontAsset = FrontAsset::register($this);
    $frontAsset->idbStoragePreview();
}

?>

    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                <?= Html::encode(Translate::_('business', 'Preview File')) ?>
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <div id="back">
                                <?= Html::a(
                                    Translate::_('business', 'Back'),
                                    Yii::$app->request->referrer,
                                    ['class' => 'btn btn-primary']
                                ); ?>
                            </div>
                            <br>
                            <?php if ($type === 'audio'): ?>
                                <div class="jumbotron jumbo-player">
                                    <h3 class="display-4 center-player"><?= $name ?></h3>
                                    <div id="player" class="center-player">
                                        <audio controls>
                                            <source src="<?= $src ?>" type="<?= $format ?>">
                                            <?= Translate::_(
                                                'business',
                                                'Your browser does not support the audio element.'
                                            ) ?>
                                        </audio>
                                    </div>
                                </div>
                            <?php elseif($type === 'pdf') : ?>
                                <div class="center-viewer">
                                    <canvas id="canvas-preview"></canvas>
                                </div>
                            <?php else : ?>
                                <p><?= Translate::_('business', 'Preview is not available')?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .center-player, .center-viewer {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .jumbo-player {
            background-color: #00a65a;
        }
    </style>

<?php if($type == 'pdf'): ?>
    <?= BusinessConfig::jsOptions(
        [
            'url' => $src,
        ]
    ) ?>
<?php endif; ?>