<?php

use app\helpers\Translate;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Breadcrumbs;

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Password Recovery Token')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <?php $form = ActiveForm::begin(
        ['id' => 'signup-form', 'fieldClass' => 'app\themes\adminlte2\views\yii\widgets\form\ActiveField']
    ); ?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body" style="text-align: center;">
                        <div style="clear: both;">

                        </div>
                        <h3 style="padding: 30px;"><?= Translate::_(
                            'business',
                            'Your contact details has been successfully modified. Before you leave this page , please take a moment to download, print, and securely store your printed account password recovery token.'
                        ) ?></h3>
                        <?=
                        Html::a(
                            '<i class="fa far fa-save"></i> ' . Translate::_(
                                'business',
                                'Download'
                            ),
                            ['get-token'],
                            [
                                'class' => 'btn btn-danger btn-xl',
                                'id' => "id-button-recovery-token",
                                'target' => '_blank',
                                'style' => 'margin-bottom: 30px;',
                                'data-toggle' => 'tooltip',
                                'title' => Translate::_(
                                    'business',
                                    'Will open the generated Password token in new window'
                                )
                            ]
                        );
                        ?>


                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php ActiveForm::end(); ?>
</div>