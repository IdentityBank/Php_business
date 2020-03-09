<?php

foreach ($data as $key => $type) {
    if (empty($type['display_name'])) {
        break;
    }
    echo $this->context->renderPartial('_revert_view', ['key' => $key, 'type' => $type]);
}
