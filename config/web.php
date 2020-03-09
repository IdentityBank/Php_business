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
# Use(s)                                                                       #
################################################################################

use idbyii2\helpers\IdbYii2Config;
use idbyii2\helpers\Localization;

################################################################################
# Load params                                                                  #
################################################################################

$params = require(__DIR__ . '/params.php');

################################################################################
# Web Config                                                                   #
################################################################################

$config = [
    'id' => 'IDB - Business',
    'name' => 'Identity Bank - Business',
    'version' => '1.0.1',
    'vendorPath' => $yii . '/vendor',
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'language' => BusinessConfig::get()->getWebLanguage(),
    'sourceLanguage' => 'en-GB',
    'aliases' => [
        '@idbyii2' => '/usr/local/share/p57b/php/idbyii2',
    ],
    'modules' => [
        'api' => [
            'class' => 'app\modules\api\ApiModule',
        ],
        'idbuser' => [
            'class' => 'app\modules\idbuser\IdbUserModule',
            'controllerNamespace' => 'app\modules\idbuser\controllers',
            'configUserAccount' => BusinessConfig::get()->getYii2BusinessModulesIdbUserConfigUserAccount(),
            'configUserData' => BusinessConfig::get()->getYii2BusinessModulesIdbUserConfigUserData(),
        ],
        'idbverification' => [
            'class' => 'app\modules\idbverification\IdbVerificationModule',
            'controllerNamespace' => 'app\modules\idbverification\controllers',
        ],
        'gdpr' => [
            'class' => 'app\modules\gdpr\GdprModule',
            'controllerNamespace' => 'app\modules\gdpr\controllers',
        ],
        'idb-storage' => [
            'class' => 'app\modules\idbStorage\IdbStorageModule',
            'controllerNamespace' => 'app\modules\idbStorage\controllers',
            'configIdbStorage' => IdbYii2Config::get()->getIdbStorageModuleConfig()
        ],
        'idbdev' => [
            'class' => 'app\modules\idbdev\IdbDevModule',
            'controllerNamespace' => 'app\modules\idbdev\controllers',
        ],
        'tools' => [
            'class' => 'app\modules\tools\ToolsModule',
        ],
        'applications' => [
            'class' => 'app\modules\applications\ApplicationsModule',
        ],
        'signup' => [
            'class' => 'app\modules\signup\SignUpModule',
            'configSignUp' => BusinessConfig::get()->getYii2BusinessModulesSignUpConfig(),
        ],
        'paymentcheck' => [
            'class' => 'app\modules\paymentcheck\PaymentCheckModule',
        ],
        'passwordrecovery' => [
            'class' => 'app\modules\passwordrecovery\PasswordRecoveryModule',
            'configPasswordRecovery' => BusinessConfig::get()->getYii2BusinessModulesSignUpConfig(),
        ],
        'mfarecovery' => [
            'class' => 'app\modules\mfarecovery\MfaRecoveryModule',
        ],
        'rbac' => [
            'class' => 'app\modules\rbac\RbacModule',
        ],
        'notifications' => [
            'class' => 'app\modules\notifications\NotificationsModule',
            'configNotifications' => BusinessConfig::get()->getYii2BusinessModulesNotificationsConfig(),
        ],
        'idbdata' => [
            'class' => 'app\modules\idbdata\IdbDataModule',
            'controllerNamespace' => 'app\modules\idbdata\controllers',
            'configAuditLog' => BusinessConfig::get()->getYii2BusinessModulesAuditLogConfig(),
        ],
        'logs' => [
            'class' => 'app\modules\logs\LogsModule',
            'controllerNamespace' => 'app\modules\logs\controllers',
        ],
        'btpmessages' => [
            'class' => 'app\modules\btpmessages\BtpmessagesModule'
        ],
        'accessmanager' => [
            'class' => 'app\modules\accessmanager\AccessmanagerModule',
            'controllerNamespace' => 'app\modules\accessmanager\controllers',
        ],
        'configuration' => [
            'class' => 'app\modules\configuration\ConfigurationModule',
            'controllerNamespace' => 'app\modules\configuration\controllers',
        ],
    ],
    'components' => [
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'forceCopy' => BusinessConfig::get()->isAssetManagerForceCopy(),
            'appendTimestamp' => true,
        ],
        'audit' => [
            'class' => 'idbyii2\audit\AuditComponent',
            'auditConfig' => [
                'class' => 'idbyii2\audit\AuditConfig',
                'enabled' => BusinessConfig::get()->isAuditEnabled(),
            ],
            'auditFile' => [
                'class' => 'idbyii2\audit\FileAudit',
                'auditPath' => BusinessConfig::get()->getAuditPath(),
                'auditFile' => BusinessConfig::get()->getAuditFileName(),
            ],
            'auditMessage' => [
                'class' => 'idbyii2\audit\AuditMessage',
                'liveServerLog' => !BusinessConfig::get()->isDebug(),
                'separator' => BusinessConfig::get()->getAuditMessageSeparator(),
                'encrypted' => BusinessConfig::get()->isAuditEncrypted(),
                'password' => BusinessConfig::get()->getAuditMessagePassword(),
            ],
        ],
        'request' => [
            'cookieValidationKey' => BusinessConfig::get()->getYii2BusinessCookieValidationKey(),
            'csrfCookie' => [
                'httpOnly' => true,
                'secure' => true,
//                'sameSite' => (PHP_VERSION_ID >= 70300 ? yii\web\Cookie::SAME_SITE_LAX : null),
            ],
            'enableCookieValidation' => true,
            'enableCsrfCookie' => true,
            'enableCsrfValidation' => true,
        ],
        'idbpeopleportalapi' =>
            [
                'class' => 'idbyii2\components\PortalApi',
                'configuration' => BusinessConfig::get()->getPeoplePortalApiConfiguration(),
            ],
        'idbankclient' => [
            'class' => 'idbyii2\models\idb\IdbBankClient',
            'service' => 'business',
            'host' => BusinessConfig::get()->getIdBankHost(),
            'port' => BusinessConfig::get()->getIdBankPort(),
            'configuration' => BusinessConfig::get()->getIdBankConfiguration()
        ],
        'idbankclientbusiness' => [
            'class' => 'idbyii2\models\idb\IdbBankClientBusiness',
            'service' => 'business',
            'host' => BusinessConfig::get()->getIdBankHost(),
            'port' => BusinessConfig::get()->getIdBankPort(),
            'configuration' => BusinessConfig::get()->getIdBankConfiguration()
        ],
        'idbillclient' => [
            'class' => 'idbyii2\models\idb\BusinessIdbBillingClient',
            'billingName' => BusinessConfig::get()->getIdBillingName(),
            'host' => BusinessConfig::get()->getIdBillHost(),
            'port' => BusinessConfig::get()->getIdBillPort(),
            'configuration' => BusinessConfig::get()->getIdBillConfiguration()
        ],
        'idbstorageclient' => [
                'class' => 'idbyii2\models\idb\IdbStorageClient',
                'storageName' => IdbYii2Config::get()->getIdbStorageName(),
                'host' => IdbYii2Config::get()->getIdbStorageHost(),
                'port' => IdbYii2Config::get()->getIdbStoragePort(),
                'configuration' => IdbYii2Config::get()->getIdbStorageConfiguration()
            ],
        'idbmessenger' => [
            'class' => 'idbyii2\components\Messenger',
            'configuration' => BusinessConfig::get()->getMessengerConfiguration(),
        ],
        'idbrabbitmq' => [
            'class' => 'idbyii2\components\IdbRabbitMq',
            'host' => BusinessConfig::get()->getIdbRabbitMqHost(),
            'port' => BusinessConfig::get()->getIdbRabbitMqPort(),
            'user' => BusinessConfig::get()->getIdbRabbitMqUser(),
            'password' => BusinessConfig::get()->getIdbRabbitMqPassword()
        ],
        'signUpCaptcha' => [
            'name' => 'IDB signup captcha',
            'class' => 'idbyii2\helpers\ReCaptcha',
            'siteKey' => BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaSiteKey(),
            'secret' => BusinessConfig::get()->getYii2BusinessSignUpFormCaptchaSecret(),
        ],
        'db' => require(__DIR__ . '/db_p57b_business.php'), //RBAC
        'p57b_business' => require(__DIR__ . '/db_p57b_business.php'),
        'p57b_business_search' => require(__DIR__ . '/db_p57b_business.php'),
        'p57b_business_log' => require(__DIR__ . '/db_p57b_business.php'),
        'user' => [
            'identityClass' => 'idbyii2\models\identity\IdbBusinessUser',
            'enableAutoLogin' => BusinessConfig::get()->getYii2BusinessEnableAutoLogin(),
            'identityCookie' => [
                'name' => '_identity-p57b',
                'httpOnly' => true
            ],
            'absoluteAuthTimeout' => BusinessConfig::get()->getYii2BusinessAbsoluteAuthTimeout(),
            'authTimeout' => BusinessConfig::get()->getYii2BusinessAuthTimeout(),
            'loginUrl' => BusinessConfig::get()->getLoginUrl(),
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['idb_business'],
            'cache' => YII_DEBUG ? null : 'cache',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'rules' => [
                'defaultRoute' => '/site/index',
                'login' => '/site/login',
                'mfa' => '/site/mfa',
                'logout' => '/site/logout',
                'idb-login' => '/site/idb-login',
                'idb-api' => '/site/idb-api',
                'idb-menu' => '/site/idb-menu',
                'mandatory-actions' => '/site/mandatory-actions',
                'profile' => '/site/profile',
                'signup' => '/signup/wizard',
                'paymentcheck' => '/paymentcheck/check',
                'signup/<action:[\w-]+>' => '/signup/wizard/<action>',
                'passwordrecovery' => '/passwordrecovery/wizard',
                'passwordrecovery/<action:[\w-]+>' => '/passwordrecovery/wizard/<action>',
                'mfarecovery' => '/mfarecovery/wizard',
                'mfarecovery/<action:[\w-]+>' => '/mfarecovery/wizard/<action>',
                'idb-storage' => '/idb-storage/idb-storage',
                'idb-storage/<action:[\w-]+>' => '/idb-storage/idb-storage/<action>',
                'gdpr/<action:[\w-]+>' => '/gdpr/default/<action>',
                'rbac/<action>' => '/rbac/rbac/<action>',
                'notifications/<action>' => '/notifications/notifications/<action>',
                'btpmessages/<action>' => '/btpmessages/btpmessages/<action>',
                'account-manager/<action>' => '/accessmanager/account-manager/<action>',
                'user-manager/<action>' => '/accessmanager/user-manager/<action>',
                'billing/<action>' => '/idbuser/billing/<action>',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'cacheApc' => [
            'class' => 'yii\caching\ApcCache'
        ],
        'cacheDB' => [
            'class' => 'yii\caching\DbCache'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'categories' => ['business'],
                    'levels' => ['info'],
                    'logFile' => '@runtime/logs/info.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 10,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'levels' => ['error', 'warning'],
                    'logFile' => '/var/log/p57b/p57b.business-errors.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'levels' => ['trace', 'info'],
                    'logFile' => '/var/log/p57b/p57b.business-debug.log',
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'business' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'sourceLanguage' => 'en-GB',
                    'basePath' => '@app/messages',
                ],
                'idbyii2' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'sourceLanguage' => 'en-GB',
                    'basePath' => '@idbyii2/messages',
                ],
                'idbexternal' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                    'sourceLanguage' => 'en-GB',
                    'basePath' => '@idbyii2/messages',
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:' . Localization::getDateFormat(),
            'datetimeFormat' => 'php:' . Localization::getDateTimeFormat(false),
            'timeFormat' => 'php:' . Localization::geTimeFormat(false),
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'EUR',
        ],
    ],
    'params' => $params,
];

if (defined('APP_THEME')) {
    switch (APP_THEME) {
        case 'adminlte2':
            $config['components']['view'] = [
                'theme' => [
                    'basePath' => '@app/themes/adminlte2',
                    'baseUrl' => '@web/themes/adminlte2',
                    'pathMap' => [
                        '@app/views' => '@app/themes/adminlte2/views',
                        '@app/modules' => '@app/themes/adminlte2/modules',
                    ],
                ]
            ];

            break;
        case 'default':
        default:
    }
}

if (defined('APP_LANGUAGE')) {
    $config['language'] = APP_LANGUAGE;
}

if (YII_ENV_DEV) {
    $allowedIPs = ['127.0.0.1', '::1'];
    if (!empty(BusinessConfig::get()->getYii2SecurityGiiAllowedIP())) {
        $allowedIPs [] = BusinessConfig::get()->getYii2SecurityGiiAllowedIP();
    }

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => $allowedIPs
    ];
}

$config['bootstrap'] = ['log'];
$config['on beforeRequest'] = function ($event) {
    idbyii2\models\db\BusinessModel::initModel();
};

return $config;

################################################################################
#                                End of file                                   #
################################################################################
