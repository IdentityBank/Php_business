<?php

use app\helpers\Translate;
use idbyii2\models\db\BusinessDatabase;

$dbName = BusinessDatabase::findOne(
    ['aid' => Yii::$app->user->identity->aid, 'dbid' => Yii::$app->user->identity->dbid]
);
if ($dbName) {
    $dbName = $dbName->name;
}

?>

<style>
    #clicker {
        display: block;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0px;
        left: 0px;
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="row" style="margin-bottom: 50px">
            <div class="col-lg-3 col-xs-12" style="text-align: center">
            </div>
            <div class="col-lg-6 col-xs-12" style="text-align: center">
                <h2><b><?= Translate::_('business', 'Welcome to your new Identity Bank account!') ?></b></h2>
                <h3><?= Translate::_(
                        'business',
                        'This is your <b>dashboard</b> which you use to manage your account.'
                    ); ?></h3>
                <h3><?= Translate::_(
                        'business',
                        'To get started with your account the first task you need to do is to import business data into your account. To do this, click on the <b>Import from file</b> button to use the import wizard to bring spreadsheet data into your account.'
                    ) ?></h3>
                <h3><?= Translate::_(
                        'business',
                        'After import you can manage your business data from this dashboard. Typical tasks you can do are: search, update, email and export personal data about, for example, your customers, employees, advisors and suppliers.'
                    ) ?></h3>
                <h3><?= Translate::_(
                        'business',
                        'Data entrusted to Identity Bank is protected with the latest encryption technology following modern security standards. People can see their own data, control it, and see what it is being used for. This keeps your customers happy and helps your business comply with strong privacy laws.'
                    ) ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-xs-12" style="text-align: center">
            </div>
            <div class="col-lg-6 col-xs-12" style="text-align: center">
                <div class="small-box bg-blue" style="text-align: center">
                    <div class="inner">
                        <h3><?= Translate::_('business', "Import from file") ?></h3>

                        <p><?= $dbName ?></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-document"></i>
                    </div>
                    <a id="my-import" href="tools/wizard/select-db" class="small-box-footer"><?= Translate::_(
                            'business',
                            "Import my data"
                        ) ?> <i class="fa fa-arrow-circle-right"></i></a>
                    <a href="tools/wizard/select-db" id="clicker"></a>
                </div>
            </div>
        </div>

    </section>
</div>
