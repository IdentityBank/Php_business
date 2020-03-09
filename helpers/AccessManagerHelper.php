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
# Use(s)                                                                       #
################################################################################

use idbyii2\helpers\IdbAccountId;
use idbyii2\models\db\BusinessAccount;
use idbyii2\models\db\BusinessAccountUser;
use idbyii2\models\db\BusinessDatabase;
use idbyii2\models\db\BusinessDatabaseData;
use idbyii2\models\db\BusinessDatabaseUser;
use idbyii2\models\idb\IdbBankClientBusiness;
use idbyii2\models\identity\IdbBusinessUser;
use Yii;
use yii\helpers\ArrayHelper;

################################################################################
# Class(es)                                                                    #
################################################################################

/**
 * Class AccessManagerHelper
 *
 * @package app\helpers
 */
class AccessManagerHelper
{

    /**
     * @param      $uid
     * @param null $dbName
     * @param null $description
     *
     * @return BusinessDatabaseUser
     * @throws \Exception
     */
    public static function createDatabase($uid, $dbName = null, $description = null)
    {
        $user = IdbBusinessUser::findIdentity($uid);
        $account = BusinessAccount::findOne(['aid' => $user->aid, 'oid' => $user->oid]);

        $database = BusinessDatabase::createDatabaseByAccount($account);
        if (!empty($dbName)) {
            $database->name = $dbName;
        }
        if (!empty($description)) {
            $database->description = $description;
        }
        $database->save();

        $businessId = IdbAccountId::generateBusinessDbId($user->oid, $user->aid, $database->dbid);

        $clientModel = IdbBankClientBusiness::model($businessId);
        $clientModel->createAccount([]);
        $clientModel->createAccountMetadata();
        $clientModel->createAccountEvents();
        $clientModel->createAccountCR();
        $clientModel->createAccountST();

        $model = new BusinessDatabaseUser();
        $model->dbid = $database->dbid;
        $model->uid = $uid;
        $model->save();

        return $model;
    }

    /**
     * @param      $accountName
     * @param bool $recreate
     *
     * @return bool|void
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteDatabase($accountName, $recreate = false)
    {
        // TODO: Add error handling for each step or we can move it as atomic operation into API code - long term solution !!!
        // For now always assuming all actions are executed correctly.
        $status = false;

        $idbAccountId = IdbAccountId::parse($accountName);
        if (empty($idbAccountId['dbid'])) {
            $msg = 'IDB ID business DB not defined for that account!';
            Yii::error(__FUNCTION__ . " - ERROR - $msg - [Account Name: $accountName]");
            throw new Exception($msg);

            return;
        }

        // Remove access to database from all users
        if (!$recreate) {
            BusinessDatabaseUser::deleteAll('dbid = :dbid', [':dbid' => $idbAccountId['dbid']]);
            $status = true;
        }

        // Delete Relations
        $clientModel = IdbBankClientBusiness::model('relation');
        $clientModel->deleteRelationsForBusiness($accountName);
        // Reset Metadata
        $clientModel = IdbBankClientBusiness::model($accountName);
        if ($recreate) {
            $clientModel->setAccountMetadata(json_encode([]));
            $clientModel->deleteAccountEvents();
            $clientModel->createAccountEvents();
        } else {
            $clientModel->deleteAccountEvents();
            $clientModel->deleteAccountMetadata();
        }
        // Delete IDB data
        $clientModel = IdbBankClientBusiness::model($accountName);
        if ($recreate) {
            $clientModel->recreateAccountST();
            $clientModel->recreateAccountCR();
            $clientModel->recreateAccount([]);
            $status = true;
        } else {
            $clientModel->deleteAccountST();
            $clientModel->deleteAccountCR();
            $clientModel->deleteAccount();
        }

        // Clean all db attributes from IDB
        if (!$recreate) {
            $model = BusinessDatabaseData::findOne($accountName);
            if ($model) {
                $status = $status && $model->delete();
            }
            $model = BusinessDatabase::find()->where('dbid = :dbid', [':dbid' => $idbAccountId['dbid']])->one();
            if ($model) {
                $status = $status && $model->delete();
            }
        }

        return $status;
    }

    /**
     * @return array
     */
    public static function getDatabases()
    {
        $businessAccounts = BusinessAccountUser::find()->where(['uid' => Yii::$app->user->identity->id])->all();
        $userDatabases = BusinessDatabaseUser::find()
                                             ->where(['uid' => Yii::$app->user->identity->id])
                                             ->select('dbid')
                                             ->asArray()
                                             ->all();
        $userDatabases = ArrayHelper::getColumn($userDatabases, 'dbid');
        $businessAccountDatabases = [];
        $businessAccountsNames = [];

        foreach ($businessAccounts as $businessAccount) {
            $businessAccount = BusinessAccount::findOne($businessAccount);
            if (!$businessAccount instanceof BusinessAccount) {
                continue;
            }
            $businessAccountsNames[$businessAccount->aid] = $businessAccount->name;
            $businessAccountUsersCount[$businessAccount->aid] = BusinessAccountUser::find()->where(
                ['aid' => $businessAccount->aid]
            )->count();
            $accountDatabases = BusinessDatabase::find()->where(['aid' => $businessAccount->aid])->asArray()->all();
            $businessAccountDatabases[$businessAccount->aid] = [];
            foreach ($accountDatabases as $accountDatabase) {
                if (in_array($accountDatabase['dbid'], $userDatabases)) {
                    $businessAccountDatabases[$businessAccount->aid][$accountDatabase['dbid']] = $accountDatabase;
                    $businessAccountDatabases[$businessAccount->aid][$accountDatabase['dbid']]['count'] = BusinessDatabaseUser::find(
                    )->where(['dbid' => $accountDatabase['dbid']])->count();
                }
            }
        }

        return [
            'businessAccounts' => $businessAccounts,
            'businessAccountDatabases' => $businessAccountDatabases,
            'businessAccountsNames' => $businessAccountsNames,
        ];
    }
}

################################################################################
#                                End of file                                   #
################################################################################
