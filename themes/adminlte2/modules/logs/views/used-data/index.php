<?php

use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use idbyii2\models\identity\IdbBusinessUser;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$metadata = null;
if ($clientModel->getAccountMetadata() !== null) {
    $metadata = json_decode($clientModel->getAccountMetadata()['Metadata'], true);
    $metadata;
}

?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Audit log')) ?>
        </h1>
        <?= Breadcrumbs::widget(
            ['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]
        ) ?>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body" id="audit-table">
                        <div style="clear: both;"></div>

                        <?php Pjax::begin(); ?>
                        <?= GridView::widget(
                            [
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'filterSelector' =>
                                    '#IdbAuditLogSearchUsedBy, #IdbAuditLogSearchMessage',

                                'columns' => [
                                    [
                                        'header' => '<span class="col_name">' . Translate::_('business', 'Used By') . '</span> <br/>
                                            <div class="input-group input-group-sm hidden-xs">
                                                <input class="search form-control pull-right" id="IdbAuditLogSearchUsedBy" name="IdbAuditLogSearch[usedBy]" type="text"/>
                                                <div class="input-group-btn">
                                                    <button class="btn btn-default btn-search"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>',
                                        'attribute' => 'usedBy',
                                        'value' => function ($model) {
                                            $identity = IdbBusinessUser::findIdentity($model->portal_uuid);

                                            return $identity->email;
                                        }
                                    ],
                                    [
                                        'attribute' => 'Message',
                                        'label' => Translate::_('business', 'Message'),
                                        'value' => function ($model) use ($clientModel, $businessModel, $metadata) {
                                            return $model->message;
                                        }
                                    ],

                                    [
                                        'header' => '<span class="col_name">' . Translate::_('business', 'Used') . '</span> <br/>
                                        <div class="input-group input-group-sm hidden-xs">
                                            <input class="search form-control pull-right" id="IdbAuditLogSearchMessage" name="IdbAuditLogSearch[used]" type="text"/>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default btn-search"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>',
                                        'attribute' => 'message',
                                        'value' => function ($model) {
                                            $identity = IdbBusinessUser::findIdentity($model->portal_uuid);

                                            return $identity->email;
                                        }
                                    ],


                                    [
                                        'attribute' => 'date',
                                        'label' => Translate::_('business', 'Date'),
                                        'value' => function ($model) {
                                            return (new DateTime($model->timestamp))->format('Y-m-d H:i');
                                        }
                                    ],

                                ]
                            ]
                        ); ?>

                        <?php Pjax::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
