<?php
# * ********************************************************************* *
# *                                                                       *
# *   Business Portal                                                     *
# *   This file is part of business. This project may be found at:        *
# *   https://github.com/IdentityBank/Php_business.                       *
# *                                                                       *
# *   Copyright (C) 2020 by Identity Bank. All Rights Reserved.           *
# *   https://www.identitybank.eu - You belong to you                     *
# *                                                                       *
# *   This program is free software: you can redistribute it and/or       *
# *   modify it under the terms of the GNU Affero General Public          *
# *   License as published by the Free Software Foundation, either        *
# *   version 3 of the License, or (at your option) any later version.    *
# *                                                                       *
# *   This program is distributed in the hope that it will be useful,     *
# *   but WITHOUT ANY WARRANTY; without even the implied warranty of      *
# *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the        *
# *   GNU Affero General Public License for more details.                 *
# *                                                                       *
# *   You should have received a copy of the GNU Affero General Public    *
# *   License along with this program. If not, see                        *
# *   https://www.gnu.org/licenses/.                                      *
# *                                                                       *
# * ********************************************************************* *

################################################################################
# Namespace                                                                    #
################################################################################

namespace app\assets;

################################################################################
# Use(s)                                                                       #
################################################################################

use app\helpers\Translate;
use Yii;
use yii\helpers\Html;
use yii\web\AssetBundle;
use yii\web\View;

################################################################################
# Class(es)                                                                    #
################################################################################

class WizardAsset extends AssetBundle
{

    public $sourcePath = '@app/views/assets';
    public $cssOptions = ['position' => View::POS_END];
    public $jsOptions = ['position' => View::POS_END];
    public $css =
        [
            'css/bootstrap-directional-buttons.css',
            'css/wizard.css',
        ];
    public $depends =
        [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ];

    public function generateWizard($left = null, $center = null, $right = null, $active = 1, $error = false)
    {
        $leftIcon = ((empty($left['Icon'])) ? 'glyphicon-ok' : $left['Icon']);
        $leftTitle = ((empty($left['Title'])) ? '' : $left['Title']);
        $centerIcon = ((empty($center['Icon'])) ? 'glyphicon-ok' : $center['Icon']);
        $centerTitle = ((empty($center['Title'])) ? '' : $center['Title']);
        $rightIcon = ((empty($right['Icon'])) ? 'glyphicon-ok' : $right['Icon']);
        $rightTitle = ((empty($right['Title'])) ? '' : $right['Title']);

        return '
            <div class="wizard">
                <div class="wizard-inner">
                    <div class="connecting-line"></div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="' . (($active == 1) ? (($error) ? 'active error' : 'active')
                : (($active > 1) ? 'disabled done' : 'disabled')) . '">
                            <a title="' . $leftTitle . '">
                                <span class="round-tab"><i class="glyphicon ' . $leftIcon . '"></i></span>
                            </a>
                        </li>
                        <li role="presentation" class="' . (($active == 2) ? (($error) ? 'active error' : 'active')
                : (($active > 2) ? 'disabled done' : 'disabled')) . '">
                            <a title="' . $centerTitle . '">
                                <span class="round-tab"><i class="glyphicon ' . $centerIcon . '"></i></span>
                            </a>
                        </li>
                        <li role="presentation" class="' . (($active == 3) ? (($error) ? 'active error' : 'active')
                : (($active > 3) ? 'disabled done' : 'disabled')) . '">
                            <a title="' . $rightTitle . '">
                                <span class="round-tab"><i class="glyphicon ' . $rightIcon . '"></i></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        ';
    }

    public function generateWizardActions($next, $back = null)
    {
        $backText = $back['Text'] ?? '';
        $backAction = $back['Action'] ?? '';
        $backTitle = $back['Help'] ?? '';
        $backId = ((empty($back['Id'])) ? 'back' : $back['Id']);
        $backClass = ' ' . ($back['Class'] ?? '');
        $backForceClass = $back['ForceClass'] ?? null;
        $backStyle = ' ' . ($back['Style'] ?? '');
        $backButtonAction = $back['backButtonAction'] ?? false;

        $nextText = $next['Text'] ?? '';
        $nextAction = $next['Action'] ?? '';
        $nextTitle = $next['Help'] ?? '';
        $nextSubmit = (strtolower(is_string($nextAction) ? $nextAction : null) === 'submit');
        $nextId = ((empty($next['Id'])) ? 'next' : $next['Id']);
        $nextClass = ' ' . ($next['Class'] ?? '');
        $nextForceClass = $next['ForceClass'] ?? null;
        $nextStyle = ' ' . ($next['Style'] ?? '');

        if (empty($backText)) {
            $backButton = '';
        } elseif ($backButtonAction) {
            $backButton = Html::a(
                $backText,
                $backAction,
                [
                    'class' => $backForceClass ??
                        'btn btn-lg btn-danger btn-arrow-left mid-margin-right wizard-prev pull-left' . $backClass,
                    'style' => $backStyle,
                    'name' => 'back-button',
                    'id' => $backId,
                    'title' => $backTitle
                ]
            );
        } else {
            $backButton = Yii::$app->controller->renderPartial(
                '@app/themes/idb/views/site/_modalWindow',
                [
                    'modal' => [
                        'name' => 'cancelFormActionModal',
                        'header' => Translate::_('business', 'Stop account signup'),
                        'body' => Translate::_(
                            'business',
                            'Your account signup will be stopped. All information you have entered so far will be deleted and wiped. You can restart the account signup again at any time.'
                        ),
                        'question' => Translate::_(
                            'business',
                            'If this is not your intention, please click on \'Continue account signup\'.'
                        ),
                        'button' => [
                            'label' => $backText,
                            'id' => $backId,
                            'class' => $backForceClass ??
                                'btn btn-lg btn-danger btn-arrow-left mid-margin-right wizard-prev pull-left'
                                . $backClass,
                            'style' => $backStyle,
                        ],
                        'leftButton' => [
                            'label' => Translate::_('business', 'Stop account signup'),
                            'id' => $backId . '-leftButton',
                            'action' => $backAction
                        ],
                        'rightButton' => [
                            'label' => Translate::_('business', 'Continue account signup'),
                            'id' => $backId . '-rightButton',
                            'style' => 'btn-success',
                            'action' => 'data-dismiss'
                        ],
                    ]
                ]
            );
        }
        if (empty($nextText)) {
            $nextButton = '';
        } elseif ($nextSubmit) {
            $nextButton = Html::submitButton(
                $nextText,
                [
                    'class' => $nextForceClass ??
                        'btn btn-lg btn-success btn-arrow-right mid-margin-right wizard-next pull-right' . $nextClass,
                    'style' => $nextStyle,
                    'name' => 'next-button',
                    'id' => $nextId,
                    'title' => $nextTitle
                ]
            );
        } else {
            $nextButton = Html::a(
                $nextText,
                $nextAction,
                [
                    'class' => $nextForceClass ??
                        'btn btn-lg btn-success btn-arrow-right mid-margin-right wizard-next pull-right' . $nextClass,
                    'style' => $nextStyle,
                    'name' => 'next-button',
                    'id' => $nextId,
                    'title' => $nextTitle
                ]
            );
        }

        return '
            <div class="wizard">
                <div class="wizard-inner">
                    <div class="connecting-line"></div>
                    <ul class="nav nav-tabs" role="tablist"></ul>
                    <div class="field-block button-height wizard-controls clearfix">
                        <div class="wizard-spacer" style="height:20px"></div>
                        ' . $backButton . '
                        ' . $nextButton . '
                    </div>
                </div>
            </div>
        ';
    }
}

################################################################################
#                                End of file                                   #
################################################################################
