<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Update data additional attribute')) ?>
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

                        <div class="data-types-update">

                            <h1><?= Html::encode($this->title) ?></h1>

                            <?= $this->render(
                                '_form',
                                [
                                    'model' => $model,
                                ]
                            ) ?>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

