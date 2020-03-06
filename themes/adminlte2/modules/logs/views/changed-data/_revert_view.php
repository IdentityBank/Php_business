<?php

use app\helpers\Translate;

?>

<div>
    <ul class="timeline timeline-inverse">
        <li class="time-label">
            <input type="checkbox" name="data[<?= $key ?>]"
                   value="<?= htmlentities($type['old_value'], ENT_QUOTES, "UTF-8") ?>"/>
            &nbsp;
            <span class="bg-blue">
                <?= empty($type['display_name']) ? '&nbsp;' : $type['display_name'] ?>
            </span>
        </li>
        <li>
            <i class="fa fa-arrow-right bg-green"></i>

            <div class="timeline-item">
                <span class="time"><?= Translate::_('business', 'Restore value') ?></span>
                <h4 class="timeline-header no-border"><?= empty($type['old_value']) ? '&nbsp;'
                        : htmlentities($type['old_value'], ENT_QUOTES, "UTF-8") ?></h4>
            </div>
        </li>
        <li>
            <i class="fa fa-reply-all bg-red"></i>
        </li>
    </ul>
    <input type="hidden" name="name[<?= $key ?>]" value="<?= $type['display_name'] ?>"/>
</div>
