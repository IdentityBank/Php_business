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
# Include(s)                                                                   #
################################################################################

include_once 'simplelog.inc';
include_once 'jsonsimpleconfig.inc';
include_once 'idbyii2/helpers/IdbYii2Config.php';

################################################################################
# Use(s)                                                                       #
################################################################################

use idbyii2\helpers\IdbAccountNumberDestination;
use idbyii2\helpers\IdbYii2Config;
use function xmz\simplelog\logLevel;
use function xmz\simplelog\registerLogger;

################################################################################
# Local Config                                                                 #
################################################################################

const businessConfigFile = '/etc/p57b/business.jsc';
const businessConfigFileDefault = 'config.jsc';

################################################################################
# Class(es)                                                                    #
################################################################################

class BusinessConfig extends IdbYii2Config
{

    private static $instance;

    final protected function __construct()
    {
        parent::__construct();
        $this->mergeJscFile(
            dirname(__FILE__) . '/'
            . businessConfigFileDefault
        );
        $this->mergeJscFile(businessConfigFile);
        registerLogger($this->getLogName(), $this->getLogPath());
        logLevel($this->getLogLevel());
    }

    public static function get()
    {
        if (!isset(self::$instance) || !self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    function isDebug()
    {
        return $this->getValue(null, 'debug', false);
    }

    function isAssetManagerForceCopy()
    {
        return $this->getValue(null, 'assetManagerForceCopy', false);
    }

    function isYiiErrorHandlerEnabled()
    {
        return $this->getValue(null, 'yiiEnableErrorHandler', true);
    }

    function getLogLevel()
    {
        return $this->getValue("Log", "logLevel");
    }

    function getLogName()
    {
        return $this->getValue("Log", "logName");
    }

    function getLogPath()
    {
        return $this->getValue("Log", "logPath");
    }

    function getTheme()
    {
        return $this->getValue(null, 'theme', "default");
    }

    function getLoginUrl()
    {
        return $this->getValue(null, 'loginUrl', ['/login']);
    }

    function useBrowserLocalization()
    {
        return $this->getValue('Localization', 'browser');
    }

    function getDefaultLocalizationLanguage()
    {
        return $this->getValue('Localization', 'defaultLanguage');
    }

    function getLocalizationLanguage()
    {
        return $this->getValue(
            'Localization',
            'language',
            $this->getDefaultLocalizationLanguage()
        );
    }

    function getWebLanguage()
    {
        return $this->getValue(
            'Localization',
            'webLanguage',
            $this->getDefaultLocalizationLanguage()
        );
    }

    function getConsoleLanguage()
    {
        return $this->getValue(
            'Localization',
            'consoleLanguage',
            $this->getDefaultLocalizationLanguage()
        );
    }

    function isAuditEnabled()
    {
        return $this->getValue('Audit', 'enabled', false);
    }

    function isAuditEncrypted()
    {
        return $this->getValue('Audit', 'messageEncrypted', false);
    }

    function getAuditMessagePassword()
    {
        return $this->getValue('Audit', 'messagePassword', 'password');
    }

    function getAuditMessageSeparator()
    {
        return $this->getValue('Audit', 'messageSeparator', ']|[');
    }

    function getAuditPath()
    {
        return $this->getValue('Audit', 'auditPath');
    }

    function getAuditFileName()
    {
        return $this->getValue('Audit', 'auditFile', 'yii2_business_audit.log');
    }

    function isOAuthEnabled()
    {
        return $this->getValue('Authentication', 'oauth', false);
    }

    function getIdBankHost()
    {
        return $this->getValue('"IDBank"."IdBankClient"', 'host', '127.0.0.1');
    }

    function getIdBankPort()
    {
        return $this->getValue('"IDBank"."IdBankClient"', 'port', 57);
    }

    function getIdBillingName()
    {
        return $this->getValue('"IDBill"."IdbBillingClient"', 'name', 'p57b');
    }

    function getIdBillHost()
    {
        return $this->getValue('"IDBill"."IdbBillingClient"', 'host', '127.0.0.1');
    }

    function getIdBillPort()
    {
        return $this->getValue('"IDBill"."IdbBillingClient"', 'port', 87);
    }

    function getYii2BusinessCookieValidationKey()
    {
        return $this->getValue('"Yii2"."business"', 'cookieValidationKey');
    }

    function isIdentityByAccessTokenEnabled()
    {
        return $this->getValue(
            '"Yii2"."business"',
            'identityByAccessTokenEnabled'
        );
    }

    function getYii2BusinessEnableAutoLogin()
    {
        return $this->getValue('"Yii2"."business"', 'enableAutoLogin', false);
    }

    function isPortalRedAlertEnabled()
    {
        return $this->getValue('"Yii2"."business"', 'portalRedAlert', true);
    }

    function getYii2BusinessErrorsToSendRecovery()
    {
        return $this->getValue('"Yii2"."business"', 'errorsToSendRecovery', 3);
    }

    function getYii2BusinessAbsoluteAuthTimeout()
    {
        return $this->getValue(
            '"Yii2"."business"',
            'absoluteAuthTimeout',
            43200
        );
    }

    function getYii2BusinessAuthTimeout()
    {
        return $this->getValue('"Yii2"."business"', 'authTimeout', 3600);
    }

    function getYii2BusinessSkin()
    {
        return $this->getValue('"Yii2"."business"', 'skin', 'skin-blue');
    }

    function isYii2BusinessHelpEnabled()
    {
        return $this->getValue('"Yii2"."business"', 'help', true);
    }

    function passwordPolicy()
    {
        return $this->getValue('"Yii2"."business"', 'passwordPolicy');
    }

    function enabledYii2BusinessAfterLoginStartWithData()
    {
        return $this->getValue('"Yii2"."business"', 'afterLoginStartWithData', false);
    }

    function getYii2BusinessUploadEnabled()
    {
        return $this->getValue('"Yii2"."business"."upload"', 'enabled', true);
    }

    function getYii2BusinessUploadLocation()
    {
        $location = $this->getValue('"Yii2"."business"."upload"', 'uploadLocation');
        if (!empty($location) && substr($location, -1) !== DIRECTORY_SEPARATOR) {
            $location .= DIRECTORY_SEPARATOR;
        }

        return $location;
    }

    function getYii2BusinessUploadMaxFilesize()
    {
        return $this->getValue('"Yii2"."business"."upload"', 'maxFilesize');
    }

    function getYii2BusinessUploadAcceptedFiles()
    {
        return $this->getValue('"Yii2"."business"."upload"', 'acceptedFiles', ['csv', 'xls', 'xlsx']);
    }

    function getYii2BusinessUploadAcceptedFilesString()
    {
        $acceptedFiles = $this->getYii2BusinessUploadAcceptedFiles();
        foreach ($acceptedFiles as $index => $acceptedFile) {
            $acceptedFiles[$index] = ".$acceptedFile";
        }
        $acceptedFilesString = implode(", ", $acceptedFiles);

        return $this->getValue('"Yii2"."business"."upload"', 'acceptedFilesString', $acceptedFilesString);
    }

    function getYii2BusinessUploadTimeout()
    {
        return $this->getValue('"Yii2"."business"."upload"', 'timeout');
    }

    function getYii2BusinessUploadChunkSize()
    {
        return $this->getValue('"Yii2"."business"."upload"', 'chunkSize');
    }

    function getYii2BusinessDownloadEnabled()
    {
        return $this->getValue('"Yii2"."business"."download"', 'enabled', true);
    }

    function getYii2BusinessDownloadLocation()
    {
        $location = $this->getValue('"Yii2"."business"."download"', 'downloadLocation');
        if (!empty($location) && substr($location, -1) !== DIRECTORY_SEPARATOR) {
            $location .= DIRECTORY_SEPARATOR;
        }

        return $location;
    }

    function getYii2BusinessExportDelimiter()
    {
        return $this->getValue('"Yii2"."business"."export"."attributes"', 'delimiter', ";");
    }

    function getYii2BusinessExportEnclosure()
    {
        return $this->getValue('"Yii2"."business"."export"."attributes"', 'enclosure', '"');
    }

    function getYii2BusinessExportEscapeChar()
    {
        return $this->getValue('"Yii2"."business"."export"."attributes"', 'escapeChar', "\\");
    }

    function getYii2IdbSecurityMagicShift()
    {
        return $this->getValue('"Yii2"."security"."idb"', 'magic_shift', 5);
    }

    function getYii2BusinessSearchPassword()
    {
        return $this->getValue(
            '"Yii2"."security"."idb"',
            'search_password',
            'password'
        );
    }

    function getYii2BusinessSearchBlowfishCost()
    {
        return $this->getValue('"Yii2"."security"."idb"', 'search_blowfish', 1);
    }

    function getYii2BusinessLogPassword()
    {
        return $this->getValue(
            '"Yii2"."security"."idb"',
            'log_password',
            'password'
        );
    }

    function getYii2BusinessLogBlowfishCost()
    {
        return $this->getValue('"Yii2"."security"."idb"', 'log_blowfish', 1);
    }

    function getYii2BusinessModulesIdbUserConfigUserAccount()
    {
        $configUserAccount = $this->getValue(
            '"Yii2"."business"."modules"."idbuser"',
            'configUserAccount'
        );
        if ((!empty($configUserAccount)) && (is_array($configUserAccount))) {
            return $configUserAccount;
        }

        return null;
    }

    function getYii2BusinessModulesIdbUserConfigUserData()
    {
        $configUserData = $this->getValue(
            '"Yii2"."business"."modules"."idbuser"',
            'configUserData'
        );
        if ((!empty($configUserData)) && (is_array($configUserData))) {
            return $configUserData;
        }

        return null;
    }

    function getYii2BusinessModulesSignUpConfig()
    {
        $configSignUp = $this->getValue(
            '"Yii2"."business"."modules"."signup"',
            'configSignUp'
        );
        if ((!empty($configSignUp)) && (is_array($configSignUp))) {
            return $configSignUp;
        }

        return null;
    }

    function getYii2BusinessModulesAuditLogConfig()
    {
        $configAuditLog = $this->getValue(
            '"Yii2"."business"."modules"."idbdata"',
            'configAuditLog'
        );
        if ((!empty($configAuditLog)) && (is_array($configAuditLog))) {
            return $configAuditLog;
        }

        return null;
    }

    function getYii2BusinessModulesNotificationsConfig()
    {
        $configNotifications = $this->getValue(
            '"Yii2"."business"."modules"."notifications"',
            'configNotifications'
        );
        if ((!empty($configNotifications)) && (is_array($configNotifications))) {
            return $configNotifications;
        }

        return null;
    }

    function getYii2BusinessSignUpFormAuthenticatorEnabled()
    {
        return $this->getValue(
            '"Yii2"."business"."modules"."signup"',
            'authenticatorEnabled',
            true
        );
    }

    function getYii2BusinessSignUpFormAuthenticatorSecurityKey()
    {
        return $this->getValue(
            '"Yii2"."business"."modules"."signup"',
            'authenticatorSecurityKey'
        );
    }

    function getYii2BusinessSignUpFormCaptchaEnabled()
    {
        return $this->getValue(
            '"Yii2"."business"."modules"."signup"',
            'captchaEnabled',
            true
        );
    }

    function getYii2BusinessSignUpFormCaptchaSiteKey()
    {
        return $this->getValue(
            '"Yii2"."business"."modules"."signup"',
            'captchaSiteKey'
        );
    }

    function getYii2BusinessSignUpFormCaptchaSecret()
    {
        return $this->getValue(
            '"Yii2"."business"."modules"."signup"',
            'captchaSecret'
        );
    }

    function getYii2BusinessNotificationConfig()
    {
        $configNotification = $this->getValue(
            '"Yii2"."business"."notification"',
            'configNotification'
        );

        if ((!empty($configNotification)) && (is_array($configNotification))) {
            return $configNotification;
        }

        return null;
    }

    function skipPaymentEnabled()
    {
        return ($this->getValue('Payment', 'skipPaymentEnabled') === 'IDB_ALLOW_SKIP_PAYMENT');
    }

    function getPaymentApiKey()
    {
        return $this->getValue('Payment', 'apiKey');
    }

    function getPaymentEnvironment()
    {
        return $this->getValue('Payment', 'environment');
    }

    function getPaymentMerchant()
    {
        return $this->getValue('Payment', 'merchant');
    }

    function getPaymentCurrency()
    {
        return $this->getValue('Payment', 'currency');
    }

    function getPaymentLocale()
    {
        return $this->getValue('Payment', 'locale', Yii::$app->language);
    }

    function getPaymentLoadingContext()
    {
        return $this->getValue('Payment', 'loadingContext');
    }

    function getYii2MfaIssuer($portal = IdbAccountNumberDestination::business)
    {
        return parent::getYii2MfaIssuer(IdbAccountNumberDestination::business);
    }

    function getJSBusinessExtendedClientSetsCreator()
    {
        return $this->getValue('"JS"."business"', 'extendedClientSetsCreator', 'true');
    }
}

################################################################################
#                                End of file                                   #
################################################################################
