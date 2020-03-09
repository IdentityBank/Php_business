<?php

use app\assets\DataTableAsset;
use app\assets\IdbDataAsset as aIdbDataAsset;
use app\helpers\ReturnUrl;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use app\themes\adminlte2\views\yii\widgets\grid\DataTable;
use idbyii2\helpers\DataHTML;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;
use yii\helpers\Url;

$dataTableAsset = DataTableAsset::register($this);
$dataAssets = aIdbDataAsset::register($this);
$dataAssets->showAllAssets();

$url = ReturnUrl::generateUrl();

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Connect with people')) ?>
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
                        <h4 class="box-title">
                            <i class="fa fa-tasks"></i>&nbsp;<?= Translate::_('business', 'Vault name'); ?>:&nbsp;
                            <b><?= Html::encode($dbName) ?></b>
                        </h4>
                    </div>
                    <div class="box-header with-border">
                        <?php if (Yii::$app->session->get('people-access-search')): ?>
                            <?= Html::a(
                                '<i class="fa fa-search-minus"></i> ' . Translate::_('business', 'Reset search'),
                                ['/applications/contacts/reset-search'],
                                ['class' => 'btn btn-app']
                            ); ?>
                        <?php endif; ?>

                        <h3>
                            <?= Translate::_(
                                'business',
                                'Select people you want to invite.'
                            ); ?>
                        </h3>

                    </div>
                    <br>
                    <div class="box-body">
                        <div style="clear: both;"></div>
                        <?= Html::beginForm(['/applications/contacts/access'], 'post', ['id' => 'search-form']); ?>
                        <div class="form-group">
                            <?= Html::submitButton(
                                Translate::_('business', 'Invite selected people'),
                                ['class' => 'btn btn-primary', ['id' => 'submit-button-form']]
                            );
                            ?>
                        </div>
                        <textarea name="search" id="search-json" cols="30" rows="10" style="display: none"></textarea>
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                               value="<?= Yii::$app->request->csrfToken; ?>"/>
                        <?php if ($dataProvider->getTotalCount() < 1): ?>
                            <h3 class="no-data-header">
                                <?= Translate::_(
                                    'business',
                                    'No data available. You will need to add some data to use this option.'
                                ) ?>
                                <?= Html::a(
                                    '<i class="fa fa-plus-circle"></i>',
                                    ['/idbdata/idb-data/create'],
                                    ['class' => 'btn btn-app']
                                ) ?>
                            </h3>
                        <?php else: ?>
                            <?= DataTable::widget(
                                [
                                    'id' => 'grid_id_user_manager',
                                    'dataProvider' => $dataProvider,
                                    'tableOptions' => ['id' => 'table_id_user_manager'],
                                    'columns' => DataHTML::generateColumnsForPeopleAccess($metadata)
                                ]
                            ); ?>
                        <?php endif; ?>
                        <?= Html::endForm(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="loading hidden"></div>

<script>
    var model = <?= json_encode($metadata) ?> ;
    if (typeof model === 'string') {
        model = JSON.parse(model);
    }

    <?php if(!empty($search)): ?>
    var afterSearch = <?= json_encode($search) ?> ;
    if (typeof afterSearch === 'string') {
        afterSearch = JSON.parse(afterSearch);
        afterSearch = JSON.parse(afterSearch);
    }

    <?php else: ?>
    var afterSearch = false;
    <?php endif; ?>

    const usedForURL = '<?= Url::toRoute(['idb-data/save-used-for'], true) ?>';

</script>
