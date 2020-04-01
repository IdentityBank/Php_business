<?php

use app\helpers\Translate;
use yii\helpers\Html;

?>

<style>
    .error-page > .error-content {
        margin-left: 10px;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= $statusCode ?> <?= Translate::_('business', 'Error Page') ?>
        </h1>
    </section>

    <section class="content">
        <div class="error-page">
            <?php
            switch ($statusCode) {
                case 403:
                    {
                        ?>
                        <h2 class="headline text-blue"><?= $statusCode ?></h2>
                        <div class="error-content">
                            <h2><br><i class="fa fa-ban text-blue"></i> <?= Translate::_('business', 'Forbidden.') ?>
                            </h2>
                            <p>
                                <?= Translate::_('business', 'You don\'t have permission to access this document.') ?>
                                <br>
                                <?= Html::a(Translate::_('business', 'You can now return to the dashboard.'), ['/']) ?>
                            </p>
                        </div>
                        <?php
                    }
                    break;
                case 404:
                    {
                        ?>
                        <h2 class="headline text-yellow"><?= $statusCode ?></h2>
                        <div class="error-content">
                            <h2><i class="fa fa-warning text-yellow"></i> <?= Translate::_(
                                    'business',
                                    'An error has occurred, please try again.'
                                ) ?><br><?= Translate::_('business', 'Page not found.') ?></h2>
                            <p>
                                <?= Translate::_('business', 'The page you requested could not be found.') ?>
                                <br>
                                <?= Html::a(Translate::_('business', 'You can now return to the dashboard.'), ['/']) ?>
                            </p>
                        </div>
                        <?php
                    }
                    break;
                default:
                case 500:
                    {
                        ?>
                        <h2 class="headline text-red"><?= $statusCode ?></h2>
                        <div class="error-content">
                            <h2><i class="fa fa-warning text-red"></i> <?= Translate::_(
                                    'business',
                                    'An error has occurred, please try again.'
                                ) ?><br><?= Translate::_(
                                    'business',
                                    'An error has occurred, please try again later.'
                                ) ?></h2>
                            <p>
                                <?= Translate::_('business', 'We will work on fixing that right away.') ?>
                                <br>
                                <?= Html::a(Translate::_('business', 'You can now return to the dashboard.'), ['/']) ?>
                            </p>
                        </div>
                        <?php
                    }
                    break;
            }
            ?>


        </div>
    </section>
</div>
