<?php

use app\assets\FrontAsset;
use app\assets\SelectAsset;
use app\helpers\Translate;
use idbyii2\helpers\File;
use idbyii2\helpers\Localization;
use idbyii2\widgets\FlashMessage;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$uploadAssets = FrontAsset::register($this);
$uploadAssets->idbStorageUpload();
$selectAssets = SelectAsset::register($this);

//$dbName = BusinessDatabase::findOne(
//    ['aid' => Yii::$app->user->identity->aid, 'dbid' => Yii::$app->user->identity->dbid]
//);
//if ($dbName) {
//    $dbName = $dbName->name;
//}
//
//$this->title = Html::encode($dbName) . ' - ' . Html::encode($name);

?>
<style>
    .upload-btn-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .btn-upload {
        margin-top: 50px;
        border: 2px dashed gray;
        color: gray;
        background-color: white;
        padding: 70px 250px;
        border-radius: 8px;
        font-size: 20px;
        font-weight: bold;
    }

    .upload-btn-wrapper input[type=file] {
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
    }

    .unstyled-button {
        border: none;
        padding: 0;
        background: none;
    }

    .glyphicon-trash {
        color: #3c8dbc;
    }
</style>
<section class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= $this->title ?>
        </h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-body">
                <?= FlashMessage::widget(
                    [
                        'success' => Yii::$app->session->hasFlash('success')
                            ? Yii::$app->session->getFlash(
                                'success'
                            ) : null,
                        'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash(
                            'error'
                        )
                            : null,
                        'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash(
                            'info'
                        )
                            : null,
                    ]
                ); ?>

                <section class="content">
                    <?php $form = ActiveForm::begin(['options' => ['method' => 'post']]); ?>
                    <div class="form-group" style="margin-bottom: 20px;">

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
                                        'Back'
                                    ),
                                    'style' => 'margin-left:-15px;',
                                    'class' => 'btn btn-back'
                                ],
                                'leftButton' => [
                                    'label' => Translate::_('business', 'Back'),
                                    'action' => Url::previous('idbstorage-file-requests'),
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
                    <button type="submit" style="margin-left: 15px;" class="btn btn-primary btn-create"><?= Translate::_('business', 'Save')?></button>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <table>
                                <thead>
                                <tr>
                                    <th><?= Translate::_('business', 'Request name') ?></th>
                                    <th style="padding-left: 40px;"><?= Translate::_('business', 'Uploads') ?></th>
                                    <th style="padding-left: 40px;"><?= Translate::_('business', 'Requested files') ?></th>
                                    <th style="padding-left: 40px;"><?= Translate::_('business', 'Completed') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= $request['name'] ?></td>
                                    <td style="padding-left: 40px;"><?= $request['uploads'] ?></td>
                                    <td style="padding-left: 40px;"> <?= $form->field($model, 'upload_limit')->input('number') ?></td>
                                    <td style="padding-top: 25px;padding-left: 40px;"><?= $form->field($model, 'type')->checkbox(['value' => 'complete'])?></td>
                                </tr>
                                </tbody>
                            </table>

                            <table style=" margin-top: 30px; margin-bottom: 30px;">
                                <thead>
                                <tr>
                                    <th><?= Translate::_('business', 'Message') ?></th>

                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= $request['message'] ?></td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <?php ActiveForm::end() ?>

                    <div class="row">
                        <div class="table-responsive">
                            <?= GridView::widget(
                                [
                                    'id' => 'idbstorage-datatable',
                                    'dataProvider' => $provider,
                                    'rowOptions' => function ($model, $key, $index, $grid) {
                                        return [
                                            'class' => 'clickable-row',
                                            'data-href' => Url::toRoute(
                                                ['summary', 'oid' => $model->oid],
                                                true
                                            )
                                        ];
                                    },
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'header' => '<span id="col-name-change-type">' . Translate::_(
                                                    'business',
                                                    'Filename'
                                                ) . '</span>',
                                            'value' => function ($model) {
                                                return $model->name;
                                            }
                                        ],
                                        [
                                            'header' => '<span id="col-name-change-type">' . Translate::_(
                                                    'business',
                                                    'Upload time'
                                                ) . '</span>',
                                            'value' => function ($model) {

                                                return Localization::getDateTimePortalFormat(
                                                    new DateTime($model->createtime)
                                                );
                                            }
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'template' => '{summary}{delete}',
                                            'header' => '',
                                            'contentOptions' => ['class' => 'click-disabled'],
                                            'headerOptions' => [
                                                'id' => 'idb_action',
                                                'class' => 'no-sort',
                                                'style' => 'width: 48px;'
                                            ],
                                            'visibleButtons' => [
                                                'delete' => true,
                                                'summary' => true,
                                            ],
                                            'buttons' => [
                                                'summary' => function ($url, $model, $key) {
                                                    return Html::a(
                                                        '<span data-object-oid="' . $model->oid
                                                        . '" data-object-id="' . $model->id
                                                        . '" class="glyphicon glyphicon-pencil"></span></a>',
                                                        ['summary', 'oid' => $model->oid],
                                                        [
                                                            'class' => 'file-summaries-button unstyled-button',
                                                            'style' => 'cursor:pointer;',
                                                        ]
                                                    );
                                                },
                                                'delete' => function ($url, $model, $key) {
                                                    return Yii::$app->controller->renderPartial(
                                                        '@app/themes/adminlte2/views/site/_modalWindow',
                                                        [
                                                            'modal' => [
                                                                'name' => 'cancelFormActionModal_'
                                                                    . preg_replace(
                                                                        "/[^A-Za-z0-9 ]/",
                                                                        '_',
                                                                        base64_encode($model->id)
                                                                    ),
                                                                'header' => \idbyii2\helpers\Translate::_(
                                                                    'business',
                                                                    'Delete IDB storage data'
                                                                ),
                                                                'body' => Translate::_(
                                                                    'business',
                                                                    'That action will permanently remove your data. Are you sure you want continue that action?'
                                                                ),
                                                                'question' => Translate::_(
                                                                    'business',
                                                                    'If this is not your intention, please click on "Cancel delete action".'
                                                                ),
                                                                'button' => [
                                                                    'label' => '<span class="glyphicon glyphicon-trash"></span>',
                                                                    'class' => 'unstyled-button'
                                                                ],
                                                                'leftButton' => [
                                                                    'label' => Translate::_(
                                                                        'business',
                                                                        'Permanently delete selected row'
                                                                    ),
                                                                    'action' => Url::toRoute(
                                                                        [
                                                                            'delete',
                                                                            'objectId' => $model->oid
                                                                        ],
                                                                        true
                                                                    ),
                                                                    'style' => 'btn btn-back'
                                                                ],
                                                                'rightButton' => [
                                                                    'label' => Translate::_(
                                                                        'business',
                                                                        'Cancel delete action'
                                                                    ),
                                                                    'style' => 'btn btn-success',
                                                                    'action' => 'data-dismiss',
                                                                ],
                                                            ]
                                                        ]
                                                    );
                                                }
                                            ]
                                        ]
                                    ],
                                ]
                            ); ?>


                        </div>
                    </div>
                </section>

            </div>
        </div>
    </section>

</section>


<?= BusinessConfig::jsOptions(
    [
        'checkUrl' => null,
        'shared' => '[]',
        'summariesUrl' => Url::toRoute('summaries'),
        'getUsersUrl' => Url::toRoute(['get-users'], true),
        'initObjectUrl' => Url::toRoute('init-object', true),
        'uuid' => null,
        'uploadLimit' => null,
        'showInactiveUrl' => Url::toRoute('show-inactive', true)
    ]
) ?>
