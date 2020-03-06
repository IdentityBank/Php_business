<?php

use app\helpers\Translate;
use yii\helpers\Url;

?>

<div>
    <ul class="timeline timeline-inverse">
        <li class="time-label">
            <span class="bg-red-active">
                <?= Translate::_(
                    'business',
                    'File shared with {share_count,number} {share_count, plural, =0{nobody} =1{person} other{peoples}}',
                    ['share_count' => count($share)]
                ) ?>
            </span>
        </li>
        <?php foreach ($share as $shareItem) : ?>
            <li class="timeline-link" onclick="window.location = '<?= Url::toRoute(['index', 'uuid' => $shareItem['id']]) ?>'">
                <i class="fa fa-user-check bg-green"></i>

                <div class="timeline-item">
                    <span class="time"></span>
                    <h4 class="timeline-header no-border"><?= $shareItem['value'] ?></h4>
                </div>
            </li>
        <?php endforeach; ?>
        <li>
            <i class="fa fa-file-o bg-blue"></i>
        </li>
    </ul>
</div>
