<?php

use app\assets\AdminLte2Asset;
use app\helpers\ReturnUrl;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\adminlte2\views\yii\widgets\grid\GridView;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;
use yii\widgets\Pjax;

$assetBundle = AdminLte2Asset::register($this);
$assetBundle->layoutDataTable($this);

$url = ReturnUrl::generateUrl();

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode($this->title) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <?= FlashMessage::widget(
        [
            'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success') : null,
            'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : null,
            'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : null,
        ]
    ); ?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Translate::_('business', 'Account Users') ?></h3>
                        </div>
                        <div class="box-header with-border">
                            <?php echo Html::a(
                                '<i class="fa fa-user-plus"></i>' . Translate::_('business', 'Create'),
                                ['form'],
                                ['class' => 'btn btn-app']
                            ) ?>
                        </div>

                        <div class="box-body">
                            <?php Pjax::begin(); ?>
                            <?= GridView::widget(
                                [
                                    'id' => 'grid_id_account_user_manager',
                                    'dataProvider' => $dataProvider,
                                    'tableOptions' => ['id' => 'table_id_user_manager'],
                                    'columns' =>
                                        [
                                            ['class' => 'yii\grid\SerialColumn'],

                                            [
                                                'label' => Translate::_('business', 'Firstname'),
                                                'value' => function ($model) {
                                                    return $model->getValue('firstname');
                                                }
                                            ],
                                            [
                                                'label' => Translate::_('business', 'Lastname'),
                                                'value' => function ($model) {
                                                    return $model->getValue('lastname');
                                                }
                                            ],
                                            [
                                                'label' => Translate::_('business', 'Email'),
                                                'value' => function ($model) {
                                                    return $model->getValue('email');
                                                }
                                            ],
                                            [
                                                'label' => Translate::_('business', 'Phone'),
                                                'value' => function ($model) {
                                                    return $model->getValue('mobile');
                                                }
                                            ],
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'template' => '{assign-database} {assign-account} {roles}',
                                                'buttons' => [

                                                    'assign-database' => function ($url, $model) {

                                                        $uid = $model->getUserId();
                                                        $firstname = $model->getValue('firstname');
                                                        $lastname = $model->getValue('lastname');

                                                        return Html::a(
                                                            '<span class="glyphicon glyphicon-transfer"></span>',
                                                            ['/user-manager/assign-database'],
                                                            [
                                                                'data' => [
                                                                    'method' => 'post',
                                                                    'params' => [
                                                                        'uid' => $uid,
                                                                        'firstname' => $firstname,
                                                                        'lastname' => $lastname,
                                                                    ],
                                                                ]
                                                            ]
                                                        );
                                                    },

                                                    'roles' => function ($url, $model) {

                                                        $uid = $model->getUserId();
                                                        $firstname = $model->getValue('firstname');
                                                        $lastname = $model->getValue('lastname');

                                                        return Html::a(
                                                            '<span class="glyphicon glyphicon-user"></span>',
                                                            ['/user-manager/roles'],
                                                            [
                                                                'data' => [
                                                                    'method' => 'post',
                                                                    'params' => [
                                                                        'uid' => $uid,
                                                                        'firstname' => $firstname,
                                                                        'lastname' => $lastname,
                                                                    ],
                                                                ]
                                                            ]
                                                        );
                                                    }

                                                ]
                                            ],
                                        ],
                                ]
                            ); ?>
                            <?php Pjax::end(); ?>
                        </div>

                    </div>
                </div>
            </div>
    </section>
</div>
