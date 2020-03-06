<?php

foreach ($data as $key => $type) {
    if (empty($type['display_name'])) {
        break;
    }
    echo $this->context->renderPartial('_change_view', ['key' => $key, 'type' => $type]);
}
