<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" type="text/css" href="/components/cropper/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/components/bootstrap-3.3.5-dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/components/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="/components/toastr/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="/components/artDialog/css/ui-dialog.css">
    <link rel="stylesheet" type="text/css" href="/css/main.css">

    <script src="/js/jquery.min.js"></script>
    <script src="/components/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script src="/components/toastr/toastr.min.js"></script>
    <script src="/components/artDialog/dist/dialog-min.js"></script>
    <script src="/components/headroom/headroom.min.js"></script>
    <script src="/components/headroom/jquery.headroom.js"></script>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

<header class="header header--fixed hide-from-print animated slideDown">
    <?php
    NavBar::begin([
        'brandLabel' => 'Night cloud',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/home/index']],
            ['label' => 'Site', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['/site/login']] :
                ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']],
        ],
    ]);
    NavBar::end();
    ?>
</header>

<div id="main-content">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
    </div>
</footer>

</body>
</html>
<?php $this->endPage() ?>
