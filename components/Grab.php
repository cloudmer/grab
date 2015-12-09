<?php
namespace app\components;

class Grab{

    private $grab;

    public function __construct($url){
        ini_set('memory_limit','1024M');
        $this->getHtml($url);
        /*
        include_once('simplehtmldom_1_5/simple_html_dom.php');
        $this->grab = new \simple_html_dom();
        $this->grab->clear();
        */
    }

    function getHtml($url,$encoded="UTF-8"){
        //zlib 解压 并转码
        $data = false;
        $url = 'http://www.shishicai.cn/gd11x5/touzhu/';

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        //在需要用户检测的网页里需要增加下面两行
//        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//        curl_setopt($ch, CURLOPT_USERPWD, US_NAME.":".US_PWD);
        $contents = curl_exec($ch);
        curl_close($ch);
        var_dump($contents);
        echo $contents;exit;


        $data = @file_get_contents("compress.zlib://".$url);
        var_dump($data);exit;
            /*
            return $data;
            $encode = mb_detect_encoding($data, array('ASCII','UTF-8','GB2312',"GBK",'BIG5'));
            return $content=iconv($encode,$encoded,$data);
            */
        if($data){

        }else{
            //抓取网页失败 记录日志

        }
    }

    function grab($url){
        echo $url;
    }
}
?>