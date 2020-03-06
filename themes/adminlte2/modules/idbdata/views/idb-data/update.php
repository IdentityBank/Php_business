<?php

use app\assets\IdbDataAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;
use yii\helpers\Url;

$dataAssets = IdbDataAsset::register($this);
$dataAssets->formUpdateAssets();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Update personal details')) ?>
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
                            <button class="btn btn-primary btn-create">Save</button>
                        </div>

                        <div id="idb-data-create" class="idb-data-create"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="loading"></div>

<?= BusinessConfig::jsOptions(
    [
        'getModelURL' => Url::toRoute(['data-client-sets/get-model'], true),
        'showAllURL' => Url::toRoute(['idb-data/show-all'], true),
        'updateURL' => Url::toRoute(['idb-data/update', 'uuid' => $model[0]], true)
    ]
) ?>

<?= Translate::js(
    [
        'emptyRequiredMessage' => Translate::_(
            'business',
            'This field cannot be blank, please enter information and then save.'
        ),
        'errorMessage' => Translate::_('business', 'An error has occured. Please contact your system administrator.')
    ]
) ?>

<script>
    let model = <?= json_encode($model) ?> ;
    if (typeof model === 'string') {
        model = JSON.parse(model);
    }
</script>
