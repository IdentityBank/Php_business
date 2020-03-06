<?php

use app\helpers\Translate;
use yii\helpers\Html;

$dbMenu = in_array(
    $menu_active_item,
    [
        '[menu][applications][idbdata]',
        '[menu][tools][idbdata]',
        '[menu][tools][idbstorage]',
        '[menu][tools][import]',
        '[menu][tools][logs_used_data]',
        '[menu][applications][contacts]',
        '[menu][tools][logs_change_requests]',
        '[menu][applications][b2p_messages]',
        '[menu][admin_db][db_manager]',
        '[menu][gdpr][gdpr]'
    ]
);

$administrationMenu = ($menu_active_section === '[menu][account_administration]');

function menuText($string)
{
    return Html::encode(mb_strimwidth($string, 0, 25, '...'));
}

?>

<style>
    .sidebar-mini:not(.sidebar-mini-expand-feature).sidebar-collapse .sidebar-menu > li:hover > a > span:not(.pull-right),
    .sidebar-mini:not(.sidebar-mini-expand-feature).sidebar-collapse .sidebar-menu > li:hover > .treeview-menu {
        width: 220px;
    }

    .sidebar-mini:not(.sidebar-mini-expand-feature).sidebar-collapse .sidebar-menu > li:hover > a > .pull-right-container {
        left: 220px !important;
    }
</style>

<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">

            <?=
            Html::tag(
                'li',
                Html::a(
                    Html::tag('i', null, ['class' => 'fa fa-dashboard']) .
                    Html::tag('span', menuText(Translate::_('business', 'Dashboard'))),
                    ['/']
                ),
                [
                    'class' => (($menu_active_item === '[menu][site][index]') ? ' active' : null),
                    'title' => Translate::_('business', 'Dashboard')
                ]
            ); ?>
            <?php if (Yii::$app->user->can('organization_user')) : ?>
                <?=
                Html::tag(
                    'li',
                    menuText(Translate::_('business', 'My Vaults')),
                    ['class' => 'header']
                ); ?>
                <?php foreach (Yii::$app->user->identity->userCurrentDatabases as $database) : ?>
                    <?php $activeDb = ($dbMenu) && ($database['dbid'] === Yii::$app->user->identity->dbid) ?>
                    <?=
                    Html::tag(
                        'li',
                        Html::a(
                            Html::tag('i', null, ['class' => 'fa fa-bank']) .
                            Html::tag('span', Html::encode(mb_strimwidth($database['name'], 0, 25, '...'))) .
                            Html::tag(
                                'span',
                                Html::tag('i', null, ['class' => 'fa fa-angle-left pull-right']),
                                ['class' => 'pull-right-container']
                            ),
                            '#',
                            ['title' => Html::encode($database['name'])]
                        ) .
                        Html::tag(
                            'ul',
                            Html::tag(
                                'li',
                                Html::a(
                                    Html::tag('i', null, ['class' => 'fa fa-list']) .
                                    Html::tag('span', menuText(Translate::_('business', 'Access vault'))),
                                    ['/idb-menu', 'dbid' => $database['dbid'], 'action' => '/idbdata/idb-data/show-all']
                                ),
                                [
                                    'class' => (($activeDb
                                        && (in_array(
                                            $menu_active_item,
                                            ['[menu][applications][idbdata]', '[menu][tools][idbdata]']
                                        )))
                                        ? ' active' : null),
                                    'title' => Translate::_('business', 'Access vault')
                                ]
                            ) .

                            Html::tag(
                                'li',
                                Html::a(
                                    Html::tag('i', null, ['class' => 'fa fa-file']) .
                                    Html::tag('span', menuText(Translate::_('business', 'File Management'))),
                                    ['/idb-menu', 'dbid' => $database['dbid'], 'action' => '/idb-storage/index']
                                ),
                                [
                                    'class' => (($activeDb && ($menu_active_item === '[menu][tools][idbstorage]'))
                                        ? ' active'
                                        : null),
                                    'title' => Translate::_('business', 'File Management')
                                ]
                            ) .

                            Html::tag(
                                'li',
                                Html::a(
                                    Html::tag('i', null, ['class' => 'fa fa-file-import']) .
                                    Html::tag('span', menuText(Translate::_('business', 'Add More Data'))),
                                    ['/idb-menu', 'dbid' => $database['dbid'], 'action' => '/tools/wizard/index']
                                ),
                                [
                                    'class' => (($activeDb && ($menu_active_item === '[menu][tools][import]'))
                                        ? ' active'
                                        : null),
                                    'title' => Translate::_('business', 'Add More Data')
                                ]
                            ) .
                            Html::tag(
                                'li',
                                Html::a(
                                    Html::tag('i', null, ['class' => 'fa fa-clipboard']) .
                                    Html::tag('span', menuText(Translate::_('business', 'View audit log'))),
                                    ['/idb-menu', 'dbid' => $database['dbid'], 'action' => '/logs/used-data']
                                ),
                                [
                                    'class' => (($activeDb && ($menu_active_item === '[menu][tools][logs_used_data]'))
                                        ? ' active' : null),
                                    'title' => Translate::_('business', 'View audit log')
                                ]
                            ) .
                            Html::tag(
                                'li',
                                Html::a(
                                    Html::tag('i', null, ['class' => 'fa fa-users']) .
                                    Html::tag('span', menuText(Translate::_('business', 'Connect with people'))),
                                    [
                                        '/idb-menu',
                                        'dbid' => $database['dbid'],
                                        'action' => '/applications/contacts/access'
                                    ]
                                ),
                                [
                                    'class' => (($activeDb && ($menu_active_item === '[menu][applications][contacts]'))
                                        ? ' active' : null),
                                    'title' => Translate::_('business', 'Connect with people')
                                ]
                            ) .
                            Html::tag(
                                'li',
                                Html::a(
                                    Html::tag('i', null, ['class' => 'fa fa-exchange']) .
                                    Html::tag('span', menuText(Translate::_('business', 'Review change requests'))),
                                    ['/idb-menu', 'dbid' => $database['dbid'], 'action' => '/logs/changed-data']
                                ),
                                [
                                    'class' => (($activeDb
                                        && ($menu_active_item === '[menu][tools][logs_change_requests]'))
                                        ? ' active' : null),
                                    'title' => Translate::_('business', 'Review change requests')
                                ]
                            ) .
                            Html::tag(
                                'li',
                                Html::a(
                                    Html::tag('i', null, ['class' => 'fa fa-edit']) .
                                    Html::tag('span', menuText(Translate::_('business', 'Send messages'))),
                                    [
                                        '/idb-menu',
                                        'dbid' => $database['dbid'],
                                        'action' => '/btpmessages/btpmessages/create'
                                    ]
                                ),
                                [
                                    'class' => (($activeDb
                                        && ($menu_active_item === '[menu][applications][b2p_messages]'))
                                        ? ' active' : null),
                                    'title' => Translate::_('business', 'Send messages')
                                ]
                            ) .
                            Html::tag(
                                'li',
                                Html::a(
                                    Html::tag('i', null, ['class' => 'fa fa-user-secret']) .
                                    Html::tag('span', menuText(Translate::_('business', 'GDPR options'))),
                                    [
                                        '/idb-menu',
                                        'dbid' => $database['dbid'],
                                        'action' => '/gdpr'
                                    ]
                                ),
                                [
                                    'class' => (($activeDb && ($menu_active_item === '[menu][gdpr][gdpr]'))
                                        ? ' active' : null),
                                    'title' => Translate::_('business', 'GDPR options')
                                ]
                            ) .
                            Html::tag(
                                'li',
                                Html::a(
                                    Html::tag('i', null, ['class' => 'fa fa-th-large']) .
                                    Html::tag('span', menuText(Translate::_('business', 'Manage vault'))),
                                    [
                                        '/idb-menu',
                                        'dbid' => $database['dbid'],
                                        'action' => '/accessmanager/database-manager'
                                    ]
                                ),
                                [
                                    'class' => (($activeDb && ($menu_active_item === '[menu][admin_db][db_manager]'))
                                        ? ' active' : null),
                                    'title' => Translate::_('business', 'Manage vault')
                                ]
                            ),
                            ['class' => 'treeview-menu']
                        ),
                        ['class' => 'treeview' . ($activeDb ? ' active' : null)]
                    ); ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <?=
            Html::tag(
                'li',
                menuText(Translate::_('business', 'Menu')),
                ['class' => 'header']
            ); ?>
            <?php if (Yii::$app->user->can('organization_user')) : ?>
                <?=
                Html::tag(
                    'li',
                    Html::a(
                        Html::tag('i', null, ['class' => 'fa fa-plus-square']) .
                        Html::tag('span', menuText(Translate::_('business', 'Create new vault'))),
                        ['/tools/wizard/select-db']
                    ),
                    [
                        'class' => (($menu_active_item === '[menu][tools][import][select-db]') ? ' active' : null),
                        'title' => Translate::_('business', 'Create new vault')
                    ]
                ); ?>
                <?=
                Html::tag(
                    'li',
                    Html::a(
                        Html::tag('i', null, ['class' => 'fa fa-file-export']) .
                        Html::tag('span', menuText(Translate::_('business', 'Manage exports'))),
                        ['/tools/export/index']
                    ),
                    [
                        'class' => (($menu_active_item === '[menu][tools][export]') ? ' active' : null),
                        'title' => Translate::_('business', 'Manage exports')
                    ]
                ); ?>
            <?php endif; ?>
            <?=
            Html::tag(
                'li',
                Html::a(
                    Html::tag('i', null, ['class' => 'fa fa-gear']) .
                    Html::tag('span', menuText(Translate::_('business', 'Account Administration'))) .
                    Html::tag(
                        'span',
                        Html::tag('i', null, ['class' => 'fa fa-angle-left pull-right']),
                        ['class' => 'pull-right-container']
                    ),
                    '#',
                    ['title' => Translate::_('business', 'Account Administration')]
                ) .
                Html::tag(
                    'ul',
                    ((Yii::$app->user->can('action_manage_users')) ? Html::tag(
                        'li',
                        Html::a(
                            Html::tag('i', null, ['class' => 'fa fa-users']) .
                            Html::tag('span', menuText(Translate::_('business', 'Manage users'))),
                            ['/accessmanager/user-manager']
                        ),
                        [
                            'class' => (($menu_active_item === '[menu][admin_db][user_manager]') ? ' active' : null),
                            'title' => Translate::_('business', 'Manage users')
                        ]
                    ) : null) .
                    ((Yii::$app->user->can('action_organization_billing_manager')) ? Html::tag(
                        'li',
                        Html::a(
                            Html::tag('i', null, ['class' => 'fa fa-money-bill-wave']) .
                            Html::tag('span', menuText(Translate::_('business', 'Billing'))),
                            ['/billing/index']
                        ),
                        [
                            'class' => (($menu_active_item === '[menu][billing][user_billing]') ? ' active' : null),
                            'title' => Translate::_('business', 'Billing')
                        ]
                    ) : null) .
                    ((Yii::$app->user->can('action_organization_billing_manager')) ? Html::tag(
                        'li',
                        Html::a(
                            Html::tag('i', null, ['class' => 'fa fa-cc-paypal']) .
                            Html::tag('span', menuText(Translate::_('business', 'Payments'))),
                            ['/billing/payments']
                        ),
                        [
                            'class' => (($menu_active_item === '[menu][billing][user_payments]') ? ' active' : null),
                            'title' => Translate::_('business', 'Payments')
                        ]
                    ) : null) .
                    ((Yii::$app->user->can('action_organization_billing_manager')) ? Html::tag(
                        'li',
                        Html::a(
                            Html::tag('i', null, ['class' => 'fa fa-list-alt']) .
                            Html::tag('span', menuText(Translate::_('business', 'Service Usage'))),
                            ['/billing/logs']
                        ),
                        [
                            'class' => (($menu_active_item === '[menu][billing][user_logs]') ? ' active' : null),
                            'title' => Translate::_('business', 'Service Usage')
                        ]
                    ) : null) .
                    Html::tag(
                        'li',
                        Html::a(
                            Html::tag('i', null, ['class' => 'fa fa-user']) .
                            Html::tag('span', menuText(Translate::_('business', 'Account Details'))),
                            ['/profile']
                        ),
                        [
                            'class' => (($menu_active_item === '[menu][account][user_profile]') ? ' active' : null),
                            'title' => Translate::_('business', 'Account Details')
                        ]
                    ),
                    ['class' => 'treeview-menu']
                ),
                [
                    'class' => 'treeview' . ($administrationMenu ? ' active' : null)
                ]
            ); ?>
            <?php if (BusinessConfig::get()->isYii2BusinessHelpEnabled()) : ?>
                <?=
                Html::tag(
                    'li',
                    Html::a(
                        Html::tag(
                            'i',
                            null,
                            [
                                'class' => 'fa fa-info-circle text-red'
                            ]
                        ) .
                        Html::tag('span', menuText(Translate::_('business', 'Help library'))) .
                        Html::tag(
                            'span',
                            Html::tag(
                                'i',
                                null,
                                [
                                    'class' => 'fa fa-angle-left pull-right'
                                ]
                            ),
                            ['class' => 'pull-right-container']
                        ),
                        '#',
                        [
                            'title' => Translate::_('business', 'Help library')
                        ]
                    ) .
                    Html::tag(
                        'ul',
                        Html::tag(
                            'li',
                            Html::a(
                                Html::tag('i', null, ['class' => 'fa fa-question']) .
                                Html::tag('span', menuText(Translate::_('business', 'How to ...'))),
                                Translate::_('business', 'https://www.identitybank.eu/help/business'),
                                [
                                    'target' => '_blank',
                                    'title' => Translate::_('business', 'How to ...')
                                ]
                            )
                        ),
                        ['class' => 'treeview-menu']
                    ),
                    [
                        'class' => 'treeview'
                    ]
                ); ?>
            <?php endif; ?>
            <?=
            Html::tag(
                'li',
                Html::a(
                    Html::tag('i', null, ['class' => 'fa fa-sign-out text-red']) .
                    Html::tag(
                        'span',
                        menuText(Translate::_('business', 'Logout')),
                        ['class' => 'text-red']
                    ),
                    null,
                    [
                        'style' => 'cursor: pointer',
                        'data-toggle' => 'modal',
                        'data-target' => '#logoutModal',
                        'title' => Translate::_('business', 'Logout')
                    ]
                )
            ); ?>

        </ul>
    </section>
</aside>
