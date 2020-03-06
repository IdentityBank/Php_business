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

class ImportWizardAsset extends AssetBundle
{

    public $sourcePath = '@app/views/assets';
    public $cssOptions = ['position' => View::POS_END];
    public $jsOptions = ['position' => View::POS_END];
    public $css =
        [
            'css/import-wizard.css',
        ];
    public $depends =
        [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ];

    public function generateWizard(
        $zero = null,
        $first = null,
        $second = null,
        $active = 0,
        $error = false
    ) {
        $zeroIcon = ((empty($zero['Icon'])) ? 'glyphicon-ok' : $zero['Icon']);
        $zeroTitle = ((empty($zero['Title'])) ? '' : $zero['Title']);
        $firstIcon = ((empty($first['Icon'])) ? 'glyphicon-ok' : $first['Icon']);
        $firstTitle = ((empty($first['Title'])) ? '' : $first['Title']);
        $secondIcon = ((empty($second['Icon'])) ? 'glyphicon-ok' : $second['Icon']);
        $secondTitle = ((empty($second['Title'])) ? '' : $second['Title']);

        return '
            <div class="import-wizard">
                <div class="import-wizard-inner">
                    <div class="connecting-line"></div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="' . (($active == 0) ? (($error) ? 'active error' : 'active')
                : (($active > 0) ? 'disabled done' : 'disabled')) . '">
                            <a title="' . $zeroTitle . '">
                                <span class="round-tab"><i class="glyphicon ' . $zeroIcon . '"></i></span>
                            </a>
                        </li>
                        <li role="presentation" class="' . (($active == 1) ? (($error) ? 'active error' : 'active')
                : (($active > 1) ? 'disabled done' : 'disabled')) . '">
                            <a title="' . $firstTitle . '">
                                <span class="round-tab"><i class="glyphicon ' . $firstIcon . '"></i></span>
                            </a>
                        </li>
                        <li role="presentation" class="' . (($active == 2) ? (($error) ? 'active error' : 'active')
                : (($active > 2) ? 'disabled done' : 'disabled')) . '">
                            <a title="' . $secondTitle . '">
                                <span class="round-tab"><i class="glyphicon ' . $secondIcon . '"></i></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        ';
    }

    public function generateWizardActions($next, $back = null)
    {
        $backText = ((empty($back['Text'])) ? '' : $back['Text']);
        $backAction = ((empty($back['Action'])) ? '' : $back['Action']);
        $backTitle = ((empty($back['Help'])) ? '' : $back['Help']);

        $nextText = ((empty($next['Text'])) ? '' : $next['Text']);
        $nextAction = ((empty($next['Action'])) ? '' : $next['Action']);
        $nextTitle = ((empty($next['Help'])) ? '' : $next['Help']);
        $nextSubmit = (strtolower(is_string($nextAction) ? $nextAction : null) === 'submit');
        $method = 'get';

        if (empty($backText)) {
            $backButton = '';
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
                            'class' => 'btn btn-lg btn-danger btn-arrow-left mid-margin-right wizard-prev pull-left'
                        ],
                        'leftButton' => [
                            'label' => Translate::_('business', 'Stop account signup'),
                            'action' => $backAction
                        ],
                        'rightButton' => [
                            'label' => Translate::_('business', 'Continue account signup'),
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
                    'class' => 'btn btn-lg btn-success btn-arrow-right mid-margin-right wizard-next pull-right',
                    'name' => 'next-button',
                    'title' => $nextTitle
                ]
            );
        } else {
            $nextButton = Html::a(
                $nextText,
                $nextAction,
                [
                    'class' => 'btn btn-lg btn-success btn-arrow-right mid-margin-right wizard-next pull-right',
                    'name' => 'next-button',
                    'title' => $nextTitle
                ]
            );
        }

        return '
            <div class="import-wizard">
                <div class="import-wizard-inner">
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
