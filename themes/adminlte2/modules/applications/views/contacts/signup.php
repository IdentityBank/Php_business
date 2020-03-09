<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\DetailView;

$attributes = array_keys($request);

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
                        <h3 class="box-title"><?= Translate::_(
                                'business',
                                'Link to continue people portal signup'
                            ) ?></h3>
                    </div>
                    <div class="box-body">
                        <?php if (!empty($errors) && is_array($errors)) { ?>
                            <div class="callout callout-danger">
                                <?php foreach ($errors as $errorHeader => $errorValue) { ?>
                                    <h4><?= $errorHeader ?></h4>
                                    <p><?= $errorValue ?></p>

                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?= DetailView::widget(
                            [
                                'model' => $request,
                                'attributes' => $attributes,
                            ]
                        ) ?>
                    </div>
                </div>
                <?php if (!empty($url)) { ?>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Translate::_('business', 'Click to finish account signup') ?></h3>
                        </div>
                        <div class="box-body">
                            <?= Html::a(
                                Translate::_('business', 'Start registration process'),
                                $url,
                                [
                                    'class' => 'btn btn-primary btn-block btn-sm',
                                    'role' => 'modal-remote',
                                    'target' => '_blank'
                                ]
                            ) ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>
