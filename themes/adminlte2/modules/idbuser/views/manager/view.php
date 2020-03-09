<?php

use app\assets\AdminLte2AppAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Inflector;

$accountNumber = ((empty($userData['accountNumber'])) ? '' : $userData['accountNumber']);
$userId = ((empty($userData['userId'])) ? '' : $userData['userId']);
$accountName = $userData['accountName'] ?? $userId;
AdminLte2AppAsset::register($this);

$skin = BusinessConfig::get()->getYii2BusinessSkin();
switch ($skin) {
    case 'skin-black-light':
    case 'skin-black':
        {
            $widget_user_header = "bg-black";
        }
        break;
    case 'skin-blue-light':
    case 'skin-blue':
        {
            $widget_user_header = "bg-blue";
        }
        break;
    case 'skin-green-light':
    case 'skin-green':
        {
            $widget_user_header = "bg-green";
        }
        break;
    case 'skin-purple-light':
    case 'skin-purple':
        {
            $widget_user_header = "bg-purple";
        }
        break;
    case 'skin-red-light':
    case 'skin-red':
        {
            $widget_user_header = "bg-red";
        }
        break;
    case 'skin-yellow-light':
    case 'skin-yellow':
        {
            $widget_user_header = "bg-yellow";
        }
        break;
    default:
        $widget_user_header = "";
}
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

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
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
                        <h3 class="box-title"><?= strtoupper($accountName) ?></h3>
                    </div>

                    <?php if (
                        Yii::$app->user->can('user_manager_update') || Yii::$app->user->can('user_manager_delete')
                        || Yii::$app->user->can('user_manager_roles')
                        || Yii::$app->user->can('notifications')
                        || Yii::$app->user->can('user_manager_search')
                    ) : ?>
                        <div class="box-header with-border">
                            <?php if (Yii::$app->user->can('user_manager_search'))
                                echo Html::a(
                                    '<i class="fa fa-search"></i>' . Translate::_('business', 'Search'),
                                    ['search'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            <?php if (Yii::$app->user->can('user_manager_update'))
                                echo Html::a(
                                    '<i class="fa fa-edit"></i>' . Translate::_('business', 'Update'),
                                    ['update', 'uid' => $uid],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            <?php if (Yii::$app->user->can('user_manager_roles'))
                                echo Html::a(
                                    '<i class="fa fa-user"></i>' . Translate::_('business', 'Roles'),
                                    ['roles', 'uid' => $uid],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            <?php if (Yii::$app->user->can('notifications'))
                                echo Html::a(
                                    '<i class="fa fa-comment-o"></i>' . Translate::_('business', 'Add Notification'),
                                    ['/notifications/create', 'uid' => $uid],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            <?php if (Yii::$app->user->can('user_manager_delete'))
                                echo Html::a(
                                    '<i class="glyphicon glyphicon-trash"></i>' . Translate::_('business', 'Delete'),
                                    ['delete', 'uid' => $uid],
                                    [
                                        'class' => 'btn btn-app btn-app-trash',
                                        'data' =>
                                            [
                                                'confirm' => Translate::_(
                                                    'business',
                                                    'Are you sure you want to delete this item?'
                                                ),
                                                'method' => 'post',
                                            ],
                                    ]
                                ) ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header <?= $widget_user_header ?>">
                        <div class="widget-user-image">
                            <ion-img class="ion ion-person img-circle"></ion-img>
                        </div>
                        <h3 class="widget-user-username"><b><?= $accountName ?></b></h3>
                    </div>
                    <div class="box-body box-profile">
                        <?php foreach ($userData as $key => $value) { ?>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <b><?= Translate::_('idbexternal', Inflector::camel2words($key, true)) ?></b>
                                    <p class="pull-right"><?= $value ?></p>
                                </li>
                            </ul>
                        <?php } ?>
                        <?php foreach ($roles as $role) { ?>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <b><?= Translate::_('business', 'Role') ?></b>
                                    <p class="pull-right"><?= $role ?></p>
                                </li>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>
