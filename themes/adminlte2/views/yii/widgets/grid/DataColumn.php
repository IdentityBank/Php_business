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

namespace app\themes\adminlte2\views\yii\widgets\grid;

################################################################################
# Use(s)                                                                       #
################################################################################

use yii\helpers\Html;

################################################################################
# Class(es)                                                                    #
################################################################################

class DataColumn extends \yii\grid\DataColumn
{

    public function renderHeaderCell()
    {
        $provider = $this->grid->dataProvider;
        if (
            ($this->attribute !== null)
            && $this->enableSorting
            && (($sort = $provider->getSort()) !== false)
            && $sort->hasAttribute($this->attribute)
        ) {
            if (($direction = $sort->getAttributeOrder($this->attribute)) !== null) {
                Html::addCssClass($this->headerOptions, 'sorting_' . ($direction === SORT_DESC ? 'desc' : 'asc'));
            } else {
                Html::addCssClass($this->headerOptions, 'sorting');
            }
        }

        return Html::tag('th', $this->renderHeaderCellContent(), $this->headerOptions);
    }
}

################################################################################
#                                End of file                                   #
################################################################################
