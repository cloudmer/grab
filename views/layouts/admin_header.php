<?php
    use yii\helpers\Url;
?>
<header class="navbar" id="header-navbar">
    <div class="container">
        <a href="index.html" id="logo" class="navbar-brand">
            <img src="/components/centaurus/img/logo.png" alt="" class="normal-logo logo-white"/>
            <img src="/components/centaurus/img/logo-black.png" alt="" class="normal-logo logo-black"/>
            <img src="/components/centaurus/img/logo-small.png" alt="" class="small-logo hidden-xs hidden-sm hidden"/>
        </a>
        <div class="clearfix">
            <button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
            </button>
            <div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
                <ul class="nav navbar-nav pull-left">
                    <li>
                        <a class="btn" id="make-small-nav">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="nav-no-collapse pull-right" id="header-nav">
                <ul class="nav navbar-nav pull-right">
                    <li class="mobile-search">
                        <a class="btn">
                            <i class="fa fa-search"></i>
                        </a>
                        <div class="drowdown-search">
                            <form role="search">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Search...">
                                    <i class="fa fa-search nav-search-icon"></i>
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="dropdown hidden-xs">
                        <a class="btn dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-warning"></i>
                            <span class="count">8</span>
                        </a>
                        <ul class="dropdown-menu notifications-list">
                            <li class="pointer">
                                <div class="pointer-inner">
                                    <div class="arrow"></div>
                                </div>
                            </li>
                            <li class="item-header">You have 6 new notifications</li>
                            <li class="item">
                                <a href="#">
                                    <i class="fa fa-comment"></i>
                                    <span class="content">New comment on 窶連wesome P...</span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="#">
                                    <i class="fa fa-plus"></i>
                                    <span class="content">New user registration</span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="#">
                                    <i class="fa fa-envelope"></i>
                                    <span class="content">New Message from George</span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="content">New purchase</span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="#">
                                    <i class="fa fa-eye"></i>
                                    <span class="content">New order</span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item-footer">
                                <a href="#">
                                    View all notifications
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown hidden-xs">
                        <a class="btn dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="count">16</span>
                        </a>
                        <ul class="dropdown-menu notifications-list messages-list">
                            <li class="pointer">
                                <div class="pointer-inner">
                                    <div class="arrow"></div>
                                </div>
                            </li>
                            <li class="item first-item">
                                <a href="#">
                                    <img src="/components/centaurus/img/samples/messages-photo-1.png" alt=""/>
                                        <span class="content">
                                            <span class="content-headline">George Clooney</span>
                                            <span class="content-text">
                                            Look, just because I don't be givin' no man a foot massage don't make it
                                            right for Marsellus to throw...
                                            </span>
                                        </span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="#">
                                    <img src="/components/centaurus/img/samples/messages-photo-2.png" alt=""/>
                                        <span class="content">
                                            <span class="content-headline">Emma Watson</span>
                                            <span class="content-text">
                                                Look, just because I don't be givin' no man a foot massage don't make it
                                                right for Marsellus to throw...
                                            </span>
                                        </span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item">
                                <a href="#">
                                    <img src="/components/centaurus/img/samples/messages-photo-3.png" alt=""/>
                                        <span class="content">
                                            <span class="content-headline">Robert Downey Jr.</span>
                                            <span class="content-text">
                                                Look, just because I don't be givin' no man a foot massage don't make it
                                                right for Marsellus to throw...
                                            </span>
                                        </span>
                                    <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                </a>
                            </li>
                            <li class="item-footer">
                                <a href="#">
                                    View all messages
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="hidden-xs">
                        <a class="btn">
                            <i class="fa fa-cog"></i>
                        </a>
                    </li>
                    <li class="dropdown profile-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= Yii::$app->user->identity->head_portrait ?>" alt=""/>
                            <span class="hidden-xs"><?= Yii::$app->user->identity->nick_name ? Yii::$app->user->identity->nick_name : Yii::$app->user->identity->username ?></span> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="user-profile.html"><i class="fa fa-user"></i>简介</a></li>
                            <li><a href="#"><i class="fa fa-cog"></i>设置</a></li>
                            <li><a href="#"><i class="fa fa-envelope-o"></i>消息</a></li>
                            <li><a href="<?= Url::to('/admin/login/logout')?>"><i class="fa fa-power-off"></i>退出</a></li>
                        </ul>
                    </li>
                    <li class="hidden-xxs">
                        <a class="btn" href="<?= Url::to('/admin/login/logout')?>">
                            <i class="fa fa-power-off"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>