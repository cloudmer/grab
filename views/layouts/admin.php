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
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script src="/js/jquery.min.js"></script>

    <script type="text/javascript">
        //<![CDATA[
        try{if (!window.CloudFlare) {var CloudFlare=[{verbose:0,p:1419364062,byc:0,owlid:"cf",bag2:1,mirage2:0,oracle:0,paths:{cloudflare:"/cdn-cgi/nexp/dok2v=1613a3a185/"},atok:"1fca8a26fb9678bbb4b5c54c34e227b9",petok:"4ca96b72a62631073dd6873922c67f1bf6e51b65-1420553914-1800",zone:"adbee.technology",rocket:"0",apps:{"ga_key":{"ua":"UA-49262924-2","ga_bs":"2"}}}];!function(a,b){a=document.createElement("script"),b=document.getElementsByTagName("script")[0],a.async=!0,a.src="/components/centaurus/js/cloudflare.min.js",b.parentNode.insertBefore(a,b)}()}}catch(e){};
        //]]>
    </script>
    <link rel="stylesheet" type="text/css" href="/components/centaurus/css/bootstrap/bootstrap.min.css"/>

    <script src="/components/centaurus/js/demo-rtl.js"></script>


    <link rel="stylesheet" type="text/css" href="/components/centaurus/css/libs/font-awesome.css"/>
    <link rel="stylesheet" type="text/css" href="/components/centaurus/css/libs/nanoscroller.css"/>

    <link rel="stylesheet" type="text/css" href="/components/centaurus/css/compiled/theme_styles.css"/>

    <link rel="stylesheet" href="/components/centaurus/css/libs/fullcalendar.css" type="text/css"/>
    <link rel="stylesheet" href="/components/centaurus/css/libs/fullcalendar.print.css" type="text/css" media="print"/>
    <link rel="stylesheet" href="/components/centaurus/css/compiled/calendar.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/components/centaurus/css/libs/morris.css" type="text/css"/>
    <link rel="stylesheet" href="/components/centaurus/css/libs/daterangepicker.css" type="text/css"/>
    <link rel="stylesheet" href="/components/centaurus/css/libs/jquery-jvectormap-1.2.2.css" type="text/css"/>

    <link type="image/x-icon" href="favicon.png" rel="shortcut icon"/>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
    <!--[if lt IE 9]>
    <script src="/components/centaurus/js/html5shiv.js"></script>
    <script src="/components/centaurus/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        /* <![CDATA[ */
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-49262924-2']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
//            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            ga.src = '/components/centaurus/js/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

        (function(b){(function(a){"__CF"in b&&"DJS"in b.__CF?b.__CF.DJS.push(a):"addEventListener"in b?b.addEventListener("load",a,!1):b.attachEvent("onload",a)})(function(){"FB"in b&&"Event"in FB&&"subscribe"in FB.Event&&(FB.Event.subscribe("edge.create",function(a){_gaq.push(["_trackSocial","facebook","like",a])}),FB.Event.subscribe("edge.remove",function(a){_gaq.push(["_trackSocial","facebook","unlike",a])}),FB.Event.subscribe("message.send",function(a){_gaq.push(["_trackSocial","facebook","send",a])}));"twttr"in b&&"events"in twttr&&"bind"in twttr.events&&twttr.events.bind("tweet",function(a){if(a){var b;if(a.target&&a.target.nodeName=="IFRAME")a:{if(a=a.target.src){a=a.split("#")[0].match(/[^?=&]+=([^&]*)?/g);b=0;for(var c;c=a[b];++b)if(c.indexOf("url")===0){b=unescape(c.split("=")[1]);break a}}b=void 0}_gaq.push(["_trackSocial","twitter","tweet",b])}})})})(window);
        /* ]]> */
    </script>

    <link rel="stylesheet" type="text/css" href="/components/toastr/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="/components/artDialog/css/ui-dialog.css">
    <link rel="stylesheet" type="text/css" href="/css/admin.css"/>
    <script src="/components/toastr/toastr.min.js"></script>
    <script src="/components/artDialog/dist/dialog-min.js"></script>

    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>


<div id="theme-wrapper">
    <?php $this->beginContent('@app/views/layouts/admin_header.php');?>
    <?php $this->endContent();?>
    <div id="page-wrapper" class="container">
        <div class="row">
            <?php $this->beginContent('@app/views/layouts/admin_menu_bar.php');?>
            <?php $this->endContent();?>
            <div id="content-wrapper">
                <?= $content ?>
                <?php $this->beginContent('@app/views/layouts/admin_bottom.php');?>
                <?php $this->endContent();?>
            </div>
        </div>
    </div>
</div>
<?php $this->beginContent('@app/views/layouts/admin_layout_options.php');?>
<?php $this->endContent();?>

<script src="/components/centaurus/js/demo-skin-changer.js"></script>
<script src="/components/centaurus/js/bootstrap.js"></script>
<script src="/components/centaurus/js/jquery.nanoscroller.min.js"></script>
<script src="/components/centaurus/js/demo.js"></script>

<script src="/components/centaurus/js/jquery-ui.custom.min.js"></script>
<script src="/components/centaurus/js/fullcalendar.min.js"></script>
<script src="/components/centaurus/js/jquery.slimscroll.min.js"></script>
<script src="/components/centaurus/js/raphael-min.js"></script>
<script src="/components/centaurus/js/morris.min.js"></script>
<script src="/components/centaurus/js/moment.min.js"></script>
<script src="/components/centaurus/js/daterangepicker.js"></script>
<script src="/components/centaurus/js/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/components/centaurus/js/jquery-jvectormap-world-merc-en.js"></script>
<script src="/components/centaurus/js/gdp-data.js"></script>
<script src="/components/centaurus/js/flot/jquery.flot.js"></script>
<script src="/components/centaurus/js/flot/jquery.flot.min.js"></script>
<script src="/components/centaurus/js/flot/jquery.flot.pie.min.js"></script>
<script src="/components/centaurus/js/flot/jquery.flot.stack.min.js"></script>
<script src="/components/centaurus/js/flot/jquery.flot.resize.min.js"></script>
<script src="/components/centaurus/js/flot/jquery.flot.time.min.js"></script>
<script src="/components/centaurus/js/flot/jquery.flot.threshold.js"></script>
<script src="/components/centaurus/js/jquery.countTo.js"></script>

<script src="/components/centaurus/js/scripts.js"></script>
<script src="/components/centaurus/js/pace.min.js"></script>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
