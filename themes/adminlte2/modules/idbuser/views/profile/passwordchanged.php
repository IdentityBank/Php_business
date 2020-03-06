<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;

?>


<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Password Changed')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="callout callout-success">
                    <h4><?= Translate::_('business', 'Password changed.') ?></h4>
                </div>
            </div>
        </div>
    </section>
</div>
