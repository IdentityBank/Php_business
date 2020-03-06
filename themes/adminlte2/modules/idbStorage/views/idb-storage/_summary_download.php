<?php

use app\helpers\Translate;

?>

<div class="info-box bg-yellow">
    <span class="info-box-icon"><i class="fa fa-cloud-download"></i></span>

    <div class="info-box-content">
        <span class="info-box-text"><?= Translate::_('business', 'Downloads') ?></span>
        <span id="summary-downloads" class="info-box-number"><?= ($attributes['downloads'] ?? 0) ?></span>
    </div>
</div>
