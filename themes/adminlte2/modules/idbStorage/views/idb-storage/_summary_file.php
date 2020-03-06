<?php

use app\helpers\Translate;
use yii\helpers\Html;

?>

<div class="small-box bg-aqua">
    <?= Html::a(
        '',
        $download,
        [
            'class' => 'a-mask',
            'data-toggle' => "tooltip",
            'data-placement' => "top",
            'title' => Translate::_('business', 'Download file')
        ]
    ); ?>
    <div class="inner">
        <h3><?= Html::encode(mb_strimwidth($name, 0, 45, '...'), ENT_QUOTES, "UTF-8") ?></h3>
        <p>&nbsp;<BR>&nbsp;<BR></p>
    </div>
    <div class="icon">
        <i class="fa fa-file-o"></i>
    </div>
    <a href="<?= $download ?>" class="small-box-footer" id="summary-download">
        <?= Translate::_('business', 'Download file') ?> <i class="fa fa-arrow-circle-right"></i>
    </a>
</div>
