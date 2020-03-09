<?php

use app\assets\AdminLte2AppAsset;
use app\assets\SelectAsset;
use app\helpers\Translate;
use app\themes\adminlte2\views\yii\widgets\breadcrumbs\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

AdminLte2AppAsset::register($this);

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
            <?= Html::encode(Translate::_('business', 'View actions')) ?>
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
                        <form action="<?= Url::toRoute(['deleteaction']) ?>">
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
                            </div>
                            <span>
                                <h1><?= $name ?></h1>
                            </span>
                            <input type="hidden" name="name" value="<?= $name ?>">
                            <select class="select-search" name="action" style="width: 200px">
                                <?php

                                foreach ($actions as $action) {
                                    echo "<option value=\"$action\">$action</option>";
                                }

                                ?>
                            </select><br><br>
                            <button type="submit" class="btn btn-app-trash" id="submit" style="float:left;">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
