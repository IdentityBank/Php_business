<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Data types')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Translate::_('business', 'Manage IDB data') ?></h3>
                    </div>

                    <div style="display: flex;">
                        <?php if ($metadataIsSet == false): ?>
                            <div class="box-body">
                                <?= Html::a(
                                    '<i class="fa fa-database"></i>' . Translate::_('business', 'Create'),
                                    ['data-client-sets/create'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            </div>
                            <?php if (!empty($sets)): ?>

                                <div class="box-body">
                                    <button id="create-from-template" data-toggle="modal"
                                            data-target="#exampleModal"
                                            class="btn btn-app"><i class="fa fa-database"></i><?= Translate::_(
                                            'business',
                                            'Create From Template'
                                        ) ?></button>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                        <div class="box-body">
                            <div class="form-group">
                                <?= Yii::$app->controller->renderPartial(
                                    '@app/themes/adminlte2/views/site/_modalWindow',
                                    [
                                        'modal' => [
                                            'name' => 'cancelFormActionModal',
                                            'header' => Translate::_('business', 'Cancel edit your data'),
                                            'body' => Translate::_(
                                                'business',
                                                'You have chosen to cancel the edit your data task, your changes will not be saved'
                                            ),
                                            'question' => Translate::_(
                                                'business',
                                                'If this is not your intention, please click on \'Continue\'.'
                                            ),
                                            'button' => [
                                                'label' => '<i class="fa fa-caret-left"></i>' . Translate::_(
                                                        'business',
                                                        'Cancel'
                                                    ),
                                                'class' => 'btn btn-app'
                                            ],
                                            'leftButton' => [
                                                'label' => Translate::_('business', 'Cancel'),
                                                'action' => Yii::$app->session->get('urlRedirect'),
                                                'style' => 'btn btn-back',
                                            ],
                                            'rightButton' => [
                                                'label' => Translate::_('business', 'Continue'),
                                                'style' => 'btn btn-primary',
                                                'action' => 'data-dismiss'
                                            ],
                                        ]
                                    ]
                                ); ?>
                                <?= Html::a(
                                    '<i class="fa fa-database"></i>' . Translate::_('business', 'Edit'),
                                    ['data-client-sets/create'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<?php if (!empty($sets)): ?>
    <div class="modal fade" id="exampleModal" style="z-index: 9999999;" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><?= Translate::_('business', 'Choose template') ?>
                        :</h3>
                    <button type="button" style="top: 15px; right: 15px; position:absolute;" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="get" action="<?= Url::toRoute(['create'], true) ?>">
                    <div class="modal-body">
                        <label>
                            <select name="id">
                                <?php foreach ($sets as $set): ?>
                                    <option value="<?= $set->id ?>"><?= $set->display_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
endif;
?>
