<?php

use app\assets\IdbDataAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model idbyii2\models\db\BusinessAccount */

$dataAssets = IdbDataAsset::register($this);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Create new account')) ?>
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
                        <?= $this->render(
                            '_form',
                            [
                                'model' => $model,
                                'users' => $users
                            ]
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
