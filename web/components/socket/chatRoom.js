var init_data = {};

if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
WEB_SOCKET_SWF_LOCATION = "swf/WebSocketMain.swf";
WEB_SOCKET_DEBUG = true;
var ws, client_list={};

// 连接服务端
function connect(init) {
    init_data = init;
    // 创建websocket
    ws = new WebSocket("ws://"+document.domain+":7272");
    // 当socket连接打开时，发送登陆数据
    ws.onopen = onopen;
    // 当有消息时根据消息类型显示不同信息
    ws.onmessage = onmessage;
    ws.onclose = function() {
        console.log("连接关闭，定时重连");
        connect();
    };
    ws.onerror = function() {
        console.log("出现错误");
    };
}

// 连接建立时发送登录信息
function onopen()
{
    // 登录
    var login_data = '{"type":"login", "uid":"'+init_data.uid+'", "room_id":"'+init_data.room_id+'"}';
    console.log("websocket握手成功，发送登录数据:"+login_data);
    ws.send(login_data);
}




// 服务端发来消息时
function onmessage(e)
{
    var data = eval("("+e.data+")");
    console.log(data);
    switch(data['type']){
        // 服务端ping客户端
        case 'ping':
            ws.send('{"type":"pong"}');
            break;;
        // 登录 更新用户列表
        case 'login':
            //{"type":"login","client_id":xxx,"client_name":"xxx","client_list":"[...]","time":"xxx"}
            say(data['type'],data['uid'],data['client_id'], data['head_portrait'], data['client_name'], data['client_name']+' 加入了聊天室', data['time']);
            if(data['client_list'])
            {
                client_list = data['client_list'];
            }
            else
            {
                var obj = new Object();
                obj['uid'] = data['uid'];
                obj['client_name'] = data['client_name'];
                obj['head_portrait'] = data['head_portrait'];
                client_list[data['client_id']] = obj;
            }
            flush_client_list();
            console.log(data['client_name']+"登录成功");
            break;
        // 用户已登陆
        case 'exist':
            var d = dialog({
                title: '警告',
                content: '您已经加入聊天室了!',
                fixed: true,
                ok: function () {
                    var that = this;
                    this.title('页面即将跳转..');
                    setTimeout(function () {
                        that.close().remove();
                        self.location = '/';
                    }, 2000);
                    return false;
                },
                cancel: function () {
                    alert('不许关闭');
                    return false;
                }
            });
            d.show();
            break;
        // 发言
        case 'say':
            //{"type":"say","from_client_id":xxx,"to_client_id":"all/client_id","content":"xxx","time":"xxx"}
            say(data['type'],data['send_uid'],data['from_client_id'], data['head_portrait'], data['from_client_name'], data['content'], data['time']);
            break;
        // 用户退出 更新用户列表
        case 'logout':
            //{"type":"logout","client_id":xxx,"time":"xxx"}
            console.log(data);
            say(data['type'],data['uid'],data['from_client_id'], data['head_portrait'], data['from_client_name'], data['from_client_name']+' 退出了', data['time']);
            delete client_list[data['from_client_id']];
            flush_client_list();
    }
}

// 提交对话
function onSubmit() {
    var input = document.getElementById("textarea");
    var to_client_id = $("#client_list option:selected").attr("value");
    var to_client_name = $("#client_list option:selected").text();
    ws.send('{"type":"say","to_client_id":"'+to_client_id+'","to_client_name":"'+to_client_name+'","content":"'+input.value+'"}');
    input.value = "";
    input.focus();
}

// 刷新用户列表框
function flush_client_list(){
    var userlist_window = $("#chat-users");
    userlist_window.empty();
    for(var p in client_list){
        var html =
            '<div class="conversation-item item-left online-user clearfix">' +
                '<div class="conversation-user">' +
                    '<img src="'+client_list[p]['head_portrait']+'" alt="">' +
                '</div>' +
                '<div id="'+p+'" data-id="'+client_list[p]['uid']+'" class="user-name">'+client_list[p]['client_name']+'</div>' +
            '</div>';
        userlist_window.append(html);
    }
}

// 发言
function say(type, send_uid, from_client_id, from_head_portrait, from_client_name, content, time){
    if(send_uid == init_data.uid){
        var class_name = 'conversation-item item-right clearfix';
    }else{
        var class_name = 'conversation-item item-left clearfix';
    }
    var html =
        '<div class="'+class_name+'">' +
            '<div class="conversation-user">' +
                '<img src="'+from_head_portrait+'" alt="">' +
            '</div>' +
            '<div class="conversation-body">' +
                '<div class="name"> '+from_client_name+' </div>' +
                '<div class="time hidden-xs"> '+time+' </div>' +
                '<div class="text">'+content+'</div>' +
            '</div>' +
        '</div>';

    if(type == 'login' || type =='logout' ){
        html = systemMessage(content);
    }

    $(".users-contents .conversation-inner").append(html);
    $('.users-contents .conversation-inner').slimScroll({ scrollBy: $(document).height()+'px' });
}

//系统消息
function systemMessage(content){
    var html =
        '<div class="system-message">' +
            '<span>'+content+'</span>' +
        '</div>';
    return html;
}

$(document).ready(function(){
    $('.conversation-inner').slimScroll({
        height: '600px',
        alwaysVisible: true,
        railVisible: true,
        wheelStep: 5,
        allowPageScroll: false,
        start: 'bottom'
    });

    /*
    * 发送所有人 提交对话
    * */
    $(".all-submit").click(function(){
        var input = $(".all-content");
        var to_client_id = 'all';
        var to_client_name = '所有人';
        ws.send('{"type":"say","send_uid":"'+init_data.uid+'","to_client_id":"'+to_client_id+'","to_client_name":"'+to_client_name+'","content":"'+input.val()+'"}');
        input.val("");
        input.focus();
    })

    /*
    * @
    * */

    $('body').on('click',".online-user",function(){
        var user = $(this).find('.user-name');
        var to_client_id = user.attr('id');
        var to_client_name = user.text();
        $(".private-name").attr('name',to_client_name);
        $(".private-name").attr('data-id',user.attr('data-id'));
        $(".private-name").attr('to_client_id',to_client_id);
        $(".private-name").text('@'+to_client_name);
    })

    /*
    * @ 私聊
    * */

    $(".private-submit").click(function(){
        var user = $('.private-name');
        var uid = user.attr('data-id');
        var name = user.attr('name');
        var to_client_id = user.attr('to_client_id');
        var to_client_name = user.text();
        var input = $(".private-contents");
        if(uid==init_data.uid){
            input.val("");
            input.focus();
            toastr.error('不能@自己哦');
            return;
        }
        if(!input.val()){
            return
        }
        if(uid && name && to_client_id && to_client_name){
            ws.send('{"type":"say","send_uid":"'+init_data.uid+'","to_uid":"'+uid+'","to_client_id":"'+to_client_id+'","to_client_name":"'+name+'","content":"'+input.val()+'"}');
            input.val("");
            input.focus();
        }
    })

    function CloseWebPage() {
        if (navigator.userAgent.indexOf("MSIE") > 0) {
            if (navigator.userAgent.indexOf("MSIE 6.0") > 0) {
                window.opener = null; window.close();
            }
            else {
                window.open('', '_top'); window.top.close();
            }
        }else if (navigator.userAgent.indexOf("Firefox") > 0) {
            window.location.href = 'about:blank '; //火狐默认状态非window.open的页面window.close是无效的
            //window.history.go(-2);
        }else {
            window.opener = null;
            window.open('', '_self', '');
            window.close();
        }
    }

 });