<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="language" content="en">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="/components/bootstrap-3.3.5-dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/components/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css">
        <link rel="stylesheet" type="text/css" href="/css/login.css">
        <script src="/js/jquery.min.js"></script>
    </head>
    <body>
        <div class="login_header">
            <div class="row">
                <div class="col-xs-6">
                    <span>小蛮牛</span>
                </div>
                <div class="col-xs-6 text-right">
                    <a href="#">产品官网</a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="form">
                <?php $form = ActiveForm::begin([
                    'options'=>['class'=>'form-center'],
                    'action'=>'/admin/login/login',
                    'method'=>'post'])?>
                <h1>小蛮牛后台管理</h1>
                <?= $form->field($model, 'username')->error(false)->label(false)->textInput(['placeholder'=>'账号'])?>
                <?= $form->field($model, 'password')->error(false)->label(false)->passwordInput(['placeholder'=>'密码'])?>
                <div id="msg" class="help-inline">
                    <?php isset($msg) ? var_dump($msg) : null; ?>
                </div>
                <?= Html::submitButton('登陆',['class'=>'btn btn-lg btn-primary btn-block','id'=>'submit'])?>
                <?php ActiveForm::end()?>
            </div>
        </div>
        <footer>
            <div class="row">
                <div class="col-sm-6">
                    <a href="#">关于我们</a>
                    <a href="#">隐私政策</a>
                    <a href="#">帮助中心</a>
                    <a href="#">联系我们</a>
                </div>
                <div class="col-sm-6 text-right">
                    ©Copyright 2015 小蛮牛 All Rights Reserved.
                </div>
            </div>
        </footer>
    </body>
    <script>
        $("#submit").click(function(){
            var username = $("#loginform-username").val();
            var password = $("#loginform-password").val();
            if(!username){
                $("#msg").text('请输入账号');
                $("#msg").slideDown('slow');
                return false;
            }
            if(!password){
                $("#msg").text('请输入密码');
                $("#msg").slideDown('slow');
                return false;
            }
        })
        <?php if(isset($msg)) : ?>
            $("#msg").slideDown('slow');
        <?php endif ?>
    </script>
</html>

