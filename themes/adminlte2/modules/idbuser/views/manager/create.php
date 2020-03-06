<?php

use app\assets\AdminLte2AppAsset;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;

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
            <div class="col-md-12">
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header <?= $widget_user_header ?>">
                        <div class="widget-user-image">
                            <ion-img class="ion ion-person img-circle"></ion-img>
                        </div>
                        <h3 class="widget-user-username">&nbsp;</h3>
                        <h5 class="widget-user-desc">&nbsp;</h5>
                    </div>
                    <div class="box-body box-profile">

                        <?= $this->render('_create_form', ['model' => $model]) ?>

                    </div>

                </div>
            </div>
        </div>
    </section>

</div>
