<?php

use app\assets\SelectAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

$assetSelectBundle = SelectAsset::register($this);
$this->registerJs(
    <<< EOT_JS_CODE

  $(document).ready(function() {
    $('.select-search').select2();
    });

EOT_JS_CODE
);

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Add Action')) ?>
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
                        <form action="<?= Url::toRoute(['addedaction'], true) ?>">
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
                                <?= Html::submitButton(
                                    Translate::_('business', 'Add'),
                                    ['class' => 'btn btn-primary']
                                ) ?>
                            </div>
                            <?= Html::encode(Translate::_('business', 'Role')) ?>: <input type="text" name="role_name"
                                                                                          value="<?= $name ?>"
                                                                                          readonly="readonly"><br><br>
                            <?= Html::encode(Translate::_('business', 'Task')) ?>:
                            <select class="select-search" name="task" style="width: 200px">
                                <?php

                                foreach ($tasks as $task) {
                                    echo "<option value=\"$task\">$task</option>";
                                }

                                ?>
                            </select><br><br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
