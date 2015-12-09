<?php
use yii\helpers\Url;

$this->registerJsFile('/components/socket/swfobject.js');
$this->registerJsFile('/components/socket/web_socket.js');
$this->registerJsFile('/components/socket/chatRoom.js');
//$this->registerJsFile('/components/socket/chat-room.js');

$csrf = Yii::$app->request->getCsrfToken();
$init_data = json_encode(array(
    '_csrf'=>$csrf,
    'uid'=>Yii::$app->user->identity->getId(),
    'room_id'=>'1',
));
?>

<body onload=connect(<?= $init_data ?>) >
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="<?= Url::to('/admin/manage')?>">控制台</a></li>
                        <li class="active"><span>聊天室</span></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <div class="main-box clearfix users-contents">
                <header class="main-box-header clearfix">
                    <h2>聊天信息</h2>
                </header>
                <div class="main-box-body clearfix">
                    <div class="conversation-wrapper">
                        <div class="conversation-content">
                            <div class="conversation-inner">
<!--                                --><?//= $this->render('contents');?>
                            </div>
                        </div>
                        <div class="conversation-new-message">
                            <div class="form-group">
                                <textarea class="form-control all-content" rows="2" placeholder="Enter your message..."></textarea>
                            </div>
                            <div class="clearfix">
                                <button type="submit" class="btn btn-success pull-right all-submit">发送给所有人</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-3  user-list">
            <div class="main-box clearfix">
                <header class="main-box-header clearfix">
                    <h2 class="text-center">在线用户</h2>
                </header>
                <div class="main-box-body clearfix">
                    <div class="conversation-wrapper">
                        <div class="conversation-content">
                            <div class="conversation-inner" id="chat-users">
<!--                                --><?//= $this->render('users');?>
                            </div>
                        </div>
                        <div class="conversation-new-message">
                            <div class="form-group">
                                <textarea class="form-control private-contents" rows="2" placeholder="Enter your message..."></textarea>
                            </div>
                            <div class="clearfix">
                                <span class="private-name"></span>
                                <button type="submit" class="btn btn-success pull-right private-submit">发送私密信息</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>



