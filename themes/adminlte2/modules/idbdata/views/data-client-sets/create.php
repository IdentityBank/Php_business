<?php

use app\assets\IdbDataAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var array $sets */
/** @var array $types */
/** @var string $id */

$dataAssets = IdbDataAsset::register($this);
$dataAssets->createSetAssets();

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Edit safes')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section id="creator-container" class="content hidden">
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

                            <button class="btn btn-primary btn-create"><?= Translate::_('business', 'Save') ?></button>
                        </div>


                        <div id="data-client-create" class="data-client-create">
                        </div>


                        <div class="modal fade" id="processors-modal" style="z-index: 9999999;" tabindex="-1" role="dialog"
                             aria-labelledby="idbDataTableSettingsLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="idbDataTableSettingsLabel">
                                            <?= Translate::_(
                                                'business',
                                                'List Data Processors:'
                                            ) ?>
                                            :</h3>
                                        <button type="button" style="top: 15px; right: 15px; position:absolute;" class="close"
                                                data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="dpo-container">
                                        </div>
                                        <button style="margin-bottom: 20px;" id="add-dpo" class="btn btn-primary"><i class="fa fa-plus-square"></i></button>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" id="processors-set" class="btn btn-primary">Set</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="modal fade" id="lawful-modal" style="z-index: 9999999;" tabindex="-1" role="dialog"
                             aria-labelledby="idbDataTableSettingsLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="idbDataTableSettingsLabel">
                                            <?= Translate::_(
                                                'business',
                                                'List Data Processors:'
                                            ) ?>
                                            :</h3>
                                        <button type="button" style="top: 15px; right: 15px; position:absolute;" class="close"
                                                data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php $form = ActiveForm::begin(['id' => 'lawful-form']); ?>

                                        <?= $form->field($model2, 'legal')
                                            ->dropDownList($legal)->label(
                                                Translate::_('business', "Lawful Basis")
                                            ); ?>
                                        <?= $form->field($model2, 'messages')
                                            ->dropDownList($messages)->label(
                                                Translate::_('business', "Select your defined message")
                                            ); ?>
                                        <?= $form->field($model2, 'message')->textarea(['rows' => 3])
                                            ->label(Translate::_('business', "Message content"))
                                            ->hint(Translate::_('business', "At this field you can provide your custom message content."),
                                                ['tag' => 'div', 'class' => 'alert alert-info']); ?>

                                        <h2><?= Translate::_('business', 'Purpose Limitation:') ?></h2>
                                        <?= $form->field($model2, 'purposeLimitation')->textarea(['rows' => 3])
                                            ->label(Translate::_('business', "Provide: Specified, explicit and legitimate purposes")) ?>

                                        <?php ActiveForm::end(); ?>

                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" id="lawful-set" class="btn btn-primary">Set</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="modal fade" id="period-modal" style="z-index: 9999999;" tabindex="-1" role="dialog"
                             aria-labelledby="idbDataTableSettingsLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="idbDataTableSettingsLabel">
                                            <?= Translate::_(
                                                'business',
                                                'Storage time limitations'
                                            ) ?>
                                            :</h3>
                                        <button type="button" style="top: 15px; right: 15px; position:absolute;" class="close"
                                                data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php $form = ActiveForm::begin(['id' => 'period-form']); ?>

                                        <?= $form->field($model, 'maximum')->input('number')
                                            ->label(Translate::_('business', "Maximum in Days")) ?>

                                        <div class="after-maximum <?= empty($model->maximum)?'idb-hidden': ''?>">
                                            <?= $form->field($model, 'minimum')->input('number')
                                                ->label(Translate::_('business', "Minimum in Days")) ?>

                                            <?= $form->field($model, 'onExpiry')
                                                ->dropDownList([
                                                    'destruction' => 'Destruction',
                                                    'pseudonymization' => 'Pseudonymization'
                                                ])->label(
                                                    Translate::_('business', "On Expiry")
                                                ); ?>

                                            <?= $form->field($model, 'reviewCycle')->input('number')
                                                ->label(Translate::_('business', "Review cycle in Days"))
                                                ->hint(Translate::_('business', "If you think you would like to keep data for longer provide review cycle value and we will send reminders."),
                                                    ['tag' => 'div', 'class' => 'alert alert-info']) ?>
                                        </div>
                                        <div class="after-review <?= empty($model->reviewCycle)?'idb-hidden': ''?>">
                                            <?= $form->field($model, 'explanation')->textarea(['rows' => 3])
                                                ->label(Translate::_('business', "Explanation")) ?>
                                        </div>
                                        <?php ActiveForm::end(); ?>

                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" id="period-set" class="btn btn-primary">Set</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    const emptyDisplayNameMessage = '<?= Translate::_(
        'business',
        'The display name cannot be blank, please enter a name and then save.'
    ) ?>';
    const showAllURL = '<?= Url::toRoute(['idb-data/show-all'], true) ?>';
    const createURL = '<?= Url::toRoute(['data-client-sets/create'], true) ?>';
    const getModelURL = '<?= Url::toRoute(['data-client-sets/get-model', 'id' => $id], true) ?>';

    let sets = <?= json_encode($sets) ?>;
    let types = <?= json_encode($types) ?>;
</script>

<div class="modal fade" id="types-modal" style="z-index: 9999999;" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Type</h4>
            </div>
            <div class="modal-body">
                <select id="selected-type">
                    <?php foreach ($types as $key => $type): ?>
                        <option value="<?= $key ?>"><?= $type['display_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="add-selected-type" class="btn btn-primary" data-dismiss="modal">Add</button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="sets-modal" style="z-index: 9999999;" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Set</h4>
            </div>
            <div class="modal-body">
                <select id="selected-set">
                    <?php foreach ($sets as $key => $set): ?>
                        <option value="<?= $key ?>"><?= $set['display_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="add-selected-set" class="btn btn-primary" data-dismiss="modal">Add</button>
            </div>
        </div>

    </div>
</div>

<?= BusinessConfig::jsOptions(
    [
        'extendedClientSetsCreator' => BusinessConfig::get()->getJSBusinessExtendedClientSetsCreator()
    ]
) ?>

<?= Translate::js(
    [
        'somethingWrongMessage' => Translate::_('business', 'Something went wrong! Try again later.'),
        'displayNameMessage' => Translate::_('business', 'Display Name'),
        'requiredMessage' => Translate::_('business', 'Required'),
        'sensitiveMessage' => Translate::_('business', 'Sensitive'),
        'addTypeMessage' => Translate::_('business', 'Add Type'),
        'addPredefinedType' => Translate::_('business', 'Add Predefined Type'),
        'addSetMessage' => Translate::_('business', 'Add Set'),
        'addPredefinedSet' => Translate::_('business', 'Add Predefined Set'),
        'dataCategoryMessage' => Translate::_('business', 'Category'),
        'normalCategoryMessage' => Translate::_('business', 'Normal'),
        'healthCategoryMessage' => Translate::_('business', 'Health'),
        'specialCategoryMessage' => Translate::_('business', 'Special'),
    ]
) ?>
