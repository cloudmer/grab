<?php
namespace app\components;
use app\models\Analysis;
use app\models\Code;
use app\models\CodeOld;
use app\models\Comparison;
use app\models\Configure;
use app\models\Log;
use app\models\Mailbox;
use Yii;
use yii\helpers\Url;

class GrabOld{
    private $grab;

    private $urlArr = array(
        '重庆 - [老时时彩] '=>'http://cp.360.cn/ssccq/?r_a=26ruYj',
    );

    private $codeType = array(
        'http://cp.360.cn/ssccq/?r_a=26ruYj'=>'1',
    );

    private $shishicaiUrl = array(
        '重庆 - [老时时彩] '=>'http://www.shishicai.cn/cqssc/touzhu/',
    );

    public function __construct($url){
        $h = date('H',time());
        $i = date('i',time());
        if($h<9 || ($h==23 && $i>5) ){
            //早上没到9点就不工作
            //晚上 23点15以后就不工作了
//            echo '休息了,亲';
//            exit;
        }

        ini_set('memory_limit','888M');
        include_once('simplehtmldom_1_5/simple_html_dom.php');
        $this->grab = new \simple_html_dom();
        $content = $this->getHtml($url);
        $this->grab->load($content);
        $this->html_str($url);
        $this->grab->clear();
    }

    private function getHtml($url,$encoded="UTF-8"){
        //zlib 解压 并转码
        $data = false;
        $data = @file_get_contents("compress.zlib://".$url);
        if($data){
            $encode = mb_detect_encoding($data, array('ASCII','UTF-8','GB2312',"GBK",'BIG5'));
            $content = iconv($encode,$encoded,$data);
            return $content;
        }else{
            //抓取网页失败 记录日志
            $urlName = array_keys($this->urlArr,$url);
            $logModel = new Log();
            $logModel->type = 0;
            $logModel->content = $urlName[0].'.开奖信息抓取失败';
            $logModel->time = time();
            $logModel->save();
            echo $urlName[0].' - [新时时彩] 开奖信息抓取失败,请尽快通知网站管理员<br/>';
            return;
        }
    }

    private function html_str($url){
        $qihao = $this->grab->find('div[class=aside]',0)->find('h3',0)->find('em',0)->plaintext;
        $code = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',1)->plaintext;

        if($code != '--'){
            $isKaiJiang = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->plaintext;
            if($isKaiJiang != '--' && $isKaiJiang != '开奖中'){
                //能读取到数据
                $h3_shape = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->find('span',0)->plaintext;
                $h3_size = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->find('span',1)->plaintext;
                $h3_jiou = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->find('span',2)->plaintext;

                $h2_shape = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',3)->find('span',0)->plaintext;
                $h2_10wei = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',3)->find('span',1)->plaintext;
                $h2_gewei = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',3)->find('span',2)->plaintext;

                echo '开奖期号:'.$qihao;
                echo '<br/>';
                echo '开奖号码:'.$code;
                echo '<br/>';
                echo '<br/>';

                echo '后3形态:'.$h3_shape;
                echo '<br/>';
                echo '后3大小比:'.$h3_size;
                echo '<br/>';
                echo '后3奇偶比:'.$h3_jiou;
                echo '<br/>';
                echo '<br/>';

                echo '后2形态:'.$h2_shape;
                echo '<br/>';
                echo '后2大小比:'.$h2_10wei;
                echo '<br/>';
                echo '后2奇偶比:'.$h2_gewei;

                $result = CodeOld::findOne(['qishu'=>$qihao,'type'=>$this->codeType[$url]]);
                if($result){
                    $urlName = array_keys($this->urlArr,$url);
                    echo $urlName[0].' - [新时时彩] 最新数据已经采集过了<br/>';
                    return;
                }

                $model = new CodeOld();
                $model->qishu = $qihao;
                $model->code = $code;
                $model->after_three_shape = $h3_shape;
                $model->after_three_size = $h3_size;
                $model->after_three_jiou = $h3_jiou;
                $model->after_two_shape = $h2_shape;
                $model->after_two_tens_place = $h2_10wei;
                $model->after_two_the_unit = $h2_gewei;
                $model->type = $this->codeType[$url];
                $model->time = time();
                $model->save();

            }else{
                $urlName = array_keys($this->urlArr,$url);
                echo $urlName[0].'等待开奖...<br/>';
            }
        }else{
            $urlName = array_keys($this->urlArr,$url);
            echo $urlName[0].'等待开奖...<br/>';
        }
    }

}
?>