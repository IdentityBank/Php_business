<?php

use app\helpers\Translate;
use idbyii2\helpers\Localization;
use idbyii2\models\db\BusinessAuthlog;
use idbyii2\widgets\FlashMessage;
use yii\helpers\Html;

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?= Html::encode(Translate::_('business', 'Account Details')) ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <?= FlashMessage::widget(
                [
                    'success' => Yii::$app->session->hasFlash('success') ? Yii::$app->session->getFlash('success')
                        : null,
                    'error' => Yii::$app->session->hasFlash('error') ? Yii::$app->session->getFlash('error') : null,
                    'info' => Yii::$app->session->hasFlash('info') ? Yii::$app->session->getFlash('info') : null,
                ]
            ); ?>
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="box">
                    <div class="box-body box-profile">

                        <h3 class="profile-username text-center"><?= $accountName ?></h3>
                        <hr>

                        <p class="text-muted text-center"><?= $userId ?></p>
                        <p class="text-muted text-center"><?= $accountNumber ?></p>
                        <hr>

                        <div class="box-body">

                            <strong><i class="fa fa-building margin-r-5"></i> <?= Translate::_(
                                    'business',
                                    'Organization'
                                ) ?></strong>
                            <p class="text-muted"><?= $oid ?></p>
                            <hr>

                            <strong><i class="fa fa-id-badge margin-r-5"></i> <?= Translate::_('business', 'Account') ?>
                            </strong>
                            <p class="text-muted"><?= $aid ?></p>
                            <hr>

                        </div>

                        <?= Html::a(
                            Translate::_('business', 'Change Password'),
                            ['changepassword'],
                            ['class' => 'btn btn-primary input-block-level form-control']
                        ) ?>
                    </div>
                </div>

            </div>

            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <?= Html::tag(
                            'li',
                            Html::a(
                                Translate::_('business', "Login History"),
                                "#activity",
                                ['data-toggle' => "tab"]
                            ),
                            ['class' => "active"]
                        ) ?>
                        <?php if (
                            Yii::$app->user->can('action_organization_billing_manager')
                            or Yii::$app->user->can('action_account_manager')
                        ) : ?>
                            <?= Html::tag(
                                'li',
                                Html::a(
                                    Translate::_('business', "Business Information"),
                                    "#information",
                                    ['data-toggle' => "tab"]
                                )
                            ) ?>
                            <?= Html::tag(
                                'li',
                                Html::a(
                                    Translate::_('business', "Business Contact"),
                                    "#contact",
                                    ['data-toggle' => "tab"]
                                )
                            ) ?>
                            <?= Html::tag(
                                'li',
                                Html::a(
                                    Translate::_('business', "DPO information"),
                                    "#dpo",
                                    ['data-toggle' => "tab"]
                                )
                            ) ?>
                            <?= Html::tag(
                                'li',
                                Html::a(
                                    Translate::_('business', "Delete Account"),
                                    "#delete",
                                    ['data-toggle' => "tab"]
                                )
                            ) ?>
                        <?php endif; ?>
                    </ul>

                    <div class="tab-content">

                        <div class="active tab-pane" id="activity">
                            <ul class="timeline timeline-inverse">
                                <li class="time-label"><span class="bg-red"><?= Localization::getDate() ?></span></li>
                                <?php $authLogModels = BusinessAuthlog::findAllByUid(Yii::$app->user->identity->id, 10);
                                foreach ($authLogModels as $authLogModel) : ?>
                                    <li>
                                        <i class="fa fa-user bg-blue"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="fa fa-clock-o"></i> <?= $authLogModel->event ?></span>

                                            <h3 class="timeline-header no-border">
                                                <?= Yii::$app->formatter->format(
                                                    $authLogModel->timestamp,
                                                    'dateTime'
                                                ) . ' [<b><i>' . $authLogModel->ip . '</i></b>]' ?>
                                            </h3>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                                <li>
                                    <i class="fa fa-clock-o bg-gray"></i>
                                </li>
                            </ul>
                        </div>

                        <?php if (
                            Yii::$app->user->can('action_organization_billing_manager')
                            or Yii::$app->user->can('action_account_manager')
                        ) : ?>
                            <?= $this->context->renderPartial('_profile-business-information', compact('data')) ?>
                            <?= $this->context->renderPartial('_profile-business-contact', compact('data')) ?>
                            <?= $this->context->renderPartial('_profile-dpo-information', compact('data')) ?>
                            <?= $this->context->renderPartial('_profile-business-delete', compact('data')) ?>
                        <?php endif; ?>

                    </div>

                </div>
            </div>
        </div>
    </section>

</div>
