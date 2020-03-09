<?php

use app\helpers\Translate;

?>

<div>
    <ul class="timeline timeline-inverse">
        <li class="time-label">
            <span class="bg-blue">
                <?= empty($type['display_name']) ? '&nbsp;' : $type['display_name'] ?>
            </span>
        </li>
        <li>
            <i class="fa fa-arrow-right bg-green"></i>

            <div class="timeline-item">
                <span class="time"><?= Translate::_('business', 'New value') ?></span>
                <h4 class="timeline-header no-border"><?= empty($type['value'])
                        ? '&nbsp;'
                        : htmlentities(
                            $type['value'],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?></h4>
            </div>
        </li>
        <li>
            <i class="fa fa-trash-o bg-red"></i>

            <div class="timeline-item">
                <span class="time"><?= Translate::_('business', 'Old value') ?></span>
                <h4 class="timeline-header no-border"><?= empty($type['old_value']) ? '&nbsp;'
                        : htmlentities($type['old_value'], ENT_QUOTES, "UTF-8") ?></h4>
            </div>
        </li>
        <li>
            <i class="fa fa-database bg-gray"></i>
        </li>
    </ul>
</div>
