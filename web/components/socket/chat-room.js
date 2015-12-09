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
    switch(data['type']){
        // 服务端ping客户端
        case 'ping':
            ws.send('{"type":"pong"}');
            break;;
        // 登录 更新用户列表
        case 'login':
            console.log(data);
            return;
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