<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use backend\assets\AdminAsset;
use yii\bootstrap\Modal;
use lib\Helper;

AdminAsset::register($this);

$sidebarCollapsed = @$_COOKIE['admin_sidebar_collapsed'] === 'true' ? 'sidebar-collapsed' : null;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <?= Html::csrfMetaTags() ?>
    <meta name="description" content="<?= Html::encode($this->title) ?>"/>
    <meta name="author" content=""/>

    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>

    <script>$.noConflict();</script>

    <!--[if lt IE 9]>
    <script src="/admin/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>if (typeof ($) === 'undefined') $ = jQuery;</script>
</head>
<body class="page-body" data-url=""><!-- page-fade-only   -->

<?php $this->beginBody() ?>

<div class="page-container <?= $sidebarCollapsed ?>">
    <!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

    <div class="sidebar-menu">

        <div class="sidebar-menu-inner">

            <header class="logo-env">

                <!-- logo -->
                <div class="logo">
                    <a href="/admin">
                        <h1 style="color:#fff;padding: 0;margin: 0">Gear App</h1>
                    </a>
                </div>

                <!-- logo collapse icon -->
                <div class="sidebar-collapse">
                    <a href="#" class="sidebar-collapse-icon">
                        <!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                        <i class="entypo-menu"></i>
                    </a>
                </div>

                <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                <div class="sidebar-mobile-menu visible-xs">
                    <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                        <i class="entypo-menu"></i>
                    </a>
                </div>

            </header>

            <?php
            include('sidebar.php');
            ?>

        </div>

    </div>

    <div class="main-content">

        <div class="row">

            <!-- Profile Info and Notifications -->
            <?php
            include('profile_info.php');
            ?>

            <!-- Raw Links -->
            <?php
            include('raw_links.php');
            ?>

        </div>

        <hr/>

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'homeLink' => [
                'label' => 'Панель управления',
                'url' => '/admin'
            ],
        ]) ?>

        <!-- Main content -->
        <div class="row" style="padding:0 15px">

            <?php
            include('messages.php');
            ?>

            <?php
            if (!empty($this->title)):
                ?>
                <h2><?= $this->title ?></h2>
                <br>
                <?php
            endif;
            ?>

            <?= $content ?>

        </div>

        <br/>

        <!-- Footer -->
        <footer class="main">

            &copy; <?= date('Y') ?> <strong><?= getenv('HTTP_HOST') ?></strong>

        </footer>
    </div>

</div>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>

<?php

Modal::begin([
    'id' =>'modal-md',
    'size' => 'modal-md',
]);
Modal::end();

Modal::begin([
    'id' =>'modal-xl',
    'size' => 'modal-xl',
]);
Modal::end();

Modal::begin([
    'id' =>'modal-sm',
    'size' => 'modal-sm',
]);
Modal::end();

Modal::begin([
    'id' =>'modal-lg',
    'size' => 'modal-lg',
]);
Modal::end();

?>
