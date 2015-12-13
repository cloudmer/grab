<?php
/* @var $this yii\web\View */

$script = <<< JS
$(document).ready(function(){
    $('.infographic-box .value .timer').countTo({});
});
JS;
$this->registerJs($script);
?>



<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li class="active"><span>控制台</span></li>
                </ol>
                <div class="alert alert-block alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="fa fa-times-circle fa-fw fa-lg"></i>
                    </button>

                    欢迎使用
                    <strong class="green">
                        小蛮牛 后台管理系统
                    </strong>
                    ,千里之行,始于足下.
                    (*^__^*) 欢迎回来
                    &nbsp;<strong class="green"><?= Yii::$app->user->identity->nick_name ? Yii::$app->user->identity->nick_name : Yii::$app->user->identity->username ?></strong>&nbsp;
                    上次登陆时间：&nbsp;<strong class="green"><?= date('Y-m-d H:i:s',Yii::$app->user->identity->login_time) ?></strong>&nbsp;
                    上次登陆IP：&nbsp;<strong class="green"><?= Yii::$app->user->identity->login_ip ?></strong>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="main-box infographic-box">
                    <i class="fa fa-user red-bg"></i>
                    <span class="headline">Users</span>
                    <span class="value">
                        <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">2562</span>
                    </span>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="main-box infographic-box">
                    <i class="fa fa-shopping-cart emerald-bg"></i>
                    <span class="headline">Purchases</span>
                    <span class="value">
                        <span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">658</span>
                    </span>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="main-box infographic-box">
                    <i class="fa fa-money green-bg"></i>
                    <span class="headline">Income</span>
                    <span class="value">
                        &#36;<span class="timer" data-from="83" data-to="8400" data-speed="900" data-refresh-interval="60">8400</span>
                    </span>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="main-box infographic-box">
                    <i class="fa fa-eye yellow-bg"></i>
                    <span class="headline">Monthly Visits</span>
                    <span class="value">
                        <span class="timer" data-from="539" data-to="12526" data-speed="1100">12526</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2>控制台信息 - System Info</h2>
            </header>
            <div class="main-box-body clearfix">
                <div class="table-responsive">
                    <table id="table-example-fixed" class="table table-hover">
                        <tbody>
                            <tr>
                                <td>Night cloud 版本：</td>
                                <td class="text-right"><?= $systemInfo['version']?></td>
                            </tr>
                            <tr>
                                <td>服务器操作系统：</td>
                                <td class="text-right"><?= $systemInfo['server_os']?></td>
                            </tr>
                            <tr>
                                <td>服务器域名/IP：</td>
                                <td class="text-right"><?= $systemInfo['server_domain']?></td>
                            </tr>
                            <tr>
                                <td>服务器环境：</td>
                                <td class="text-right"><?= $systemInfo['web_server']?></td>
                            </tr>
                            <tr>
                                <td>PHP 版本：</td>
                                <td class="text-right"><?= $systemInfo['php_version']?></td>
                            </tr>
                            <tr>
                                <td>Mysql 版本：</td>
                                <td class="text-right"><?= $systemInfo['mysql_version']?></td>
                            </tr>
                            <tr>
                                <td>GD 版本：</td>
                                <td class="text-right"><?= $systemInfo['gd_version']?></td>
                            </tr>
                            <tr>
                                <td>文件上传限制：</td>
                                <td class="text-right"><?= $systemInfo['upload_max_filesize']?></td>
                            </tr>
                            <tr>
                                <td>最大占用内存：</td>
                                <td class="text-right"><?= $systemInfo['memory_limit']?></td>
                            </tr>
                            <tr>
                                <td>最大执行时间：</td>
                                <td class="text-right"><?= $systemInfo['max_execution_time']?></td>
                            </tr>
                            <tr>
                                <td>安全模式：</td>
                                <td class="text-right">
                                    <span class="label <?= $systemInfo['safe_mode'] == 'YES' ? 'label-success' : 'label-warning'?> ">
                                        <?= $systemInfo['safe_mode']?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Zlib支持：</td>
                                <td class="text-right">
                                    <span class="label <?= $systemInfo['zlib'] == 'YES' ? 'label-success' : 'label-warning'?> ">
                                        <?= $systemInfo['zlib']?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Curl支持：</td>
                                <td class="text-right">
                                    <span class="label <?= $systemInfo['curl'] == 'YES' ? 'label-success' : 'label-warning'?> ">
                                        <?= $systemInfo['curl']?>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2>用户信息 - User Info</h2>
            </header>
            <div class="main-box-body clearfix">
                <div class="table-responsive">
                    <table id="table-example-fixed" class="table table-hover">
                        <tbody>
                            <tr>
                                <td>使用者浏览器：</td>
                                <td class="text-right"><?= $systemInfo['user_browser']?></td>
                            </tr>
                            <tr>
                                <td>浏览器语言：</td>
                                <td class="text-right"><?= $systemInfo['user_lang']?></td>
                            </tr>
                            <tr>
                                <td>登陆方式：</td>
                                <td class="text-right"><?= $systemInfo['is_mobil']?></td>
                            </tr>
                            <tr>
                                <td>使用者操作系统：</td>
                                <td class="text-right"><?= $systemInfo['user_os']?></td>
                            </tr>
                            <tr>
                                <td>当前登录Ip：</td>
                                <td class="text-right"><?= $systemInfo['user_ip']?></td>
                            </tr>
                            <tr>
                                <td>最后登录Ip：</td>
                                <td class="text-right"><?= Yii::$app->user->identity->login_ip?></td>
                            </tr>
                            <tr>
                                <td>最后登录时间：</td>
                                <td class="text-right"><?= date('Y-m-d',Yii::$app->user->identity->login_time)?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <header class="main-box-header clearfix">
                <h2>开发团队 - Development Team</h2>
            </header>
            <div class="main-box-body clearfix">
                <div class="table-responsive">
                    <table id="table-example-fixed" class="table table-hover">
                        <tbody>
                            <tr>
                                <td>版权所有：</td>
                                <td class="text-right"><a>小蛮牛个人所有</a></td>
                            </tr>
                            <tr>
                                <td>开发人员：</td>
                                <td class="text-right">夜云</td>
                            </tr>
                            <tr>
                                <td>联系电话：</td>
                                <td class="text-right">152-2888-3771</td>
                            </tr>
                            <tr>
                                <td>联系QQ：</td>
                                <td class="text-right">
                                    359709440
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

