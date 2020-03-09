<?php

use app\helpers\Translate;
use yii\helpers\Html;
use yii\helpers\Url;

$accountName = ((empty(Yii::$app->user->identity->accountName)) ? '' : Yii::$app->user->identity->accountName);

?>

<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="hidden-xs">
                    <?= $accountName ?>
                    &nbsp;
                </span>
        <i class="fa fa-angle-down"></i>
    </a>
    <ul class="dropdown-menu">
        <li class="user-header">
            <p>
                <?= $accountName ?>
            </p>
        </li>
        <li class="user-footer">
            <div class="pull-left">
                <?= Html::a(
                    Translate::_('business', 'Account Details'),
                    ['/profile'],
                    ['class' => 'btn btn-default btn-flat']
                ) ?>
            </div>
            <div class="pull-right">
                <?= Html::button(
                    Translate::_('business', 'Logout'),
                    ['class' => 'btn btn-default btn-flat', 'data-toggle' => 'modal', 'data-target' => '#logoutModal']
                ) ?>
            </div>
        </li>
    </ul>
</li>

<div class="form-group">
    <?= Yii::$app->controller->renderPartial(
        '@app/themes/adminlte2/views/site/_modalWindow',
        [
            'modal' => [
                'name' => 'logoutModal',
                'background-color' => 'rgba(215, 247, 245, 0.97)',
                'header' => Translate::_('business', 'Logout?'),
                'body' => Translate::_(
                    'business',
                    'Are you sure you want to logout of your account?'
                ),
                'question' => '',
                'leftButton' => [
                    'label' => Translate::_('business', 'No'),
                    'action' => 'data-dismiss',
                    'style' => 'btn btn-back',
                ],
                'rightButton' => [
                    'label' => Translate::_('business', 'Yes'),
                    'style' => 'btn btn-primary',
                    'action' => Url::toRoute(['/site/logout'])
                ],
            ]
        ]
    ); ?>
</div>
