<?php
namespace app\components;

class Mem{

    private $memcache;

    public function __construct(){
        $host = '127.0.0.1';
        $port = 11211;
        $memcache = new \Memcache();
        $memcache->pconnect($host,$port);
        $this->memcache = $memcache;
    }

    /*
     * 用户登陆 记录用户登陆信息到缓存服务器中
     * */
    function setUserInfo(){
        $user = \Yii::$app->user->identity;
        $this->memcache->set('user_'.$user->id,$user->attributes);
        $this->memcache->close();
    }

    /*
     * 得到 当前登陆用户信息
     * */
    function getUserInfo(){
        $userInfo = $this->memcache->get('user_'.\Yii::$app->user->identity->getId());
        $this->memcache->close();
        return $userInfo;
    }

    /*
     * 用户退出登录 删除记录
     * */
    function delUserInfo(){
        $this->memcache->delete('user_'.\Yii::$app->user->identity->getId());
        $this->memcache->close();
    }
}
?>