<?php
namespace Workerman\Memcache;

class Memcache{

    private $memcache;

    public function __construct(){
        $host = '127.0.0.1';
        $port = 11211;
        $memcache = new \Memcache();
        $memcache->pconnect($host,$port);
        $this->memcache = $memcache;
    }


    public function getUserInfo($id){
        $userInfo = $this->memcache->get('user_'.$id);
        $this->memcache->close();
        return $userInfo;
    }

}
?>