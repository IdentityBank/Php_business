<?php

use yii\helpers\Url;

$modal_default = [
    'name' => 'customActionModal',
    'header' => 'header',
    'body' => 'body',
    'question' => '',
    'button' => [
        'label' => 'label',
        'class' => 'btn btn-primary'
    ],
    'leftButton' => [
        'label' => 'label',
        'style' => 'btn-danger'
    ],
    'rightButton' => [
        'label' => 'label',
        'style' => 'btn-warning'
    ],
];

if (empty($modal['button']['class'])) {
    $modal['button']['class'] = $modal_default['button']['class'];
}
if (empty($modal['leftButton']['style'])) {
    $modal['leftButton']['style'] = $modal_default['leftButton']['style'];
}
if (empty($modal['rightButton']['style'])) {
    $modal['rightButton']['style'] = $modal_default['rightButton']['style'];
}
if (empty($modal['leftButton']['action'])) {
    $modal['leftButton']['action'] = $modal_default['leftButton']['action'];
}
if ($modal['leftButton']['action'] === 'data-dismiss') {
    $modal['leftButton']['action'] = 'data-dismiss="modal"';
} else {
    $modal['leftButton']['action'] = 'onclick="location.href=\'' . Url::toRoute($modal['leftButton']['action'])
        . '\';"';
}


if (empty($modal['rightButton']['action'])) {
    $modal['rightButton']['action'] = $modal_default['rightButton']['action'];
}
if ($modal['rightButton']['action'] === 'data-dismiss') {
    $modal['rightButton']['action'] = 'data-dismiss="modal"';
} else {
    $modal['rightButton']['action'] = 'onclick="location.href=\'' . Url::toRoute($modal['rightButton']['action'])
        . '\';"';
}

?>

<!-- Modal -->
<style>
    #<?= $modal['name'] ?>
    .modal-header {
        background-color: #232E6C;
        color: white;
        font-family: ubuntu;
    }

    #<?= $modal['name'] ?>
    .modal-body {
        background-color: #D7F7F5;
        font-family: ubuntu;
    }

    #<?= $modal['name'] ?>
    .modal-footer {
        background-color: #00AEAB;
    }

    #<?= $modal['name'] ?>
    .modal-footer {
        justify-content: space-between;
    }
</style>
<button type="button" class="<?= $modal['button']['class'] ?>" data-toggle="modal"
        data-target="#<?= $modal['name'] ?>">
    <?= $modal['button']['label'] ?>
</button>
<div class="modal fade" id="<?= $modal['name'] ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $modal['name'] ?>"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <?php if (!empty($modal['header'])) : ?>
                <div class="modal-header">
                    <h5 class="modal-title"><?= $modal['header'] ?></h5>
                </div>
            <?php endif; ?>
            <?php if (!empty($modal['body'])) : ?>
                <div class="modal-body">
                    <?= $modal['body'] ?> <br/><br/>
                    <?php if (!empty($modal['question'])): ?>

                        <?= $modal['question'] ?>

                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="modal-footer">
                <?php if (!empty($modal['leftButton']['label'])) : ?>
                    <button type="button" class="btn <?= $modal['leftButton']['style'] ?>" style="float:left;"
                        <?= $modal['leftButton']['action'] ?>><?= $modal['leftButton']['label'] ?></button>
                <?php endif; ?>
                <?php if (!empty($modal['rightButton']['label'])) : ?>
                    <button type="button" class="btn <?= $modal['rightButton']['style'] ?>"
                        <?= $modal['rightButton']['action'] ?>><?= $modal['rightButton']['label'] ?></button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

