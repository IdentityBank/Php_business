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

namespace app\helpers;

################################################################################
# Use(s)                                                                      #
################################################################################

use idbyii2\helpers\DataHTML;
use idbyii2\helpers\Metadata;
use yii\base\DynamicModel;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Class ConfigHelper
 *
 * @package app\helpers
 */
class ConfigHelper
{

    /**
     * @param $metadata
     *
     * @return array
     */
    public static function prepareMapping($metadata)
    {
        $model = new DynamicModel(
            ['email_no', 'mobile_no', 'name_no', 'surname_no', 'send_email', 'valid_both', 'phone_code']
        );
        $model->addRule(['email_no', 'mobile_no'], 'required');

        if (Metadata::hasPeopleAccessMap($metadata)) {
            $model['email_no'] = $metadata['PeopleAccessMap']['email_no'];
            $model['mobile_no'] = $metadata['PeopleAccessMap']['mobile_no'];
            $model['surname_no'] = $metadata['PeopleAccessMap']['surname_no'];
            $model['name_no'] = $metadata['PeopleAccessMap']['name_no'];
        }


        $arrayMetadata = ['' => ''];
        if (!empty($metadata['database'])) {
            foreach ($metadata['database'] as $db) {
                $arrayMetadata[$db['uuid']] = DataHTML::getDisplayName($db['uuid'], $metadata);
            }
        }

        return ['arrayMetadata' => $arrayMetadata, 'model' => $model];
    }
}

################################################################################
#                                End of file                                   #
################################################################################
