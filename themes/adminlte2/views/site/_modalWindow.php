<?php

$modalDefault = [
    'name' => 'customActionModal',
    'header' => 'header',
    'body' => 'body',
    'question' => '',
    'button' => [
        'label' => 'label',
        'class' => '',
        'style' => '',
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

if (empty($modal['button']['class']) && !empty($modalDefault['button']['class'])) {
    $modal['button']['class'] = $modalDefault['button']['class'];
}
if (empty($modal['button']['style']) && !empty($modalDefault['button']['style'])) {
    $modal['button']['style'] = $modalDefault['button']['style'];
}
if (empty($modal['leftButton']['style']) && !empty($modalDefault['leftButton']['style'])) {
    $modal['leftButton']['style'] = $modalDefault['leftButton']['style'];
}
if (empty($modal['rightButton']['style']) && !empty($modalDefault['rightButton']['style'])) {
    $modal['rightButton']['style'] = $modalDefault['rightButton']['style'];
}
if (empty($modal['leftButton']['action']) && !empty($modalDefault['leftButton']['action'])) {
    $modal['leftButton']['action'] = $modalDefault['leftButton']['action'];
}
if ((!empty($modal['leftButton']['action'])) && ($modal['leftButton']['action'] === 'data-dismiss')) {
    $modal['leftButton']['action'] = 'data-dismiss="modal"';
} else {
    $leftButtonOnClickAction = $modal['leftButton']['onClickAction'] ?? null;
    $modal['leftButton']['action'] = 'onclick="' . $leftButtonOnClickAction . ' location.href=\''
        . ($modal['leftButton']['action'] ?? '#') . '\';"';
}

if (empty($modal['rightButton']['action'])) {
    $modal['rightButton']['action'] = $modalDefault['rightButton']['action'] ?? '#';
}
if ($modal['rightButton']['action'] === 'data-dismiss') {
    $modal['rightButton']['action'] = 'data-dismiss="modal"';
} else {
    $rightButtonOnClickAction = $modal['rightButton']['onClickAction'] ?? null;
    $modal['rightButton']['action'] = 'onclick="' . $rightButtonOnClickAction . ' location.href=\''
        . ($modal['rightButton']['action'] ?? '#') . '\';"';
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

    <?php if (!empty($modal['background-color'])) : ?>
    <?php $modal['class_internal'] = "{$modal['name']}-modal"; ?>
    .<?= $modal['class_internal'] ?> {
        background-color: <?= $modal['background-color'] ?>;
    }

    <?php endif; ?>

</style>
<?php if (!empty($modal['button'])) : ?>
    <button type="button" class="<?= $modal['button']['class'] ?>" data-toggle="modal"
            style="<?= !empty($modal['button']['style']) ? $modal['button']['style'] : '' ?>"
        <?= !empty($modal['button']['id']) ? 'id="' . $modal['button']['id'] . '"' : '' ?>
        <?= !empty($modal['button']['disabled']) ? 'disabled="disabled"' : '' ?>
            data-target="#<?= $modal['name'] ?>">
        <?= $modal['button']['label'] ?>
    </button>
<?php endif; ?>
<div class="modal fade <?= $modal['class'] ?? '' ?> <?= $modal['class_internal'] ?? '' ?>"
     id="<?= $modal['name'] ?>"
     tabindex="-1"
     role="dialog"
     aria-labelledby="<?= $modal['name'] ?>"
     aria-hidden="<?= (empty($modal['aria-hidden']) ? 'true' : $modal['aria-hidden']) ?>">
    <div class="modal-dialog modal-dialog-centered"
         style="margin-top: 50px;"
         role="document">
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
                    <button type="button"
                            id="<?= empty($modal['leftButton']['id'])?'leftButton':$modal['leftButton']['id'] ?>"
                            class="btn <?= $modal['leftButton']['style'] ?>"
                            style="<?= empty($modal['leftButton']['buttonStyle'])?'float:left;':$modal['leftButton']['buttonStyle'] ?>"
                        <?= $modal['leftButton']['action'] ?>
                        <?= !empty($modal['leftButton']['id']) ? 'id="' . $modal['leftButton']['id'] . '"' : '' ?>
                    ><?= $modal['leftButton']['label'] ?></button>
                <?php endif; ?>
                <?php if (!empty($modal['rightButton']['label'])) : ?>
                    <button type="button"
                            id="<?= empty($modal['rightButton']['id'])?'rightButton':$modal['rightButton']['id'] ?>"
                            class="btn <?= $modal['rightButton']['style'] ?>"
                            style="<?= empty($modal['rightButton']['buttonStyle'])?'float:right;':$modal['rightButton']['buttonStyle'] ?>"
                        <?= $modal['rightButton']['action'] ?>
                    ><?= $modal['rightButton']['label'] ?></button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
