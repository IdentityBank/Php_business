<?php

use app\helpers\ReturnUrl;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\helpers\Translate;
use yii\helpers\Html;
use yii\helpers\Url;

$url = ReturnUrl::generateUrl();

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Actions')) ?>
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
                        <div class="box-header with-border">
                            <table class="table">
                                <tr>
                                    <th><?= Html::encode(Translate::_('business', 'Role')) ?></th>
                                    <th><?= Html::encode(Translate::_('business', 'Actions')) ?></th>
                                    <th><?= Html::encode(Translate::_('business', 'Add')) ?></th>
                                </tr>
                                <tr>
                                    <?php foreach ($roles

                                    as $role) : ?>
                                <tr>
                                    <td><?= $role ?></td>

                                    <td>
                                        <form action="<?= Url::toRoute(['/rbac/viewaction'], true) ?>" method="get">
                                            <input type="hidden" name="name" value="<?= $role ?>">
                                            <button type="submit" class="btn btn-app-sm" id="va_<?= $role ?>"
                                                    style="float:left;">
                                                <i class="fas fa-font"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <td>
                                        <form action="<?= Url::toRoute(['/rbac/addaction'], true) ?>" method="get">
                                            <input type="hidden" name="name" value="<?= $role ?>">
                                            <button type="submit" class="btn btn-app-sm" id="add_ <?= $role ?>"
                                                    style="float:left;">
                                                <span class="fa fa-plus-circle"></span>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
