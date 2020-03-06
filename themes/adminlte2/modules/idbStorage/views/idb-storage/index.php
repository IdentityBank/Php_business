<?php

use app\assets\FrontAsset;
use app\assets\SelectAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\grid\GridView;
use idbyii2\helpers\File;
use idbyii2\helpers\Localization;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\widgets\FlashMessage;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$uploadAssets = FrontAsset::register($this);
$uploadAssets->idbStorageUpload();
$selectAssets = SelectAsset::register($this);

$dbName = BusinessDatabase::findOne(
    ['aid' => Yii::$app->user->identity->aid, 'dbid' => Yii::$app->user->identity->dbid]
);
if ($dbName) {
    $dbName = $dbName->name;
}

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

    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                <?= Html::encode($this->title) ?>
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-files-o"></i>&nbsp;&nbsp;<?= Html::encode($dbName) ?>
                            </h3>
                        </div>
                        <div class="box-body">
                            <section class="content container-fluid">
                                <div class="box-body">

                                    <?php if (empty($uuid)): ?>
                                        <?= Html::a(
                                            '<i class="fa fa-plus-circle"></i> ' . Translate::_(
                                                'business',
                                                'Add request for file'
                                            ),
                                            null,
                                            [
                                                'class' => 'btn btn-app',
                                                'id' => 'upload-request-button',
                                                'style' => 'margin-left: 0;'
                                            ]
                                        ) ?>
                                        <?= Html::a(
                                            '<i class="fa fa-exchange"></i> ' . Translate::_(
                                                'business',
                                                'File requests'
                                            ),
                                            ['file-requests'],
                                            ['class' => 'btn btn-app']
                                        ) ?>
                                        <?= Html::button(
                                            '<span class="glyphicon glyphicon-cog"></span>' . Translate::_(
                                                'business',
                                                'Options'
                                            ),
                                            [
                                                'class' => 'btn btn-app',
                                                'data-toggle' => "modal",
                                                'style' => "float:right;",
                                                'data-target' => "#storage-options-modal"
                                            ]
                                        ) ?>
                                    <?php else: ?>
                                        <table class="table" style="margin-bottom: 50px">
                                            <thead>
                                            <tr>
                                                <?php foreach ($user as $key => $data): ?>
                                                    <th><?= $key ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <?php foreach ($user as $data): ?>
                                                    <td><?= $data ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                            </tbody>
                                        </table>

                                    <?php endif; ?>

                                    <div style="clear: both;"></div>
                                    <?= FlashMessage::widget(
                                        [
                                            'success' => Yii::$app->session->hasFlash('success')
                                                ? Yii::$app->session->getFlash(
                                                    'success'
                                                ) : null,
                                            'error' => Yii::$app->session->hasFlash('error')
                                                ? Yii::$app->session->getFlash(
                                                    'error'
                                                )
                                                : null,
                                            'info' => Yii::$app->session->hasFlash('info')
                                                ? Yii::$app->session->getFlash(
                                                    'info'
                                                )
                                                : null,
                                        ]
                                    ); ?>
                                    <?php if (!empty($uuid)): ?>
                                        <div style="text-align: right;">
                                            <?= Translate::_('business', 'Show all files that user can sees') ?>
                                            <input id="show-all-files" <?= $showAllUserFiles ? 'checked="checked"'
                                                : '' ?> type="checkbox">
                                        </div>
                                    <?php endif; ?>
                                    <div class="table-responsive">
                                        <?= GridView::widget(
                                            [
                                                'id' => 'idbstorage-datatable',
                                                'dataProvider' => $dataProvider,
                                                'tableOptions' => ['id' => '-datatable'],
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
                                                            ) . '</span><br/>' .
                                                            '<form method="post"><div class="input-group input-group-sm hidden-xs">
                                                                    <input class="search form-control pull-right" value="'
                                                            . ArrayHelper::getValue($search, 'name', '') . '" name="search[name]" type="text"/>
                                                                    <div class="input-group-btn">
                                                                        <button type="submit" class="btn btn-default btn-search"><i class="fa fa-search"></i></button>
                                                                    </div>
                                                                    <input type="hidden" name="'
                                                            . Yii::$app->request->csrfParam . '" value="'
                                                            . Yii::$app->request->csrfToken . '" />
                                                                </div></form>',
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
                                                        'template' => '{summary} {show} {delete}',
                                                        'header' => '',
                                                        'contentOptions' => ['class' => 'click-disabled'],
                                                        'headerOptions' => [
                                                            'id' => 'idb_action',
                                                            'class' => 'no-sort',
                                                            'style' => 'width: 66px;'
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
                                                            'show' => function ($url, $model, $key) {
                                                                return Html::a(
                                                                    '<span class="fa fa-link"></span></a>',
                                                                    Url::toRoute(
                                                                        ['download', 'oid' => $model->oid],
                                                                        true
                                                                    ),
                                                                    [
                                                                        'style' => 'cursor:pointer;color: #FF0000;',
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
                                <div style="display:none;" id="max-file-warning"
                                     class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                    </button>
                                    <h4><i class="icon fa fa-ban"></i> <?= Translate::_(
                                            'business',
                                            'The file is too big!'
                                        ) ?></h4>
                                    <?= Translate::_(
                                        'business',
                                        'Max file size: {uploadLimit}MB',
                                        compact('uploadLimit')
                                    ) ?>
                                </div>
                                <div class="box-footer">
                                    <div id="upload_box" style="text-align: center;">
                                        <div class="upload-btn-wrapper">
                                            <button id="button-upload" class="btn-upload"><?= Translate::_(
                                                    'business',
                                                    'click or drop to upload file'
                                                ) ?></button>
                                            <div id="max-file-size"><?= Translate::_(
                                                    'business',
                                                    'Max file size: {uploadLimit}MB',
                                                    compact('uploadLimit')
                                                ) ?></div>
                                            <form id="upload-form" method="POST" enctype="multipart/form-data">
                                                <div class="hidden-inputs"></div>
                                                <input style="width: 0px;" type="file" name="file"/>
                                            </form>
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

    <div class="modal fade" id="storage-options-modal" style="z-index: 9999999;" tabindex="-1" role="dialog"
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
                <form method="POST" action="<?= Url::toRoute(['settings'], true) ?>">
                    <div class="modal-body">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                               value="<?= Yii::$app->request->csrfToken; ?>"/>
                        <table>
                            <tr>
                                <td><?= Translate::_('business', 'People can upload files') ?>:</td>
                                <td style="padding-left: 20px;">
                                    <input type="checkbox"
                                        <?= $options['peopleUpload'] ? 'checked' : '' ?>
                                           name="options[people_upload]"/>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal"><?= Translate::_(
                                'business',
                                'Close'
                            ) ?></button>
                        <button type="submit" class="btn btn-primary"><?= Translate::_('business', 'Save') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="idbStorageShareChange" style="z-index: 9999999;" tabindex="-1" role="dialog"
         aria-labelledby="idbDataTableSettingsLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="idbDataTableSettingsLabel">
                        <?= Translate::_(
                            'business',
                            'Share File'
                        ) ?>:
                    </h3>
                    <button type="button" style="top: 15px; right: 15px; position:absolute;" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $form = ActiveForm::begin(
                    [
                        'action' => Url::toRoute(['share'], true),
                        'options' => ['method' => 'post', 'id' => 'shareTemplate']
                    ]
                ); ?>
                <div class="modal-body">
                    <label>
                        <input type="checkbox" checked id="share-everyone" name="wholeVault"/>&nbsp;
                        <?= Translate::_(
                            'business',
                            'Share to everyone'
                        ) ?>
                    </label>

                    <div id="select-people" style="display: none;">
                        <?= $form->field($model, 'people_user')->dropDownList(
                            [],
                            ['multiple' => 'multiple', 'name' => 'people_users[]', 'id' => 'change-share-input']
                        ) ?>
                    </div>
                    <input type="hidden" name="shareName"/>
                    <input type="hidden" name="shareChecksum"/>
                    <input type="hidden" name="shareSize"/>
                    <input type="hidden" name="shareKey"/>
                    <input type="hidden" name="shareOid"/>
                    <?php if (!empty($uuid)): ?>
                        <input type="hidden" name="shareUuid" value="<?= $uuid ?>"/>
                    <?php endif; ?>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal"><?= Translate::_(
                            'business',
                            'Close'
                        ) ?></button>
                    <button type="submit" class="btn btn-primary"><?= Translate::_('business', 'Save') ?></button>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="idbStorageRequestUpload" style="z-index: 9999999;" tabindex="-1" role="dialog"
         aria-labelledby="idbDataTableSettingsLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="idbDataTableSettingsLabel">
                        <?= Translate::_(
                            'business',
                            'Request file upload'
                        ) ?>:
                    </h3>
                    <button type="button" style="top: 15px; right: 15px; position:absolute;" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $form = ActiveForm::begin(
                    [
                        'action' => Url::toRoute(['request-file-upload'], true),
                        'options' => ['method' => 'post', 'id' => 'requestUpload']
                    ]
                ); ?>
                <div class="modal-body">
                    <?= $form->field($modelRequest, 'people_user')->dropDownList(
                        [],
                        ['multiple' => 'multiple', 'id' => 'request-file-input']
                    ) ?>
                    <?= $form->field($modelRequest, 'name')->textInput() ?>

                    <?= $form->field($modelRequest, 'message')->textArea(['rows' => '6']) ?>
                    <?= $form->field($modelRequest, 'upload_limit')->input('number') ?>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal"><?= Translate::_(
                            'business',
                            'Close'
                        ) ?></button>
                    <button type="submit" class="btn btn-primary"><?= Translate::_('business', 'Save') ?></button>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>

    <div class="progress-container">
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75"
                 aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
        </div>
    </div>

<?= BusinessConfig::jsOptions(
    [
        'checkUrl' => $redirectUrl,
        'shared' => '[]',
        'summariesUrl' => Url::toRoute('summaries'),
        'getUsersUrl' => Url::toRoute(['get-users'], true),
        'initObjectUrl' => Url::toRoute('init-object', true),
        'uuid' => $uuid,
        'uploadLimit' => File::convertToBytes($uploadLimit . 'MB'),
        'showInactiveUrl' => Url::toRoute('show-inactive', true),
        'showAllUserFilesUrl' => Url::toRoute('show-all-user-files', true)
    ]
) ?>