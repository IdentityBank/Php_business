<?php

use app\assets\SwitcherAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\models\db\RolesModel;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

SwitcherAsset::register($this);

?>
<style>
    .head_table {
        background-color: #00AEAB;
        color: white;
    }

    .cell_table {
        background-color: #002570;
        color: white;
    }

</style>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Manage roles')) ?>
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
                        <h3 class="box-title"><?= $firstname . ' ' . $lastname ?></h3>
                    </div>
                    <div class="box-body">
                        <?php $form = ActiveForm::begin(
                            [
                                'action' => ['/accessmanager/user-manager/save'],
                                'options' => ['method' => 'post', 'id' => 'map_form']
                            ]
                        ) ?>

                        <div class="form-group">
                            <?= Yii::$app->controller->renderPartial(
                                '@app/themes/adminlte2/views/site/_modalWindow',
                                [
                                    'modal' => [
                                        'name' => 'cancelFormActionModal',
                                        'header' => Translate::_('business', 'Cancel assign roles'),
                                        'body' => Translate::_(
                                            'business',
                                            'You have chosen to cancel the assign roles task, your changes will not be saved'
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
                            <?= Html::submitButton(
                                Translate::_('business', 'Save'),
                                ['class' => 'btn btn-primary', 'id' => 'save']
                            ) ?>
                        </div>
                        <table style="width:100%" class="table table-bordered">
                            <tr>
                                <th><?= Translate::_('business', 'Role')?></th>
                                <th><?= Translate::_('business', 'Description')?></th>
                                <th><?= Translate::_('business', 'Status')?></th>
                            </tr>
                            <?php foreach ($roles as $role => $status) : ?>
                                <?php if (
                                    ($role === 'organization_admin')
                                    && ($status === 'on')
                                ) {
                                    continue;
                                }
                                ?>
                                <tr>
                                    <td><?= RolesModel::getDiaplayName($role) ?></td>
                                    <td><?= $roles_desc[$role] ?></td>
                                    <td>
                                        <?php if ($status === 'on') : ?>
                                            <input type='hidden' value='off' name='<?= $role ?>'>
                                            <input type="checkbox" checked data-toggle="toggle"
                                                   data-onstyle="success"
                                                   name="<?= $role ?>" data-offstyle="danger">
                                        <?php endif; ?>
                                        <?php if ($status === 'off') : ?>
                                            <input type='hidden' value='off' name='<?= $role ?>'>
                                            <input type="checkbox" data-toggle="toggle" data-onstyle="success"
                                                   name="<?= $role ?>" data-offstyle="danger">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>

                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                        <input type="hidden" name="uid" value="<?= $uid ?>"/>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
