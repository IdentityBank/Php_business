<?php

use app\assets\FrontAsset;
use app\assets\IdbDataAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;
use yii\helpers\Url;

$dataAssets = IdbDataAsset::register($this);

$frontAsset = FrontAsset::register($this);
$frontAsset->dataForm();

?>


<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Add New Person')) ?>
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
                                'success' => Yii::$app->session->hasFlash('success')
                                    ? Yii::$app->session->getFlash('success') : null,
                                'error' => Yii::$app->session->hasFlash('error')
                                    ? Yii::$app->session->getFlash('error') : null,
                                'info' => Yii::$app->session->hasFlash('info')
                                    ? Yii::$app->session->getFlash('info') : null,
                            ]
                        ); ?>
                        <div class="form-group">
                            <?= Yii::$app->controller->renderPartial(
                                '@app/themes/adminlte2/views/site/_modalWindow',
                                [
                                    'modal' => [
                                        'name' => 'cancelFormActionModal',
                                        'header' => Translate::_('business', 'Cancel edit your data'),
                                        'body' => Translate::_(
                                            'business',
                                            'You have chosen to cancel the edit your data task, your changes will not be saved'
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
                                            'label' => Translate::_('business', 'Cancel'),
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
                        </div>

                        <div id="idb-data-create" class="idb-data-create"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= BusinessConfig::jsOptions(
    [
        'showAllURL' => Url::toRoute(['idb-data/show-all'], true),
        'createURL' => Url::toRoute(['idb-data/create'], true),
        'getModelURL' => Url::toRoute(['data-client-sets/get-model'], true)
    ]
) ?>

<?= Translate::js(
    [
        'saveMessage' => Translate::_('business', 'Save'),
        'sentDisabledMessage' => Translate::_(
            'business',
            'Before invitations can be sent safes need to be identified and mapped first'
        ),
        'sentMessage' => Translate::_('business', 'Send invitation to the person you are adding to the vault.'),
        'emptyRequiredMessage' => Translate::_(
            'business',
            'This field cannot be blank, please enter information and then save.'
        )
    ]
) ?>

