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

namespace app\themes\adminlte2\views\yii\widgets\form;

################################################################################
# Use(s)                                                                       #
################################################################################

################################################################################
# Class(es)                                                                    #
################################################################################

class ActiveField extends \yii\bootstrap\ActiveField
{

    public $template = '
        {label}
        {hint}
        {input}
        {error}
    ';
    public $checkboxTemplate = '
        {hint}
        {input}&ensp;{beginLabel}{labelTitle}{endLabel}
        {error}';

    protected function createLayoutConfig($instanceConfig)
    {
        $config = parent::createLayoutConfig($instanceConfig);
        $config['hintOptions'] = ['tag' => 'div', 'class' => 'alert alert-wizard'];

        return $config;
    }
}

################################################################################
#                                End of file                                   #
################################################################################