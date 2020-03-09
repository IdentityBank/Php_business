<?php

use app\assets\DataTableAsset;
use app\assets\IdbDataAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\adminlte2\views\yii\widgets\grid\DataTable;
use idbyii2\helpers\Localization;
use idbyii2\helpers\Tag;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\widgets\FlashMessage;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;


$dbName = BusinessDatabase::findOne(
    ['aid' => Yii::$app->user->identity->aid, 'dbid' => Yii::$app->user->identity->dbid]
);
if ($dbName) {
    $dbName = $dbName->name;
}

$dataTableAsset = DataTableAsset::register($this);
$dataAssets = IdbDataAsset::register($this);
$dataAssets->logTableAssets();
?>

<style>
    .unstyled-button {
        border: none;
        padding: 0;
        background: none;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Review change requests')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <?= FlashMessage::widget(
        [
            'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success') : null,
            'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : null,
            'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : null,
        ]
    ); ?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-tasks"></i>&nbsp;&nbsp;<?= Html::encode($dbName) ?>
                        </h3>
                    </div>

                    <div class="box-header with-border">
                        <?php if (Yii::$app->session->get('search')): ?>
                            <?= Html::a(
                                '<i class="fa fa-search-minus"></i> ' . Translate::_('business', 'Reset search'),
                                ['reset-search'],
                                ['class' => 'btn btn-app']
                            ); ?>
                        <?php endif; ?>
                    </div>

                    <div class="box-body">
                        <?php if (count($dataProvider->models) < 1): ?>
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
                                        ['reset-search'],
                                        ['class' => 'btn btn-app']
                                    ); ?>
                                <?php else: ?>
                                    <?= Translate::_('business', 'No data available.') ?>
                                <?php endif; ?>
                            </h3>
                        <?php else: ?>
                            <?= DataTable::widget(
                                [
                                    'id' => 'grid_id_user_manager',
                                    'dataProvider' => $dataProvider,
                                    'tableOptions' => ['id' => 'table_id_user_manager'],
                                    'columns' => [
                                        [
                                            'header' => '<span id="col-name-date">' . Translate::_('business', 'Date')
                                                . '</span>',
                                            'value' => function ($model) {
                                                return Localization::getDateTimePortalFormat(new DateTime($model[4]));
                                            }
                                        ],
                                        [
                                            'header' => '<span id="col-name-change-type">' . Translate::_(
                                                    'business',
                                                    'Changed by user'
                                                ) . '</span>',
                                            'value' => function ($model) {

                                                $json = json_decode($model[3], true);
                                                if (!empty($json['userId'])) {
                                                    return $json['userId'];
                                                }

                                                return '';
                                            }
                                        ],
                                        [
                                            'header' => '<span id="col-name-change-type">' . Translate::_(
                                                    'business',
                                                    'Who changed'
                                                ) . '</span>',
                                            'value' => function ($model) {
                                                $tags = Tag::parseTagsToArray($model[5]);

                                                if (in_array('PEOPLE', $tags)) {
                                                    return Translate::_('business', 'Customer');
                                                }
                                                if (in_array('BUSINESS', $tags)) {
                                                    return Translate::_('business', 'Business');
                                                }

                                                return '';
                                            }
                                        ],
                                        [
                                            'format' => 'html',
                                            'header' => '<span id="col-name-change">' . Translate::_(
                                                    'business',
                                                    'Change'
                                                ) . '</span>',
                                            'value' => function ($model) {
                                                $json = json_decode($model[3] ?? null, true);
                                                if (ArrayHelper::getValue($json, 'deleteAll', false)) {
                                                    $days = Localization::getDiffInDays(new \DateTime(), new \DateTime($model[4]), 30);
                                                    if($days > 0) {
                                                        return Translate::_('business', 'Going to be disconnected in {days} days.', compact('days'));
                                                    }

                                                    return Translate::_('business', 'Disconnected with business.');
                                                }
                                                if (!empty($json['data'])) {
                                                    return $this->context->renderPartial(
                                                        '_changes_view',
                                                        ['data' => $json['data']]
                                                    );
                                                }
                                            }
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'template' => '{reverse}',
                                            'header' => '',
                                            'headerOptions' => ['id' => 'idb_action', 'class' => 'no-sort'],
                                            'visibleButtons' => [
                                                'reverse' => true,
                                            ],
                                            'buttons' => [
                                                'reverse' => function ($url, $model, $key) {
                                                    $json = json_decode($model[3], true);
                                                    $target = null;
                                                    if (ArrayHelper::getValue($json, 'deleteAll', false)) {
                                                        return '';
                                                    }
                                                    if (!empty($json) && !empty($json['data'])) {
                                                        $target = '#idb-revert-' . $model[0];
                                                    }

                                                    return Html::a(
                                                        '<span class="glyphicon glyphicon-repeat"></span></a>',
                                                        null,
                                                        [
                                                            'style' => 'cursor:pointer;color: #FF0000;',
                                                            'class' => 'reverse',
                                                            'data-id' => $model[0],
                                                            'data-toggle' => "modal",
                                                            'data-target' => $target,
                                                            'title' => Translate::_('business', "Reverse change")
                                                        ]
                                                    );
                                                },

                                            ]
                                        ]
                                    ],
                                ]
                            ); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<form class="hidden" id="search-form" method="POST">
    <textarea name="search" id="search-json" cols="30" rows="10"></textarea>
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
           value="<?= Yii::$app->request->csrfToken; ?>"/>
</form>

<?php foreach ($dataProvider->models as $model): ?>
    <?php
    $json = json_decode($model[3], true);
    if (empty($json) || empty($json['data'])) {
        continue;
    }
    ?>
    <div class="modal fade" id="idb-revert-<?= $model[0] ?>" style="z-index: 9999999;" tabindex="-1" role="dialog"
         aria-labelledby="idbDataTableSettingsLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header with-border">
                    <h4 class="modal-title" id="idbDataTableSettingsLabel">
                        <b><?= Translate::_(
                                'business',
                                'Select item(s) to revert'
                            ) ?>:</b>
                    </h4>
                    <button type="button" style="top: 15px; right: 15px; position:absolute;" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= Url::toRoute(['revert'], true) ?>">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                               value="<?= Yii::$app->request->csrfToken; ?>"/>

                        <table class="revert-table">
                            <?= $this->context->renderPartial('_reverts_view', ['data' => $json['data']]) ?>
                        </table>

                        <hr>
                        <div class="form-group">
                            <label><?= Translate::_('business', 'Reason why change request is rejected') ?></label>
                            <textarea
                                    required="required"
                                    rows="3"
                                    name="message"
                                    class="form-control"
                                    placeholder="<?= Translate::_(
                                        'business',
                                        'Enter reason for rejection...'
                                    ) ?>"></textarea>
                        </div>

                        <input type="hidden" name="businessId" value="<?= $json['businessId'] ?>"/>
                        <input type="hidden" name="peopleId" value="<?= $json['peopleId'] ?? '' ?>"/>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Revert</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<div class="loading hidden"></div>

<script>
    <?php if(!empty($search)): ?>
    var afterSearch = <?= json_encode($search) ?> ;
    if (typeof afterSearch === 'string') {
        afterSearch = JSON.parse(afterSearch);
        afterSearch = JSON.parse(afterSearch);
    }

    <?php else: ?>
    var afterSearch = false;
    <?php endif; ?>
</script>
