<?php

use app\assets\SwitcherAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

SwitcherAsset::register($this);

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Configuration: sending email and SMS')) ?>
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

                        <form method="post"
                              action="<?= Url::toRoute(['/configuration/configuration/savedata'], true) ?>">

                            <div class="form-group">
                                <?= Yii::$app->controller->renderPartial(
                                    '@app/themes/adminlte2/views/site/_modalWindow',
                                    [
                                        'modal' => [
                                            'name' => 'cancelFormActionModal',
                                            'header' => Translate::_('business', 'Stop create account'),
                                            'body' => Translate::_(
                                                'business',
                                                'You have chosen to stop the create an account task, your changes will not be saved'
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
                                                'label' => Translate::_('business', 'Stop'),
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
                                <?= Html::submitButton(
                                    Translate::_('business', 'Save'),
                                    ['class' => 'btn btn-primary']
                                ) ?>
                            </div>
                            <?php if ($mail == 'on') : ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="mail" checked data-toggle="toggle"
                                               data-onstyle="success"
                                               data-offstyle="danger">
                                        <?= Translate::_('business', 'Send email') ?>
                                    </label>
                                </div>
                            <?php else : ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="mail" data-toggle="toggle" data-onstyle="success"
                                               data-offstyle="danger">
                                        <?= Translate::_('business', 'Send email') ?>
                                    </label>
                                </div>
                            <?php endif; ?>
                            <?php if ($sms == 'on') : ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="sms" checked data-toggle="toggle"
                                               data-onstyle="success"
                                               data-offstyle="danger">
                                        <?= Translate::_('business', 'Send SMS') ?>
                                    </label>
                                </div>
                            <?php else : ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="sms" data-toggle="toggle" data-onstyle="success"
                                               data-offstyle="danger">
                                        <?= Translate::_('business', 'Send SMS') ?>
                                    </label>
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                            <input type="hidden" name="dbid" value="<?= $dbid ?>"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
