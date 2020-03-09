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
# Location(s)                                                                  #
################################################################################

define('YII_VERSION', 'idb');
$dirname = dirname(__FILE__);
$dirConfig = $dirname . '/../config';
define('YII_DIR_CONFIG', $dirConfig);
$yii = '/usr/local/share/p57b/php/3rdparty/yii2/yii-advanced-app-' . YII_VERSION;

################################################################################
# Include(s)                                                                   #
################################################################################

require_once(YII_DIR_CONFIG . '/config.inc');
require_once('idbyii2/helpers/Localization.php');
require_once('idbyii2/helpers/IdbSecurity.php');

################################################################################
# Use(s)                                                                       #
################################################################################

use idbyii2\helpers\Localization;
use xmz\simplelog\SNLog as Log;
use yii\web\Application as YiiApplication;

################################################################################
# Yii Application Config                                                       #
################################################################################

// DEBUG mode
if (BusinessConfig::get()->isDebug()) {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', false);
    defined('YII_ENV') or define('YII_ENV', 'prod');
}

// Theme
define('APP_THEME', BusinessConfig::get()->getTheme());

// Localization
if (BusinessConfig::get()->useBrowserLocalization()) {
    $language = Localization::getBrowserLocalization(BusinessConfig::get()->getDefaultLocalizationLanguage());
} else {
    $language = BusinessConfig::get()->getLocalizationLanguage();
}
define('APP_LANGUAGE', $language);

//Error handler
if (defined('YII_ENABLE_ERROR_HANDLER')) {
    $isYiiErrorHandlerEnabled = BusinessConfig::get()->isYiiErrorHandlerEnabled();
    if (YII_ENABLE_ERROR_HANDLER != $isYiiErrorHandlerEnabled) {
        runkit_constant_redefine(YII_ENABLE_ERROR_HANDLER, $isYiiErrorHandlerEnabled);
    }
} else {
    define('YII_ENABLE_ERROR_HANDLER', BusinessConfig::get()->isYiiErrorHandlerEnabled());
}

require($yii . '/vendor/autoload.php');
require($yii . '/vendor/yiisoft/yii2/Yii.php');
$config = require($dirConfig . '/web.php');

if (BusinessConfig::get()->isDebug()) {
    $pid = getmypid();
    Log::debug(
        BusinessConfig::get()->getLogName(),
        "$pid - ${_SERVER['REQUEST_URI']} - " .
        "[Debug|" . BusinessConfig::get()->isDebug() . "]." .
        "[YII_ENABLE_ERROR_HANDLER|" . BusinessConfig::get()->isYiiErrorHandlerEnabled() . "]." .
        "[Theme|" . BusinessConfig::get()->getTheme() . "]." .
        "[Language|$language]"
    );
}

################################################################################
# Application settings                                                         #
################################################################################

idbyii2\helpers\IdbSecurity::$magic_shift_value = BusinessConfig::get()->getYii2IdbSecurityMagicShift();

################################################################################
# Start Yii Application                                                        #
################################################################################

(new YiiApplication($config))->run();

################################################################################
#                                End of file                                   #
################################################################################
