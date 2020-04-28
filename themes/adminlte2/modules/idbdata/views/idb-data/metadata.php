<?php

use app\assets\JsonEditorAsset;
use app\helpers\Translate;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\models\db\BusinessDatabaseData;
use yii\helpers\Html;
use yii\web\View;

JsonEditorAsset::register($this);

$dbName = BusinessDatabase::findOne(
    ['aid' => Yii::$app->user->identity->aid, 'dbid' => Yii::$app->user->identity->dbid]
);
if ($dbName) {
    $dbName = $dbName->name;
}

$businessId = Yii::$app->user->identity->getBusinessDbId();
$idbDatabaseData = BusinessDatabaseData::getDatabaseNameByBusinessId($businessId);

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'The vault metadata')) ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-database"></i>&nbsp;&nbsp;
                            <?= Html::encode($dbName) ?>&nbsp;
                        </h3>
                        <h4>
                            [Organization:&nbsp;<?= Html::encode(Yii::$app->user->identity->oid) ?>].
                            [Account:&nbsp;<?= Html::encode(Yii::$app->user->identity->aid) ?>].
                            [DB:&nbsp;<?= Html::encode(Yii::$app->user->identity->dbid) ?>]&nbsp;
                        </h4>
                        <h4>
                            [Business ID:&nbsp;<?= Html::encode($businessId) ?>]
                        </h4>
                        <h4>
                            [Database ID:&nbsp;<?= Html::encode($idbDatabaseData) ?>]
                        </h4>
                    </div>
                    <div class="box-body">
                        <div id="jsoneditor" class="jsoneditor jsoneditor-mode-code" style="height: 700px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function setJsonData(data) {
        var editor = new JSONEditor(document.getElementById("jsoneditor"),
            {
                modes: ['text', 'code', 'view'],
                mode: 'code',
                search: false
            });
        editor.set(data);
    }

    function initPage() {
        $(document).ready(function () {
            setJsonData(JSON.parse('<?= $metadata ?>'));
        });
    }
</script>
<?php $this->registerJs("initPage()", View::POS_END); ?>
