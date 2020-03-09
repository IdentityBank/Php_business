<?php

use app\assets\AdminLte2AppAsset;
use app\assets\SelectAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

AdminLte2AppAsset::register($this);
$assetSelectBundle = SelectAsset::register($this);

$this->registerJs(
    <<< EOT_JS_CODE

  $(document).ready(function() {
    $('.select-search').select2();
});

EOT_JS_CODE
);

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Roles')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
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
                                            'label' => Translate::_(
                                                'business',
                                                'Cancel'
                                            ),
                                            'class' => 'btn btn-back'
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
                        </div>
                        <div class="box box-widget widget-user-2">
                            <div class="box-body box-profile">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <form action="<?= Url::toRoute(["/idbuser/manager/addroletouser"], true) ?>"
                                              method="get">
                                            <input type="hidden" name="uid" value="<?= $uid ?>">
                                            <div class="input-group margin">
                                                <div class="input-group-btn">
                                                    <button type="submit" id="submit"
                                                            class="btn btn-app btn-app-green"><i
                                                                class="fa fa-plus-square"></i></button>
                                                </div>
                                                <div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group field-keyvalueform-key required">

                                                            <label class="control-label"><?= Html::encode(
                                                                    Translate::_('business', 'Add Role')
                                                                ) ?></label>

                                                            <select class="select-search form-control" name="role"
                                                                    required>
                                                                <?php

                                                                foreach ($roles as $role) {
                                                                    echo "<option value=\"$role\">$role</option>";
                                                                }

                                                                ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                        </form>
                            </div>
                        </div>
                    </div><!-- end of added role -->

                    <table class="table table-bordered" id="myTable">
                        <thead>
                        <tr>
                            <th scope="col"><?= Html::encode(Translate::_('business', 'Role')) ?></th>
                            <th scope="col"><?= Html::encode(Translate::_('business', 'Delete')) ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($roles_user as $role) { ?>
                            <tr>
                                <td><?= $role ?></td>

                                <td style='text-align: center;'>

                                    <form action="<?= Url::toRoute(["/idbuser/manager/deleterolefromuser"], true) ?>"
                                          method="get"
                                          style='display: inline-block; vertical-align: top; float: left;'>
                                        <input type="hidden" name="uid" value="<?= $uid ?>">
                                        <input type="hidden" name="role" value="<?= $role ?>">
                                        <button type="submit" class="btn btn-app-trash" id="delete_<?= $role ?>"
                                                data-confirm="<?= Html::encode(
                                                    Translate::_(
                                                        'business',
                                                        'Are you sure you want to delete this item?'
                                                    )
                                                ) ?>">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>
                                    </form>

                                </td>

                            </tr>
                        <?php }
                        ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
</div>
</section>
</div>
