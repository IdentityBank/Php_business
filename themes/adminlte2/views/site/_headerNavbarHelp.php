<?php

use app\helpers\BusinessConfig;
use app\helpers\Translate;
use yii\helpers\Html;

$contextHelpUrl = $this->params['contextHelpUrl'] ??
    Translate::_('business', 'https://www.identitybank.eu/help/business');

?>

<?php if (BusinessConfig::get()->isYii2BusinessHelpEnabled()) : ?>
    <li class="dropdown usehelp help-menu">
        <?=
        Html::a(
            Html::tag(
                'i',
                null,
                ['class' => 'fa fa-info-circle text-red', 'style' => "background-color: white; padding: 2px;"]
            ) .
            '&nbsp;' .
            Translate::_('business', 'Help'),
            $contextHelpUrl,
            ['class' => 'dropdown-toggle', 'target' => '_blank']
        )
        ?>
    </li>
<?php endif; ?>
