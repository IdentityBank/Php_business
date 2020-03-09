<?php

use app\assets\AdminLte2AppAsset;
use app\assets\AdminLte2Asset;
use yii\helpers\Html;

$assetBundleAdminLte2 = AdminLte2Asset::register($this);
$assetBundle = AdminLte2AppAsset::register($this);
$assetUrlAdminLte2 = $assetBundleAdminLte2->getAssetUrl();
$assetUrl = $assetBundle->getAssetUrl();
$params = [
    'assetUrlAdminLte2' => $assetUrlAdminLte2,
    'assetUrl' => $assetUrl
];
$skin = BusinessConfig::get()->getYii2BusinessSkin();
switch ($skin) {
    case 'skin-black-light':
        {
            $theme_color = "#ffffff";
        }
        break;
    case 'skin-black':
        {
            $theme_color = "#ffffff";
        }
        break;
    case 'skin-blue-light':
        {
            $theme_color = "#3c8dbc";
        }
        break;
    case 'skin-blue':
        {
            $theme_color = "#3c8dbc";
        }
        break;
    case 'skin-green-light':
        {
            $theme_color = "#00a65a";
        }
        break;
    case 'skin-green':
        {
            $theme_color = "#00a65a";
        }
        break;
    case 'skin-purple-light':
        {
            $theme_color = "#605ca8";
        }
        break;
    case 'skin-purple':
        {
            $theme_color = "#605ca8";
        }
        break;
    case 'skin-red-light':
        {
            $theme_color = "#dd4b39";
        }
        break;
    case 'skin-red':
        {
            $theme_color = "#dd4b39";
        }
        break;
    case 'skin-yellow-light':
        {
            $theme_color = "#f39c12";
        }
        break;
    case 'skin-yellow':
        {
            $theme_color = "#f39c12";
        }
        break;
    case 'skin-idb':
        {
            $theme_color = "#00AEAB";
            $assetBundleAdminLte2->loadIdbSkin();
        }
        break;
    default:
        $theme_color = "#999999";
}

$sidebarCollapse = '';
if (
    !empty($_COOKIE['toggleStateAdminLTE'])
    && ($_COOKIE['toggleStateAdminLTE'] === 'closed')
) {
    $sidebarCollapse = 'sidebar-collapse';
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>

    <meta charset="<?= Yii::$app->charset ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="theme-color" content="<?= $theme_color ?>"/>
    <meta name="msapplication-TileColor" content="<?= $theme_color ?>">
    <?php if ($skin === 'skin-idb') { ?>
        <meta name="msapplication-config" content="<?= $assetUrlAdminLte2 ?>idb/img/ico/browserconfig.xml">
        <link href="//fonts.googleapis.com/css?family=Handlee:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=latin"
              rel="stylesheet" type="text/css"/>
        <link href="//fonts.googleapis.com/css?family=Ubuntu:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=cyrillic-ext"
              rel="stylesheet" type="text/css"/>
    <?php } ?>
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $assetUrlAdminLte2 ?>idb/img/ico/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $assetUrlAdminLte2 ?>idb/img/ico/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $assetUrlAdminLte2 ?>idb/img/ico/favicon-16x16.png">
    <link rel="manifest" href="<?= $assetUrlAdminLte2 ?>idb/img/ico/site.webmanifest">
    <link rel="mask-icon" href="<?= $assetUrlAdminLte2 ?>idb/img/ico/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="<?= $assetUrlAdminLte2 ?>idb/img/ico/favicon.ico">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->context->getPageTitle($this->title)) ?></title>

    <?php $this->head() ?>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>
<body class="hold-transition <?= $skin ?> sidebar-mini <?= $sidebarCollapse ?>">

<?php $this->beginBody() ?>

<?= $content ?>

<?php $this->endBody() ?>

<script>
    $(function () {
        $("body")
            .on("collapsed.pushMenu", function () {
                Cookie.set('toggleStateAdminLTE', 'closed');
            })
            .on("expanded.pushMenu", function () {
                Cookie.set('toggleStateAdminLTE', 'opened');
            });
    });
</script>

</body>
</html>
<?php $this->endPage() ?>
