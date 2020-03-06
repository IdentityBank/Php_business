<?php

use app\assets\AdminLte2AppAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Inflector;

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
                        || Yii::$app->user->can('user_manager_search')
                    ) : ?>
                        <div class="box-header with-border">
                            <?php if (Yii::$app->user->can('user_manager_search'))
                                echo Html::a(
                                    '<i class="fa fa-search"></i>' . Translate::_('business', 'Search'),
                                    ['search'],
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
                        <h3 class="widget-user-username"><b><?= $userId ?></b></h3>
                        <h5 class="widget-user-desc"><?= $accountNumber ?></h5>
                    </div>
                    <div class="box-body box-profile">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <?php $form = ActiveForm::begin(
                                    ['fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField']
                                ); ?>
                                <div class="input-group margin">
                                    <div class="input-group-btn">
                                        <?= Html::submitButton(
                                            '<i class="fa fa-plus-square"></i>',
                                            ['class' => 'btn btn-app btn-app-green']
                                        ) ?>
                                    </div>
                                    <div>
                                        <div class="col-lg-6">
                                            <?= $form->field($keyValueModel, 'key')->textInput(
                                                [
                                                    'maxlength' => true,
                                                    'placeholder' => Translate::_('business', "User data key")
                                                ]
                                            ) ?>
                                        </div>
                                        <div class="col-lg-6">
                                            <?= $form->field($keyValueModel, 'value')->textInput(
                                                [
                                                    'maxlength' => true,
                                                    'placeholder' => Translate::_('business', "User data value")
                                                ]
                                            ) ?>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </li>
                        </ul>
                        <?php foreach ($userData as $key => $value) { ?>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <?php
                                    $updateName = "
                                <b>" . Translate::_('idbexternal', Inflector::camel2words($key, true)) . "</b>
                                <code>[$key]</code>
                                ";
                                    $url = ['delete', 'uid' => $uid, 'key' => $key];
                                    $icon = Html::tag(
                                        'span',
                                        '',
                                        ['class' => "btn btn-app-trash glyphicon glyphicon-trash"]
                                    );
                                    $options =
                                        [
                                            'title' => Translate::_('business', 'Delete'),
                                            'data' =>
                                                [
                                                    'confirm' => Translate::_(
                                                        'business',
                                                        'Are you sure you want to delete this item?'
                                                    ),
                                                    'method' => 'post',
                                                ]
                                        ];
                                    echo Html::a($icon, $url, $options);
                                    $url = ['update', 'uid' => $uid, 'key' => $key];
                                    $options =
                                        [
                                            'id' => "update-button-$key",
                                            'updatebtn' => "$key",
                                            'onclick' => "
                                        $('#update-modal-title').html(" . json_encode($updateName) . ");
                                        document.forms['update-form']['keyvalueform-value'].value = '$value';
                                        document.forms['update-form']['keyvalueform-key'].value = '$key';
                                    ",
                                            'title' => Translate::_('business', 'Update'),
                                            'class' => "btn btn-default glyphicon glyphicon-pencil",
                                            'data-toggle' => "modal",
                                            'data-target' => "#modal-update-attribute"
                                        ];
                                    echo Html::button(null, $options);
                                    ?>
                                    &nbsp;
                                    <?= $updateName ?>
                                    <p class="pull-right"><?= $value ?></p>
                                </li>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<div class="modal fade" id="modal-update-attribute">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php $form = ActiveForm::begin(
                ['id' => 'update-form', 'fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField']
            ); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 id="update-modal-title" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <?= $form->field($keyValueModel, 'value')->textInput(
                    ['maxlength' => true, 'placeholder' => Translate::_('business', "User data value")]
                ) ?>
                <?= $form->field($keyValueModel, 'key')->hiddenInput()->label(false); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= Translate::_(
                        'business',
                        'Cancel'
                    ) ?></button>
                <?= Html::submitButton(Translate::_('business', 'Update'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?= Html::hiddenInput('action', 'update') ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
