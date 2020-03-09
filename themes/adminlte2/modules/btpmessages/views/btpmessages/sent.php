<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Message sent')) ?>
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
                        <div class="alert alert-success">

                            <?= Html::encode(Translate::_('business', 'Message sent')) ?>!
                        </div>
                        <center><a class="btn btn-primary btn-lg"
                                   href="<?= Url::toRoute(['/btpmessages/create'], true) ?>"
                                   role="button"><?= Html::encode(
                                    Translate::_('business', 'Create another message?')
                                ) ?></a></center>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
