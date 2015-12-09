<?php

/* @var $this yii\web\View */
$this->title = '关于我们';

$this->registerCssFile('/components/swiper/dist/css/swiper.min.css');
$this->registerCssFile('/components/swiper/dist/css/animate.min.css');
$this->registerJsFile('/components/swiper/dist/js/swiper.min.js');
$this->registerJsFile('/components/swiper/dist/js/swiper.animate1.0.2.min.js');

$script = <<< JS
$(document).ready(function(){
    var header = new Headroom(document.querySelector("header"), {
        tolerance: 5,
        offset : 205,
        classes: {
            initial: "animated",
            pinned: "slideDown",
            unpinned: "slideUp"
        }
    });
    header.init();
});
JS;
$this->registerJs($script);
?>

<body class="home-body">
<div class="container welcome-box">
    <h1>
        <a href="/">Welcome to aiyeyun.com</a>
    </h1>
    <p>Please don't say me handsome</p>
    <a class="go" href="/">Go</a>
</div>

<div class="container service-box">
    <section>
        <header>
            <h2>Gentlemen, behold! This is <strong>Strongly Typed</strong>!</h2>
        </header>
    </section>
    <div class="row">
        <div class="col-lg-4">
            <section>
                <a href="#" class="image featured"><img src="/images/pic01.jpg" alt=""></a>
                <header>
                    <h3>Please don't say me handsome</h3>
                </header>
                <p>我知道你很违背自己的良心..不过没关系,好吧.你随意.但咱们还是低调点 Thank you</p>
            </section>
        </div>
        <div class="col-lg-4">
            <section>
                <a href="#" class="image featured"><img src="/images/pic02.jpg" alt=""></a>
                <header>
                    <h3>Please don't say me handsome</h3>
                </header>
                <p>我知道你很违背自己的良心..不过没关系,好吧.你随意.但咱们还是低调点 Thank you</p>
            </section>
        </div>
        <div class="col-lg-4">
            <section>
                <a href="#" class="image featured"><img src="/images/pic03.jpg" alt=""></a>
                <header>
                    <h3>Please don't say me handsome</h3>
                </header>
                <p>我知道你很违背自己的良心..不过没关系,好吧.你随意.但咱们还是低调点 Thank you</p>
            </section>
        </div>
    </div>
</div>

<div class="banner-wrapper">
    <div class="inner">
        <section class="container banner">
            <p>Use this space for <strong>profound thoughts</strong>.<br>
                Or an enormous ad. Whatever.</p>
        </section>
    </div>
</div>
</body>

<?//= $this->render('/base/cropper') ?><!--<br/>-->



