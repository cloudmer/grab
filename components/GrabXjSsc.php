<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-9-5
 * Time: 下午8:30
 */

namespace app\components;

use app\models\AnalysisXjssc;
use app\models\Log;
use app\models\Comparison;
use app\models\Configure;
use app\models\Xjdata;
use app\models\Xjssc;
use app\models\Mailbox;
use Yii;

//设置时区
date_default_timezone_set('PRC');

class GrabXjSsc
{

    /**
     * 信息来源网：http://tools.cjcp.com.cn/gl/ssc/xj-2.html
     * POST 数据 打开浏览器调试模式 查看 AJAX 加载地址：http://tools.cjcp.com.cn/gl/ssc/filter/kjdata.php
     * 最新开奖信息查询网
     */
    const URL = 'http://tools.cjcp.com.cn/gl/ssc/filter/kjdata.php';

    /**
     * 信息来源网 新疆时时彩官网
     * http://www.xjflcp.com/game/sscIndex
     */
    const URL_GW = 'http://www.xjflcp.com/game/sscIndex';

    /* 抓取后的数据 array */
    private $data;

    /* 新疆时时彩 上传的数据包 数组 */
    private $data_packet;

    /* 新疆时时彩 上传的数据包 txt 文本内容 */
    private $data_packet_txt;

    public function __construct()
    {
        ini_set('memory_limit','888M');
        $this->get_data();     //抓取数据
        $this->insert_mysql(); //记录数据
        $this->reserve_warning(); //预定号码报警
        $this->warning();      //邮件报警
    }

    /**
     * 预定号码报警
     */
    private function reserve_warning(){
        new Reserve('xj');
    }

    /**
     * 邮件报警
     */
    private function warning(){
        new Alarm('xj');
    }

    /**
     * file_get_contents 抓取开奖数据
     */
    private function get_data(){
        include_once('simplehtmldom_1_5/simple_html_dom.php');
        $simple_html_dom = new \simple_html_dom();

        //zlib 解压 并转码
        $data = false;
        $data = @file_get_contents("compress.zlib://".self::URL_GW);
        if(!$data){
            $this->setLog(false,'新疆时时彩-开奖数据抓取失败');
            exit('新疆时时彩-数据抓取失败,请尽快联系网站管理员'."\r\n");
        }

        //转换成 UTF-8编码
        $encode = mb_detect_encoding($data, array('ASCII','UTF-8','GB2312',"GBK",'BIG5'));
        $content = iconv($encode,'UTF-8',$data);

        $simple_html_dom->load($content);
        //开奖期号
        $qihao = $simple_html_dom->find('div[class=con_left]',1)->find('span',0)->plaintext;
        $qihao = trim($qihao);
        //开奖号
        $code_1 = $simple_html_dom->find('div[class=kj_ball_new]',0)->find('i',0)->plaintext;
        $code_2 = $simple_html_dom->find('div[class=kj_ball_new]',0)->find('i',1)->plaintext;
        $code_3 = $simple_html_dom->find('div[class=kj_ball_new]',0)->find('i',2)->plaintext;
        $code_4 = $simple_html_dom->find('div[class=kj_ball_new]',0)->find('i',3)->plaintext;
        $code_5 = $simple_html_dom->find('div[class=kj_ball_new]',0)->find('i',4)->plaintext;
        $code = trim($code_1).trim($code_2).trim($code_3).trim($code_4).trim($code_5);

        if(!$code){
            exit('新疆时时彩-等待开奖...'."\r\n");
        }

        $simple_html_dom->clear();

        //将开奖号中间的空格去掉
        $code = str_replace(" ", '', $code);
        //开奖时间
        $kjsj = date('Y-m-d H:i:s');

        $this->data = ['qihao'=>$qihao, 'kjsj'=>$kjsj, 'code'=>$code];
    }

    /**
     * curl 访问 开奖数据
     */
    private function get_data2(){
        $post_data = ['lotteryType'=>'xjssc']; //新疆时时彩
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,60);   //只需要设置一个秒的数量就可以  60超时
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);

        $xjCodeArr = json_decode($output,true);
        if(!is_array($xjCodeArr)){
            exit('新疆时时彩-数据抓取失败,请尽快联系网站管理员'."\r\n");
        }

        //期号
        if(!isset($xjCodeArr['kaijiang']['qihao'])){
            exit('新疆时时彩-开奖期号抓取失败,请尽快联系网站管理员'."\r\n");
        }

        //开奖时间
        if(!isset($xjCodeArr['kaijiang']['riqi'])){
            exit('新疆时时彩-开奖时间抓取失败,请尽快联系网站管理员'."\r\n");
        }

        //开奖号码
        if(!isset($xjCodeArr['kaijiang']['jianghao'])){
            exit('新疆时时彩-开奖号码抓取失败,请尽快联系网站管理员'."\r\n");
        }

        //期号
        $qihao = $xjCodeArr['kaijiang']['qihao'];
        //开奖时间
        $kjsj = $xjCodeArr['kaijiang']['riqi'];
        //开奖号码
        $code = $xjCodeArr['kaijiang']['jianghao'];
        $this->data = ['qihao'=>$qihao, 'kjsj'=>$kjsj, 'code'=>$code];
    }

    /**
     * 记录到 mysql
     */
    private function insert_mysql(){
        $exists = Xjssc::findOne(['qishu'=>$this->data['qihao'],'code'=>$this->data['code']]);
        if($exists){
            exit("新疆时时彩数据已经采集过了 时间:".date('Y-m-d H:i:s')."\r\n");
        }

        //开奖前三 号码
        $q3 = $this->data['code'][0].$this->data['code'][1].$this->data['code'][2];
        //开奖中三 号码
        $z3 = $this->data['code'][1].$this->data['code'][2].$this->data['code'][3];
        //后奖中三 号码
        $h3 = $this->data['code'][2].$this->data['code'][3].$this->data['code'][4];

        //解析数据包
        $this->analysisCode();

        //中奖与未中奖号码
        $isLucky = $this->isLucky($q3,$z3,$h3);

        //前三是组6还是组3
        $q3_type = $this->is_type($q3);
        //中三是组6还是组3
        $z3_type = $this->is_type($z3);
        //后三是组6还是组3
        $h3_type = $this->is_type($h3);

        if(!$isLucky || !$this->data_packet || !$this->data_packet_txt){
            exit("重庆时时彩数据包还未上传,当前不存储数据,请尽快上传数据包 时间:".date('Y-m-d H:i:s')."\r\n");
        }

        //开启事物
        $innerTransaction = Yii::$app->db->beginTransaction();
        try{
            /* 插入 开奖记录表数据 */
            $xjsscModel = new Xjssc();
            $xjsscModel->qishu             = $this->data['qihao'];
            $xjsscModel->one               = $this->data['code'][0];
            $xjsscModel->two               = $this->data['code'][1];
            $xjsscModel->three             = $this->data['code'][2];
            $xjsscModel->four              = $this->data['code'][3];
            $xjsscModel->five              = $this->data['code'][4];
            $xjsscModel->code              = $this->data['code'];
            $xjsscModel->front_three_type  = $q3_type;
            $xjsscModel->center_three_type = $z3_type;
            $xjsscModel->after_three_type  = $h3_type;
            $xjsscModel->kj_time           = $this->data['kjsj'];
            $xjsscModel->time              = time();
            $xjsscModel->save();

            /* 插入 开奖记录关联的 数据分析表 解析的结果 */
            foreach ($isLucky as $key=>$val) {
                $analysisXjsscModel = new AnalysisXjssc();
                $analysisXjsscModel->xjssc_id                = $xjsscModel->id;
                $analysisXjsscModel->front_three_lucky_txt   = $val['q3_lucky'];
                $analysisXjsscModel->front_three_regret_txt  = $val['q3_regert'];
                $analysisXjsscModel->center_three_lucky_txt  = $val['z3_lucky'];
                $analysisXjsscModel->center_three_regret_txt = $val['z3_regert'];
                $analysisXjsscModel->after_three_lucky_txt   = $val['h3_lucky'];
                $analysisXjsscModel->after_three_regret_txt  = $val['h3_regert'];
                $analysisXjsscModel->data_txt                = $this->data_packet_txt[$key]; //当前数据包文本内容
                $analysisXjsscModel->type                    = $key; //数据包的id
                $analysisXjsscModel->time                    = time();
                $analysisXjsscModel->save();
            }

            $innerTransaction->commit(); //事物提交

            $this->setLog(true,'新疆时时彩数据抓取成功');
            echo "新疆时时彩数据抓取成功 时间:".date('Y-m-d H:i:s')."\r\n";
        } catch (\Exception $e){
            $innerTransaction->rollBack();
            $this->setLog(false,'新疆时时彩数据与数据分析存入失败');
            exit("新疆时时彩数据分析存入失败 时间:".date('Y-m-d H:i:s')."\r\n");
        }
    }

    /**
     * 是组6 还是组3
     */
    private function is_type($code){
        $codeArr = str_split($code);
        //是组6
        if(count($codeArr) == count(array_unique($codeArr))){
            return 1;
        }else{
            //是组三
            return 2;
        }
    }

    /**
     * 解析 上传数据
     */
    private function analysisCode(){
        //循环将 重庆数据包 内的数据 转换成数据放在全局变量里
        $data = Xjdata::find()->select('id,data_txt')->all();
        foreach ($data as $key=>$val){
            $id = $val->id; //数据包id
            $dataTxts = str_replace("\r\n", ' ', $val->data_txt); //将回车转换为空格
            $dataArr = explode(' ',$dataTxts);
            $dataArr = array_filter($dataArr);
            $this->data_packet[$id] = $dataArr; //重庆数据包 内的数据 转换成数据放在全局变量里
            $this->data_packet_txt[$id] = $val->data_txt; //重庆数据包 内的数据包文本格式 放在全局变量里
        }
    }

    /**
     * 数据包里的号码是否中奖
     * @param $q3 开奖前3号码;
     * @param $z3 开奖中3号码;
     * @param $h3 开奖后3号码;
     * @return bool
     */
    private function isLucky($q3,$z3,$h3){
        $arr = [];
        foreach ($this->data_packet as $key=>$val){
            $data_id = $key; //数据包id
            $q3_lucky  = null;  //前三中奖号码
            $q3_regert = null;  //前三未中奖号码

            $z3_lucky  = null;  //中三中奖号码
            $z3_regert = null;  //中三未中奖号码

            $h3_lucky  = null;  //后三中奖号码
            $h3_regert = null;  //后三未中奖号码

            foreach ($val as $k=>$v){
                //前三中奖状态
                if($v == $q3){
                    $q3_lucky = $v;
                }else{
                    $q3_regert .= $v."\r\n";
                }
                //中三中奖状态
                if($v == $z3){
                    $z3_lucky = $v;
                }else{
                    $z3_regert .= $v."\r\n";
                }
                //后三中奖状态
                if($v == $h3){
                    $h3_lucky = $v;
                }else{
                    $h3_regert .= $v."\r\n";
                }
                $arr[$key] = [
                    'q3_lucky'=>$q3_lucky,
                    'q3_regert'=>$q3_regert,
                    'z3_lucky'=>$z3_lucky,
                    'z3_regert'=>$z3_regert,
                    'h3_lucky'=>$h3_lucky,
                    'h3_regert'=>$h3_regert,
                ];
            }
        }

        return $arr;
    }


    /**
     * 记录日志
     * @param bool $state      操作状态;
     * @param string $content  操作内容;
     */
    private function setLog($state = true, $content = ''){
        $state == true ? $type=1 : $type = 2;
        //抓取网页失败 记录日志
        $logModel = new Log();
        $logModel->type = $type;
        $logModel->content = $content;
        $logModel->time = time();
        $logModel->save();
    }

}