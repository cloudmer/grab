<?php
/**
 * Created by PhpStorm.
 * User: wang.haibo
 * Date: 2018/5/18
 * Time: 10:57
 */

namespace app\components;


use app\models\AnalysisTxffc;
use app\models\Log;
use app\models\Txdata;
use app\models\Txffc;
use yii\console\Controller;
use \Yii;


//设置时区
date_default_timezone_set('PRC');

class GrabTxFfc extends Controller
{

    /**
     * 最新开奖信息查询网
     */
    const URL = 'http://www.011428.com/cqssc.php';

    /* 抓取后的数据 array */
    private $data;

    /* 腾讯分分彩 上传的数据包 数组 */
    private $data_packet = [];

    /* 腾讯分分彩 上传的数据包 txt 文本内容 */
    private $data_packet_txt;

    public function __construct()
    {
        ini_set('memory_limit','888M');
        $this->get_data();     //抓取数据
        $this->insert_mysql(); //记录数据
    }

    /**
     * 抓取开奖号
     */
    private function get_data(){
        include_once('simplehtmldom_1_5/simple_html_dom.php');
        $simple_html_dom = new \simple_html_dom();

        //zlib 解压 并转码
        $data = false;
        $data = @file_get_contents("compress.zlib://".self::URL);
        if(!$data){
            $this->setLog(false,'腾讯分分彩-开奖数据抓取失败');
            exit('腾讯分分彩-数据抓取失败,请尽快联系网站管理员'."\r\n");
        }

        //转换成 UTF-8编码
        $encode = mb_detect_encoding($data, array('ASCII','UTF-8','GB2312',"GBK",'BIG5'));
        $content = iconv($encode,'UTF-8',$data);
        $simple_html_dom->load($content);

        $qihao = $simple_html_dom->find('h2', 0)->plaintext;
        $code  = trim($simple_html_dom->find('span', 0)->plaintext)
            . trim($simple_html_dom->find('span', 1)->plaintext)
            . trim($simple_html_dom->find('span', 2)->plaintext)
            . trim($simple_html_dom->find('span', 3)->plaintext)
            . trim($simple_html_dom->find('span', 4)->plaintext);

        $kjsj = date('Y-m-d H:i:s');
        $this->data = ['qihao'=>$qihao, 'kjsj'=>$kjsj, 'code'=>$code];
    }

    /**
     * 记录到 mysql
     */
    private function insert_mysql(){
        $exists = Txffc::findOne(['qishu'=>$this->data['qihao'],'code'=>$this->data['code']]);
        if($exists){
            exit("腾讯分分彩数据已经采集过了 时间:".date('Y-m-d H:i:s')."\r\n");
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
            exit("腾讯分分彩数据包还未上传,当前不存储数据,请尽快上传数据包 时间:".date('Y-m-d H:i:s')."\r\n");
        }

        //开启事物
        $innerTransaction = Yii::$app->db->beginTransaction();
        try{
            /* 插入 开奖记录表数据 */
            $cqsscModel = new Txffc();
            $cqsscModel->qishu             = $this->data['qihao'];
            $cqsscModel->one               = $this->data['code'][0];
            $cqsscModel->two               = $this->data['code'][1];
            $cqsscModel->three             = $this->data['code'][2];
            $cqsscModel->four              = $this->data['code'][3];
            $cqsscModel->five              = $this->data['code'][4];
            $cqsscModel->code              = $this->data['code'];
            $cqsscModel->front_three_type  = $q3_type;
            $cqsscModel->center_three_type = $z3_type;
            $cqsscModel->after_three_type  = $h3_type;
            $cqsscModel->kj_time           = $this->data['kjsj'];
            $cqsscModel->time              = time();
            $cqsscModel->save();

            /* 插入 开奖记录关联的 数据分析表 解析的结果 */
            foreach ($isLucky as $key=>$val){
                $analysisCqsscModel = new AnalysisTxffc();
                $analysisCqsscModel->txffc_id                = $cqsscModel->id;
                $analysisCqsscModel->front_three_lucky_txt   = $val['q3_lucky'];
                $analysisCqsscModel->front_three_regret_txt  = $val['q3_regert'];
                $analysisCqsscModel->center_three_lucky_txt  = $val['z3_lucky'];
                $analysisCqsscModel->center_three_regret_txt = $val['z3_regert'];
                $analysisCqsscModel->after_three_lucky_txt   = $val['h3_lucky'];
                $analysisCqsscModel->after_three_regret_txt  = $val['h3_regert'];
                //$analysisCqsscModel->data_txt                = $this->data_packet_txt[$key]; //当前数据包文本内容
                $analysisCqsscModel->data_txt                = null; //当前数据包文本内容
                $analysisCqsscModel->type                    = $key; //数据包的id
                $analysisCqsscModel->time                    = time();
                $analysisCqsscModel->save();
            }

            $innerTransaction->commit(); //事物提交

            $this->setLog(true,'腾讯分分彩数据抓取成功');
            echo "腾讯分分彩数据抓取成功 时间:".date('Y-m-d H:i:s')."\r\n";
        } catch (\Exception $e){
            $innerTransaction->rollBack();
            $this->setLog(false,'腾讯分分彩数据与数据分析存入失败');
            exit("腾讯分分彩数据分析存入失败 时间:".date('Y-m-d H:i:s')."\r\n");
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
        $data = Txdata::find()->select('id,data_txt')->all();
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
                    'q3_lucky'  => $q3_lucky  ? '1' : null,
                    'q3_regert' => $q3_regert ? '1' : null,
                    'z3_lucky'  => $z3_lucky  ? '1' : null,
                    'z3_regert' => $z3_regert ? '1' : null,
                    'h3_lucky'  => $h3_lucky  ? '1' : null,
                    'h3_regert' => $h3_regert ? '1' : null,
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