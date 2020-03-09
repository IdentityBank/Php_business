<?php

use app\helpers\ReturnUrl;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;

$url = ReturnUrl::generateUrl();

?>

<div class="content-wrapper">
    <?= FlashMessage::widget(
        [
            'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success') : null,
            'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : null,
            'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : null,
        ]
    ); ?>

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Manage vaults')) ?>
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
                            <h3 class="box-title">
                                <i class="fa fa-database margin-r-5"></i>
                                <?= Translate::_(
                                    'business',
                                    'My vaults for account - {activeAccountName}',
                                    ['activeAccountName' => $activeAccountName]
                                ) ?>
                            </h3>
                        </div>
                        <div class="box-header with-border">
                            <?php if (false): ?>
                                <?= Html::a(
                                    '<i class="glyphicon glyphicon-pencil"></i>' . Translate::_(
                                        'business',
                                        'Change Vault'
                                    ),
                                    ['/accessmanager/database-manager/change-database'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            <?php endif; ?>
                            <?= Html::a(
                                '<i class="fa fa-plus-square-o"></i>' . Translate::_('business', 'Create vault'),
                                ['/accessmanager/database-manager/createdb'],
                                ['class' => 'btn btn-app']
                            ) ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="box-header with-border">
                            <?php foreach ($businessAccountDatabases as $businessAccount => $businessDatabases) : ?>
                                <?php if ($businessAccount === $activeAccount) : ?>
                                    <div class="box box-widget widget-user">
                                        <div class="box-header with-border bg-teal-gradient">
                                            <i class="fa fa-database"></i>
                                            <h3 class="box-title bg-teal-gradient"><?= ($businessAccountsNames[$businessAccount])
                                                ?? $businessAccount ?></h3>
                                        </div>
                                        <div class="box-footer">
                                            <?php foreach ($businessDatabases as $businessDatabase) : ?>
                                                <p class="<?= ($businessDatabase['dbid']
                                                    == Yii::$app->user->identity->dbid) ? 'text-green'
                                                    : 'text-muted' ?>">
                                                    <?= $businessDatabase['name'] ?>
                                                    <?php if (!empty($businessDatabase['count'])) : ?>
                                                        <small class="label pull-right <?= ($businessDatabase['dbid']
                                                            == Yii::$app->user->identity->dbid) ? 'bg-green'
                                                            : 'bg-blue' ?>">
                                                            <?= $businessDatabase['count'] ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </p>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
