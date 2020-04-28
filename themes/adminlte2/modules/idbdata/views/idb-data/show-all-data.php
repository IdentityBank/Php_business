<?php

use app\assets\DataTableAsset;
use app\assets\IdbDataAsset;
use app\helpers\ReturnUrl;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\adminlte2\views\yii\widgets\grid\DataTable;
use idbyii2\helpers\DataHTML;
use idbyii2\helpers\Metadata;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$dbName = BusinessDatabase::findOne(
    ['aid' => Yii::$app->user->identity->aid, 'dbid' => Yii::$app->user->identity->dbid]
);
if ($dbName) {
    $dbName = $dbName->name;
}

$dataTableAsset = DataTableAsset::register($this);
$dataAssets = IdbDataAsset::register($this);
$dataAssets->showAllAssets();

$url = ReturnUrl::generateUrl();

$isUserDatabaseUsedForApproved = Yii::$app->user->identity->isUserDatabaseUsedForApproved();

?>

<style>
    .unstyled-button {
        border: none;
        padding: 0;
        background: none;
    }

    .glyphicon-trash {
        color: #3c8dbc;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Access vault')) ?>
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
                        <h3 class="box-title">
                            <i class="fa fa-tasks"></i>&nbsp;&nbsp;<?= Html::encode($dbName) ?>
                        </h3>
                    </div>
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

                    <div class="box-header with-border">
                        <?= Html::a(
                            '<i class="fa fa-plus-circle"></i> ' . Translate::_('business', 'Add New Person'),
                            ['/idbdata/idb-data/create'],
                            ['class' => 'btn btn-app']
                        ) ?>
                        <?= Html::a(
                            '<i class="fa fa-edit"></i> ' . Translate::_('business', 'Edit safes'),
                            ['/idbdata/data-client-sets/create'],
                            ['class' => 'btn btn-app']
                        ) ?>
                        <?= Html::button(
                            '<i class="fa fa-hand-o-down"></i> ' . Translate::_('business', 'Audit log'),
                            [
                                'data-toggle' => "modal",
                                'id' => "audit-multiple",
                                'class' => "btn btn-app",
                                'style' => "color: #FF0000;",
                                'disabled' => 'disabled',
                                'data-target' => "#used-for-modal"
                            ]
                        ) ?>

                        <?= Yii::$app->controller->renderPartial(
                            '@app/themes/adminlte2/views/site/_modalWindow',
                            [
                                'modal' => [
                                    'name' => 'cancelFormActionModal_',
                                    'header' => Translate::_('business', 'Delete people data'),
                                    'body' => Translate::_(
                                        'business',
                                        'That action will permanently remove your data. Are you sure you want continue that action?'
                                    ),
                                    'question' => Translate::_(
                                        'business',
                                        'If this is not your intention, please click on "Cancel delete action".'
                                    ),
                                    'button' => [
                                        'label' => '<span class="glyphicon glyphicon-trash"></span>' . Translate::_(
                                                'business',
                                                'Delete multiple'
                                            ),
                                        'class' => 'btn btn-app',
                                        'style' => 'color: #3c8dbc;',
                                        'disabled' => 'disabled',
                                        'id' => "delete-multiple",
                                    ],
                                    'leftButton' => [
                                        'label' => Translate::_('business', 'Permanently delete selected row(s)'),
                                        'style' => 'btn btn-back',
                                        'id' => 'send-delete-multiple',
                                        'action' => 'data-dismiss'
                                    ],
                                    'rightButton' => [
                                        'label' => Translate::_('business', 'Cancel delete action'),
                                        'style' => 'btn btn-success',
                                        'action' => 'data-dismiss',
                                        'style' => 'btn btn-primary'
                                    ],
                                ]
                            ]
                        ); ?>


                        <?php if (Yii::$app->session->get('search')): ?>
                            <?= Html::a(
                                '<i class="fa fa-search-minus"></i> ' . Translate::_('business', 'Reset search'),
                                ['idb-data/reset-search'],
                                ['class' => 'btn btn-app']
                            ); ?>
                        <?php endif; ?>
                        <?= Html::a(
                            '<i class="fa fa-file-import"></i> ' . Translate::_('business', 'Import'),
                            ['/tools/wizard/index'],
                            ['class' => 'btn btn-app']
                        ); ?>
                        <?= Html::a(
                            '<i class="fa fa-file-text-o"></i> ' . Translate::_('business', 'Export'),
                            ['/tools/export/prepare'],
                            ['class' => 'btn btn-app']
                        ); ?>
                        <?php if(BusinessConfig::get()->getDebugBusinessDebugMetadataView()) : ?>
                        <?= Html::a(
                            '<i class="fa fa-clipboard-list"></i> ' . Translate::_('business', 'Metadata'),
                            ['idb-data/metadata'],
                            ['class' => 'btn btn-app btn-app-red']
                        ); ?>
                        <?php endif; ?>
                        <?= Html::button(
                            '<span class="glyphicon glyphicon-cog"></span>' . Translate::_('business', 'Options'),
                            [
                                'class' => 'btn btn-app',
                                'data-toggle' => "modal",
                                'style' => "float:right;",
                                'data-target' => "#idbDataTableSettings"
                            ]
                        ) ?>
                    </div>

                    <div class="box-body">
                        <div style="clear: both;"></div>
                        <?php if ($dataProvider->getTotalCount() < 1): ?>
                            <h3 class="no-data-header">
                                <?php if ($dataProvider->getCountWithoutFilters() > 0): ?>
                                    <?= Translate::_(
                                        'business',
                                        'No data available for this search. Please enter different search data.'
                                    ) ?>
                                    <?= Html::a(
                                        '<i class="fa fa-search-minus"></i> ' . Translate::_(
                                            'business',
                                            'Reset search'
                                        ),
                                        ['idb-data/reset-search'],
                                        ['class' => 'btn btn-app']
                                    ); ?>
                                <?php else: ?>
                                    <?= Translate::_(
                                        'business',
                                        'No data available. You will need to add some data to use this option.'
                                    ) ?>
                                    <?= Html::a(
                                        '<i class="fa fa-plus-circle"></i>',
                                        ['/idbdata/idb-data/create'],
                                        ['class' => 'btn btn-app']
                                    ) ?>
                                <?php endif; ?>
                            </h3>
                        <?php else: ?>
                            <?= DataTable::widget(
                                [
                                    'id' => 'grid_id_user_manager',
                                    'dataProvider' => $dataProvider,
                                    'pager' => [
                                        'firstPageLabel' => Translate::_('business', 'First'),
                                        'lastPageLabel' => Translate::_('business', 'Last')
                                    ],
                                    'tableOptions' => ['id' => 'table_id_user_manager'],
                                    'columns' => DataHTML::generateColumns($metadata),
                                ]
                            ); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="idbDataTableSettings" style="z-index: 9999999;" tabindex="-1" role="dialog"
     aria-labelledby="idbDataTableSettingsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="idbDataTableSettingsLabel">
                    <?= Translate::_(
                        'business',
                        'Vault settings'
                    ) ?>:
                </h3>
                <button type="button" style="top: 15px; right: 15px; position:absolute;" class="close"
                        data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?= Url::toRoute(['set-display'], true) ?>">
                <div class="modal-body">
                    <h3 class="modal-first-h"><?= Translate::_('business', 'Select safes to display') ?>:</h3>
                    <label><input type="checkbox" id="checkAll">Select/Unselect all</label>

                    <div id="columns-pager">
                        <table id="show-column-group-1" class="show-column-group">
                            <?php
                            $counter = 1;
                            $groupCounter = 2;
                            ?>
                            <?php foreach ($metadata['database'] as $db):
                            $checked = '';

                            if (
                                (!empty($metadata['settings'][Yii::$app->user->identity->id][$db['uuid']])
                                    && $metadata['settings'][Yii::$app->user->identity->id][$db['uuid']] === 'on')
                                || empty($metadata['settings'][Yii::$app->user->identity->id])
                            ) {
                                $checked = 'checked="checked"';
                            }
                            ?>
                            <tr>
                                <td><?= mb_strimwidth(
                                        strip_tags(DataHTML::getDisplayName($db['uuid'], $metadata)),
                                        0,
                                        50,
                                        '...'
                                    ) ?></td>
                                <td style="padding-left: 20px;">
                                    <input type="checkbox"
                                           id="<?= DataHTML::getDisplayName($db['uuid'], $metadata) ?>"
                                           class="column_checkbox" <?= $checked ?>
                                           name="display[<?= $db['uuid'] ?>]"/>
                                </td>
                            </tr>
                            <?php if ($counter % 10 == 0): ?>
                        </table>
                        <table class="hidden show-column-group" id="show-column-group-<?= $groupCounter ?>">
                            <?php
                            $groupCounter++;
                            ?>
                            <?php endif; ?>
                            <?php
                            $counter++;
                            ?>
                            <?php endforeach; ?>

                        </table>
                    </div>

                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                           value="<?= Yii::$app->request->csrfToken; ?>"/>

                    <h3><?= Translate::_('business', 'Search options') ?>:</h3>
                    <table>
                        <tr>
                            <td><?= Translate::_('business', 'Case Sensitive Search') ?>:</td>
                            <td style="padding-left: 20px;">
                                <input type="checkbox"
                                    <?= $otherOptions['caseSensitive'] ? 'checked' : '' ?>
                                       name="case_sensitive"/>
                            </td>
                        </tr>

                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form class="hidden" id="search-form" method="POST">
    <textarea name="search" id="search-json" cols="30" rows="10"></textarea>
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
           value="<?= Yii::$app->request->csrfToken; ?>"/>
</form>

<div class="loading hidden"></div>

<div class="modal fade" id="used-for-modal" style="z-index: 9999999;" tabindex="-1" role="dialog"
     aria-labelledby="idbDataTableSettingsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="idbDataTableSettingsLabel">
                    <?= Translate::_(
                        'business',
                        'Why do you need to use this information?'
                    ) ?>
                    :</h3>
                <button type="button" style="top: 15px; right: 15px; position:absolute;" class="close"
                        data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <select id="used-for-legal" style="width: 100%; margin-bottom:20px;">
                    <option selected disabled hidden value=""><?= Translate::_(
                            'business',
                            'Select lawful basis for use'
                        ) ?></option>
                    <?php foreach ($legal as $message): ?>
                        <option value="<?= $message->message ?>"> <?= $message->message ?> </option>
                    <?php endforeach; ?>
                </select>

                <select id="used-for-select" style="width: 100%; margin-bottom: 20px;">
                    <option selected disabled hidden value=""><?= Translate::_(
                            'business',
                            'Your reason to use this information now'
                        ) ?></option>
                    <?php foreach ($messages as $message): ?>
                        <option value="<?= strip_tags($message->message) ?>"> <?= strip_tags($message->message) ?> </option>
                    <?php endforeach; ?>
                    <option value="other"><?= Translate::_('business', 'Other reason, type below') ?></option>
                </select>
                <textarea style="width: 100%; height: 150px;" id="used-for-area"><?= strip_tags(empty($messages[0]->message)) ? ''
                        : strip_tags($messages[0]->message) ?></textarea>
                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                       value="<?= Yii::$app->request->csrfToken; ?>"/>
                <input type="hidden" id="used-for-sms" value="<?= $metadata['options']['send_sms']; ?>"/>
                <input type="hidden" id="used-for-mail" value="<?= $metadata['options']['send_mail']; ?>"/>
                <?php if (
                    isset($metadata['PeopleAccessMap']['mobile_no'])
                    && isset($metadata['PeopleAccessMap']['email_no'])
                    && Metadata::hasType($metadata['PeopleAccessMap']['mobile_no'], $metadata)
                    && Metadata::hasType($metadata['PeopleAccessMap']['email_no'], $metadata)
                ) : ?>
                    <input type="hidden" id="col_mobile" value="<?= $metadata['PeopleAccessMap']['mobile_no']; ?>"/>
                    <input type="hidden" id="col_mail" value="<?= $metadata['PeopleAccessMap']['email_no']; ?>"/>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" id="used-for-send" class="btn btn-primary">Send</button>
            </div>
        </div>
    </div>
</div>

<?php if (!$isUserDatabaseUsedForApproved) : ?>
    <div class="form-group">
        <?= Yii::$app->controller->renderPartial(
            '@app/themes/adminlte2/views/site/_modalWindow',
            [
                'modal' => [
                    'name' => 'userDatabaseUsedForApprovedFormActionModal',
                    'header' => Translate::_('business', 'Audit Log'),
                    'body' => Translate::_(
                            'business',
                            'To use data in your vault you will need to give a lawful basis and a reason for legal compliance. Recording your actions also demonstrates your commitment to transparency and trust towards your data subjects. Select the ‘Audit Log’ button to record your reasons.'
                        ) . '<br><br>' . Html::a(
                            Translate::_(
                                'business',
                                'Take me to help for more information.'
                            ),
                            Translate::_('business', 'https://www.identitybank.eu/help/business/auditLog'),
                            [
                                'target' => '_blank',
                                'title' => Translate::_('business', 'How to ...')
                            ]
                        ),
                    'question' => Html::checkbox(
                        'userDatabaseUsedForApprovedFormActionModalCheckbox',
                        false,
                        [
                            'id' => 'userDatabaseUsedForApprovedFormActionModalCheckbox',
                            'label' => Translate::_('business', 'Do not show this again'),
                            'onclick' => 'userDatabaseUsedForApprovedFormActionModalCheckboxAction(this);'
                        ]
                    ),
                    'leftButton' => [
                        'label' => Translate::_('business', 'I understand') . ' - ' . Translate::_(
                                'business',
                                'Do not show this again'
                            ),
                        'id' => 'userDatabaseUsedForApprovedFormActionModalLeftButton',
                        'action' => Url::toRoute(['show-all', 'idbAttr' => 'AuditLog'], true),
                        'style' => 'btn btn-primary hidden',
                        'buttonStyle' => 'float:right;',
                    ],
                    'rightButton' => [
                        'label' => Translate::_('business', 'I understand'),
                        'id' => 'userDatabaseUsedForApprovedFormActionModalRightButton',
                        'style' => 'btn btn-primary',
                        'action' => 'data-dismiss'
                    ],
                ]
            ]
        ); ?>
    </div>

    <script>
        function userDatabaseUsedForApprovedFormActionModalCheckboxAction(checkbox) {
            if (checkbox.checked) {
                // $("#userDatabaseUsedForApprovedFormActionModalCheckbox").prop("disabled", true);
                $("#userDatabaseUsedForApprovedFormActionModalLeftButton").removeClass('hidden');
                $("#userDatabaseUsedForApprovedFormActionModalRightButton").addClass('hidden');
            } else {
                $("#userDatabaseUsedForApprovedFormActionModalLeftButton").addClass('hidden');
                $("#userDatabaseUsedForApprovedFormActionModalRightButton").removeClass('hidden');
            }
        }

        function initUserDatabaseUsedForApprovedFormActionModal() {
            $(document).ready(function () {
                $('#userDatabaseUsedForApprovedFormActionModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#userDatabaseUsedForApprovedFormActionModal').modal('show');
            });
        }
    </script>
    <?php $this->registerJs('initUserDatabaseUsedForApprovedFormActionModal()', View::POS_END); ?>
<?php endif; ?>

<script>

    let model = <?= json_encode($metadata) ?>;
    if (typeof model === 'string') {
        model = JSON.parse(model);
    }

    <?php if(!empty($search)): ?>
    let afterSearch = <?= json_encode($search) ?>;
    if (typeof afterSearch === 'string') {
        afterSearch = JSON.parse(afterSearch);
        afterSearch = JSON.parse(afterSearch);
    }

    <?php else: ?>
    let afterSearch = false;
    <?php endif; ?>


    const usedForURL = '<?= Url::toRoute(['idb-data/save-used-for'], true) ?>';
    const deleteMultipleURL = '<?= Url::toRoute(['idb-data/delete-multiple'], true) ?>';

</script>

<style>
    .no-sort-checkbox::after, .no-sort::after {
        display: none !important;
    }

    .no-sort {
        pointer-events: none !important;
        cursor: default !important;
    }

    .no-sort-checkbox {
        cursor: default !important;
    }
</style>
