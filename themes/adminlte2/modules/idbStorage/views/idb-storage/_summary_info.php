<?php

use app\helpers\Translate;
use idbyii2\helpers\File;

?>

<div class="info-box bg-green">
    <span class="info-box-icon"><i class="fa fa-file-text-o"></i></span>

    <div class="info-box-content">
        <span class="info-box-text"><?= Translate::_('business', 'Metadata') ?></span>
        <div>
            <ul class="timeline timeline-inverse">
                <li>
                    <i class="fa fa-file-o bg-blue-active"></i>
                </li>
                <li>
                    <i class="fa fa-clock-o bg-yellow-active"></i>

                    <div class="timeline-item bg-white">
                        <span id="summary-createtime" class="time"><?= $createTime ?></span>
                        <h4 class="timeline-header no-border"><?= Translate::_('business', 'Upload time') ?></h4>
                    </div>
                </li>
                <li>
                    <i class="fa fa-tag bg-yellow-active"></i>

                    <div class="timeline-item bg-white">
                        <span class="time" id="summary-size"><?= File::formatSize($metadata['size']) ?></span>
                        <h4 class="timeline-header no-border"><?= Translate::_('business', 'Size') ?></h4>
                    </div>
                </li>
                <li>
                    <i class="fa fa-check bg-yellow-active"></i>

                    <div class="timeline-item bg-white">
                        <span class="time" id="summary-checksum"><?= $metadata['checkSum'] ?></span>
                        <h4 class="timeline-header no-border"><?= Translate::_(
                                'business',
                                'File checksum [md5]'
                            ) ?></h4>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
