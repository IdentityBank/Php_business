<?php

use app\helpers\Translate;
use yii\helpers\Html;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Business Account Manager') . ' :: ' . $oid->name) ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <?= Translate::_('business', 'Organization details') ?>
                    </div>
                    <div class="box-body">

                        <?= $oid->name ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
