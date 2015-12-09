<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 聊天主逻辑
 * 主要是处理 onMessage onClose
 */
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use Workerman\Memcache\Memcache;

class Event
{

    /**
     * 有消息时
     * @param int $client_id
     * @param string $message
     */
    public static function onMessage($client_id, $message)
    {
        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:".json_encode($_SESSION)." onMessage:".$message."\n";
        // 客户端传递的是json数据
        $message_data = json_decode($message, true);
        if(!$message_data)
        {
            return ;
        }

        // 根据类型执行不同的业务
        switch($message_data['type'])
        {
            // 客户端回应服务端的心跳
            case 'pong':
                return;
            // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端，广播给所有客户端xx进入聊天室
            case 'login':
                $event = new Event();
                $event->login($client_id,$message_data);
                break;

                // 判断是否有房间号
                if(!isset($message_data['room_id']))
                {
                    throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }
                $uid = $message_data['uid'];
                $room_id = $message_data['room_id'];
                $_SESSION['room_id'] = $room_id;
                //从缓存服务器中取出当前登陆用户的信息
                $memcache = new Memcache();
                $userInfo = $memcache->getUserInfo($uid);
                $_SESSION['userInfo'] = $userInfo;
                $_SESSION['client_name'] = $userInfo['nick_name'] ? $userInfo['nick_name'] : $userInfo['username'];
                // 获取房间内所有用户列表
                $clients_list = Gateway::getClientInfoByGroup($room_id);
                foreach($clients_list as $tmp_client_id=>$item)
                {
                    $clients_list[$tmp_client_id] = [
                        'uid'=>$item['userInfo']['id'],
                        'client_name'=>$item['userInfo']['nick_name'] ? $item['userInfo']['nick_name'] : $item['userInfo']['username'],
                        'head_portrait'=>$item['userInfo']['head_portrait']
                    ];
                }
                $clients_list[$client_id] = [
                    'uid'=>$userInfo['id'],
                    'client_name'=>$userInfo['nick_name'] ? $userInfo['nick_name'] : $userInfo['username'],
                    'head_portrait'=>$userInfo['head_portrait']
                ];
                if(count(Gateway::getClientIdByUid($uid)) != 0 ){
                    // 用户已在房间内
                    $_SESSION['client_name'] = null;
                    Gateway::sendToCurrentClient(json_encode(array('type'=>'exist')));
                    return;
                }
                // 转播给当前房间的所有客户端，xx进入聊天室 message {type:login, client_id:xx, name:xx}
                $new_message = array('type'=>$message_data['type'], 'client_id'=>$client_id, 'uid'=>$uid,'head_portrait'=>$userInfo['head_portrait'], 'client_name'=>$_SESSION['client_name'], 'time'=>date('Y-m-d H:i:s'));
                Gateway::sendToGroup($room_id, json_encode($new_message));
                Gateway::joinGroup($client_id, $room_id);
                Gateway::bindUid($client_id,$uid);


                // 给当前用户发送用户列表
                $new_message['client_list'] = $clients_list;
                Gateway::sendToCurrentClient(json_encode($new_message));
                return;

            // 客户端发言 message: {type:say, to_client_id:xx, content:xx}
            case 'say':
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
                //从缓存服务器中取出用户信息
                $memcache = new Memcache();
                $userInfo = $memcache->getUserInfo($message_data['send_uid']);
                $client_head_portrait = $userInfo['head_portrait'];

                // 私聊
                if($message_data['to_client_id'] != 'all')
                {
                    $new_message = array(
                        'send_uid'=>$message_data['send_uid'],
                        'to_uid'=>$message_data['to_uid'],
                        'head_portrait'=>$client_head_portrait,
                        'type'=>'say',
                        'from_client_id'=>$client_id,
                        'from_client_name' =>$client_name,
                        'to_client_id'=>$message_data['to_client_id'],
                        'content'=>"<b>对你说: </b>".nl2br(htmlspecialchars($message_data['content'])),
                        'time'=>date('Y-m-d H:i:s'),
                    );
                    Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
                    $new_message['content'] = "<b>你对".htmlspecialchars($message_data['to_client_name'])."说@: </b>".nl2br(htmlspecialchars($message_data['content']));
                    return Gateway::sendToCurrentClient(json_encode($new_message));
                }

                $new_message = array(
                    'send_uid'=>$message_data['send_uid'],
                    'head_portrait'=>$client_head_portrait,
                    'type'=>'say',
                    'from_client_id'=>$client_id,
                    'from_client_name' =>$client_name,
                    'to_client_id'=>'all',
                    'content'=>nl2br(htmlspecialchars($message_data['content'])),
                    'time'=>date('Y-m-d H:i:s'),
                );
                return Gateway::sendToGroup($room_id ,json_encode($new_message));
        }
    }

    /**
     * 当客户端断开连接时
     * @param integer $client_id 客户端id
     */
    public static function onClose($client_id)
    {
        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";

        // 从房间的客户端列表中删除
        if(isset($_SESSION['room_id']) && isset($_SESSION['client_name']))
        {
            $room_id = $_SESSION['room_id'];
            $new_message = array('type'=>'logout', 'from_client_id'=>$client_id, 'from_client_name'=>$_SESSION['client_name'], 'time'=>date('Y-m-d H:i:s'));
            Gateway::sendToGroup($room_id, json_encode($new_message));
        }
    }

    /*
     * 客户端登陆
     * */
    private function login($client_id,$message_data){
        // 判断是否有房间号
        if(!isset($message_data['room_id'])) {
            throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
        }
        //获取用户客户端登陆的身份
        $uid = $message_data['uid'];

        $room_id = $message_data['room_id']; //获取用户提交上来的 房间号
        $_SESSION['room_id'] = $room_id; //将房间号存入 session
        $memcache = new Memcache(); //从缓存服务器中取出当前登陆用户的信息
        $userInfo = $memcache->getUserInfo($uid); //从缓存服务器
        $_SESSION['userInfo'] = $userInfo; //把缓存数据 存进session 绑定当前连接用户
        $_SESSION['client_name'] = $userInfo['nick_name'] ? $userInfo['nick_name'] : $userInfo['username'];
        // 获取房间内所有用户列表
        $clients_list = Gateway::getClientInfoByGroup($room_id);

        foreach($clients_list as $tmp_client_id=>$item) {
            $clients_list[$tmp_client_id] = [
                'uid'=>$item['userInfo']['id'],
                'client_name'=>$item['userInfo']['nick_name'] ? $item['userInfo']['nick_name'] : $item['userInfo']['username'],
                'head_portrait'=>$item['userInfo']['head_portrait']
            ];
        }

        $clients_list[$client_id] = [
            'uid'=>$userInfo['id'],
            'client_name'=>$userInfo['nick_name'] ? $userInfo['nick_name'] : $userInfo['username'],
            'head_portrait'=>$userInfo['head_portrait']
        ];

        $id = Gateway::getClientIdByUid($uid); //获取id 绑定下的 client_id  1对多关系
        if(empty($id)){ //新用户登陆
            // 转播给当前房间的所有客户端，xx进入聊天室 message {type:login, client_id:xx, name:xx}
            $new_message = array('type'=>$message_data['type'], 'client_id'=>$client_id, 'uid'=>$uid,'head_portrait'=>$userInfo['head_portrait'], 'client_name'=>$_SESSION['client_name'], 'time'=>date('Y-m-d H:i:s'));
            Gateway::sendToGroup($room_id, json_encode($new_message)); // 发送所有人
            Gateway::joinGroup($client_id, $room_id); //将当前连接人 添加到房间内
            Gateway::bindUid($client_id,$uid); //将当前连接人 绑定用户id 1对多关系
        }else{
            //页面多开
            Gateway::bindUid($client_id,$uid); //将当前连接人 绑定用户id 1对多关系
        }

        // 给当前用户发送用户列表
        $new_message['client_list'] = $clients_list;
        Gateway::sendToCurrentClient(json_encode($new_message));
        return;

    }

}
