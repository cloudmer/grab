<?php
    use app\models\Menus;

    function active($controller,$action=false,$open=false){
        if(($controller && !$action) && $controller == '/'.Yii::$app->controller->id){
            return $open ? 'open active' : 'active';
        }
        if($action && $action == Yii::$app->controller->action->id && $controller == '/'.Yii::$app->controller->id){
            return 'active';
        }
    }
?>
<div id="nav-col">
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">
            <div id="user-left-box" class="clearfix hidden-sm hidden-xs">
                <img alt="" src="<?= Yii::$app->user->identity->head_portrait ?>"/>
                <div class="user-box">
                    <span class="name">
                        Welcome<br/>
                        <?= Yii::$app->user->identity->nick_name ? Yii::$app->user->identity->nick_name : Yii::$app->user->identity->username ?>
                    </span>
                    <span class="status">
                        <i class="fa fa-circle"></i> Online
                    </span>
                </div>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">

                <ul class="nav nav-pills nav-stacked">

                    <?php foreach(Menus::menuSub(1) as $menu):?>
                        <?php if($menu->state): ?>
                        <?php $menu2 = Menus::menuSub(2,$menu->id) ?>
                        <li class="<?= active($menu->controller);?>">
                            <a href="<?= $menu->controller.'/'.$menu->action ?>" class="<?= $menu2 ? 'dropdown-toggle' : null ?>">
                                <i class="<?= $menu->icon ?>"></i>
                                <span><?= $menu->name?></span>
                                <?php if(!$menu2):?>
                                    <span class="label label-success pull-right">New</span>
                                <?php else:?>
                                    <i class="fa fa-chevron-circle-right drop-icon"></i>
                                <?php endif?>
                            </a>
                            <!-- 二级菜单栏 start -->
                            <?php if($menu2):?>
                                <ul class="submenu">
                                    <?php foreach($menu2 as $menu):?>
                                    <?php if($menu->state): ?>
                                    <?php $menu3 = Menus::menuSub(3,$menu->id) ?>
                                        <li>
                                            <script>
                                                <?php if(active($menu->controller,$menu->action)):?>
                                                    $(function(){
                                                        var a = $('a[href="<?= $menu->controller.'/'.$menu->action ?>"]');
                                                        a.parent().parent().parent().addClass('open active');
                                                    })
                                                <?php endif ?>
                                            </script>
                                            <a class="<?= active($menu->controller,$menu->action)?> <?= $menu3 ? 'dropdown-toggle' : null ?>" href="<?= $menu->controller.'/'.$menu->action?>">
                                                <?= $menu->name ?>
                                                <?php if($menu3):?>
                                                    <i class="fa fa-chevron-circle-right drop-icon"></i>
                                                <?php endif?>
                                            </a>
                                            <!-- 三级菜单栏 start -->
                                                <?php if($menu3):?>
                                                    <ul class="submenu">
                                                        <?php foreach($menu3 as $menu):?>
                                                        <?php if($menu->state): ?>
                                                            <li>
                                                                <script>
                                                                    <?php if(active($menu->controller,$menu->action)):?>
                                                                        $(function(){
                                                                            var a = $('a[href="<?= $menu->controller.'/'.$menu->action ?>"]');
                                                                            a.parent().parent().parent().parent().parent().addClass('open active');
                                                                            a.parent().parent().parent().addClass('open active');
                                                                        })
                                                                    <?php endif ?>
                                                                </script>
                                                                <a class="<?= active($menu->controller,$menu->action,true)?>" href="<?= $menu->controller.'/'.$menu->action?>">
                                                                    <?= $menu->name ?>
                                                                </a>
                                                            </li>
                                                            <?php endif ?>
                                                        <?php endforeach ?>
                                                    </ul>
                                                <?php endif ?>
                                            <!-- 三级菜单栏 end -->
                                        </li>
                                        <?php endif?>
                                    <?php endforeach ?>
                                </ul>
                            <?php endif ?>
                            <!-- 二级菜单栏 end -->
                        </li>
                        <?php endif?>
                    <?php endforeach ?>
                    <!--
                    <br/>

                    <li>
                        <a href="#">
                            <i class="fa fa-dashboard"></i>
                            <span>控制台</span>
                            <span class="label label-info label-circle pull-right">28</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-table"></i>
                            <span>Tables</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="tables.html">
                                    Simple
                                </a>
                            </li>
                            <li>
                                <a href="tables-advanced.html">
                                    Advanced
                                </a>
                            </li>
                            <li>
                                <a href="users.html">
                                    Users
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-envelope"></i>
                            <span>Email</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="email-inbox.html">
                                    Inbox
                                </a>
                            </li>
                            <li>
                                <a href="email-detail.html">
                                    Detail
                                </a>
                            </li>
                            <li>
                                <a href="email-compose.html">
                                    Compose
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-bar-chart-o"></i>
                            <span>Graphs</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="graphs-morris.html">
                                    Morris &amp; Mixed
                                </a>
                            </li>
                            <li>
                                <a href="graphs-flot.html">
                                    Flot
                                </a>
                            </li>
                            <li>
                                <a href="graphs-dygraphs.html">
                                    Dygraphs
                                </a>
                            </li>
                            <li>
                                <a href="graphs-xcharts.html">
                                    xCharts
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="widgets.html">
                            <i class="fa fa-th-large"></i>
                            <span>Widgets</span>
                            <span class="label label-success pull-right">New</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-desktop"></i>
                            <span>Pages</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="calendar.html">
                                    Calendar
                                </a>
                            </li>
                            <li>
                                <a href="gallery.html">
                                    Gallery
                                </a>
                            </li>
                            <li>
                                <a href="gallery-v2.html">
                                    Gallery v2
                                </a>
                            </li>
                            <li>
                                <a href="pricing.html">
                                    Pricing
                                </a>
                            </li>
                            <li>
                                <a href="projects.html">
                                    Projects
                                </a>
                            </li>
                            <li>
                                <a href="team-members.html">
                                    Team Members
                                </a>
                            </li>
                            <li>
                                <a href="timeline.html">
                                    Timeline
                                </a>
                            </li>
                            <li>
                                <a href="timeline-grid.html">
                                    Timeline Grid
                                </a>
                            </li>
                            <li>
                                <a href="user-profile.html">
                                    User Profile
                                </a>
                            </li>
                            <li>
                                <a href="search.html">
                                    Search Results
                                </a>
                            </li>
                            <li>
                                <a href="invoice.html">
                                    Invoice
                                </a>
                            </li>
                            <li>
                                <a href="intro.html">
                                    Tour Layout
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-edit"></i>
                            <span>Forms</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="form-elements.html">
                                    Elements
                                </a>
                            </li>
                            <li>
                                <a href="x-editable.html">
                                    X-Editable
                                </a>
                            </li>
                            <li>
                                <a href="form-wizard.html">
                                    Wizard
                                </a>
                            </li>
                            <li>
                                <a href="form-wizard-popup.html">
                                    Wizard popup
                                </a>
                            </li>
                            <li>
                                <a href="form-wysiwyg.html">
                                    WYSIWYG
                                </a>
                            </li>
                            <li>
                                <a href="form-summernote.html">
                                    WYSIWYG Summernote
                                </a>
                            </li>
                            <li>
                                <a href="form-ckeditor.html">
                                    WYSIWYG CKEditor
                                </a>
                            </li>
                            <li>
                                <a href="form-dropzone.html">
                                    Multiple File Upload
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-desktop"></i>
                            <span>UI Kit</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="ui-elements.html">
                                    Elements
                                </a>
                            </li>
                            <li>
                                <a href="notifications.html">
                                    Notifications &amp; Alerts
                                </a>
                            </li>
                            <li>
                                <a href="modals.html">
                                    Modals
                                </a>
                            </li>
                            <li>
                                <a href="video.html">
                                    Video
                                </a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-toggle">
                                    Icons
                                    <i class="fa fa-chevron-circle-right drop-icon"></i>
                                </a>
                                <ul class="submenu">
                                    <li>
                                        <a href="icons-awesome.html">
                                            Awesome Icons
                                        </a>
                                    </li>
                                    <li>
                                        <a href="icons-halflings.html">
                                            Halflings Icons
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="ui-nestable.html">
                                    Nestable List
                                </a>
                            </li>
                            <li>
                                <a href="typography.html">
                                    Typography
                                </a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-toggle">
                                    3 Level Menu
                                    <i class="fa fa-chevron-circle-right drop-icon"></i>
                                </a>
                                <ul class="submenu">
                                    <li>
                                        <a href="#">
                                            3rd Level
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            3rd Level
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            3rd Level
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="maps.html">
                            <i class="fa fa-map-marker"></i>
                            <span>Google Maps</span>
                            <span class="label label-danger pull-right">Updated</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-file-text-o"></i>
                            <span>Extra pages</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="faq.html">
                                    FAQ
                                </a>
                            </li>
                            <li>
                                <a href="emails.html">
                                    Email Templates
                                </a>
                            </li>
                            <li>
                                <a href="login.html">
                                    Login
                                </a>
                            </li>
                            <li>
                                <a href="login-full.html">
                                    Login Full
                                </a>
                            </li>
                            <li>
                                <a href="registration.html">
                                    Registration
                                </a>
                            </li>
                            <li>
                                <a href="registration-full.html">
                                    Registration Full
                                </a>
                            </li>
                            <li>
                                <a href="forgot-password.html">
                                    Forgot Password
                                </a>
                            </li>
                            <li>
                                <a href="forgot-password-full.html">
                                    Forgot Password Full
                                </a>
                            </li>
                            <li>
                                <a href="lock-screen.html">
                                    Lock Screen
                                </a>
                            </li>
                            <li>
                                <a href="lock-screen-full.html">
                                    Lock Screen Full
                                </a>
                            </li>
                            <li>
                                <a href="error-404.html">
                                    Error 404
                                </a>
                            </li>
                            <li>
                                <a href="error-404-v2.html">
                                    Error 404 Nested
                                </a>
                            </li>
                            <li>
                                <a href="error-500.html">
                                    Error 500
                                </a>
                            </li>
                            <li>
                                <a href="extra-grid.html">
                                    Grid
                                </a>
                            </li>
                        </ul>
                    </li>
                    -->
                </ul>
            </div>
        </div>
    </section>
</div>