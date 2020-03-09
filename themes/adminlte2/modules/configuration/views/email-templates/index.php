<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\enums\AvailableLanguage;
use idbyii2\enums\EmailActionType;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Email Templates Config')) ?>
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
                        <?= FlashMessage::widget(
                            [
                                'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash(
                                    'success'
                                ) : null,
                                'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error')
                                    : null,
                                'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info')
                                    : null,
                            ]
                        ); ?>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi deserunt dicta dignissimos
                            facere facilis iure possimus sequi, sunt tenetur!<br/> Exercitationem facilis inventore
                            minima nisi obcaecati provident quaerat repudiandae sint vitae.</p>
                        <?php foreach (EmailActionType::getPeopleActions() as $actionKey => $action): ?>
                            <h3><?= EmailActionType::translate($action) ?>:</h3>
                            <table id="email-actions">
                                <?php foreach (AvailableLanguage::getAllKeyValuePairs() as $iso => $language): ?>
                                    <tr class="email-action">
                                        <td>
                                            <?= AvailableLanguage::translate($language) ?>: <b>
                                                <?= (!empty($templates[$actionKey])
                                                    && !empty($templates[$actionKey][$iso]))
                                                    ?
                                                    $templates[$actionKey][$iso]
                                                    :
                                                    Translate::_('business', 'none') ?>
                                            </b>
                                        </td>

                                        <td class="edit-column">
                                            <?=
                                            Html::a(
                                                '<span class="btn btn-sm btn-success"><b class="fa fa-wrench"></b></span>',
                                                [
                                                    '/configuration/email-templates/edit',
                                                    'action' => $actionKey,
                                                    'iso' => $iso
                                                ]
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
