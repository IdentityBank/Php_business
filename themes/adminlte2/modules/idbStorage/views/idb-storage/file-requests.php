<?php

use app\assets\FrontAsset;
use app\assets\SelectAsset;
use app\helpers\Translate;
use idbyii2\helpers\File;
use idbyii2\widgets\FlashMessage;
use yii\grid\GridView;
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
                                    'style' => 'margin-left:-15px; margin-bottom: 20px;',
                                    'class' => 'btn btn-back'
                                ],
                                'leftButton' => [
                                    'label' => Translate::_('business', 'Back'),
                                    'action' => Url::previous('idbstorage'),
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
                    <div class="row">
                        <?= Html::a(
                            '<i class="fa fa-plus-circle"></i> ' . Translate::_('business', 'Add request for file'),
                            null,
                            ['class' => 'btn btn-app', 'id' => 'upload-request-button' , 'style' => 'margin-left: 0;']
                        ) ?>
                        <div class="table-responsive">
                            <div style="text-align: right;">
                                <?= Translate::_('business', 'Show')?>:
                                <select id="show-all-requests">
                                    <option value="all" <?= $showRequests === 'all'? 'selected': '' ?>><?= Translate::_('business', 'All requests')?></option>
                                    <option value="active" <?= $showRequests === 'active'? 'selected': '' ?>><?= Translate::_('business', 'Active requests')?></option>
                                    <option value="inactive" <?= $showRequests === 'inactive'? 'selected': '' ?>><?= Translate::_('business', 'Inactive requests')?></option>
                                </select>
                            </div>

                        <?= GridView::widget(
                            [
                                'dataProvider' => $provider,
                                'columns' => [
                                    'name',
                                    'message',
                                    [
                                        'header' => Translate::_('business', 'User'),
                                        'value' => function ($model) use ($users) {
                                            foreach($users as $user) {
                                                if($user['id'] === $model['pid']) {
                                                    return $user['text'];
                                                }
                                            }
                                            return Translate::_('business', 'undefined');
                                        }
                                    ],
                                    [
                                        'header' => Translate::_('business', 'Status'),
                                        'value' => function ($model) {
                                            return $model['type'] === 'complete'?Translate::_('business', 'Inactive'): Translate::_('business', 'Active');
                                        }
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{show}{delete}',
                                        'header' => '',
                                        'contentOptions' => ['class' => 'click-disabled'],
                                        'headerOptions' => [
                                            'id' => 'idb_action',
                                            'class' => 'no-sort',
                                            'style' => 'width: 48px;'
                                        ],
                                        'visibleButtons' => [
                                            'delete' => true,
                                            'show' => true,
                                        ],
                                        'buttons' => [
                                            'show' => function ($url, $model, $key) {
                                                return Html::a(
                                                    '<span class="glyphicon glyphicon-pencil"></span></a>',
                                                    ['file-request', 'id' => $model['id']],
                                                    [
                                                        'class' => 'unstyled-button',
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
                                                                    base64_encode($model['id'])
                                                                ),
                                                            'header' => \idbyii2\helpers\Translate::_(
                                                                'business',
                                                                'Delete IDB storage file request'
                                                            ),
                                                            'body' => Translate::_(
                                                                'business',
                                                                'That action will permanently remove request. Are you sure you want continue that action?'
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
                                                                        'delete-request',
                                                                        'id' => $model['id']
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


<?= BusinessConfig::jsOptions(
    [
        'checkUrl' => $redirectUrl,
        'shared' => '[]',
        'summariesUrl' => Url::toRoute('summaries'),
        'getUsersUrl' => Url::toRoute(['get-users'], true),
        'initObjectUrl' => Url::toRoute('init-object', true),
        'uuid' => null,
        'uploadLimit' => File::convertToBytes($uploadLimit . 'MB'),
        'showInactiveUrl' => Url::toRoute('show-inactive', true)
    ]
) ?>
