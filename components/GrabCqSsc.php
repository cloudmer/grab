<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-9-9
 * Time: 上午12:52
 */

namespace app\components;

use app\models\AnalysisCqssc;
use app\models\Cqssc;
use app\models\Log;
use app\models\Comparison;
use app\models\Configure;
use app\models\Mailbox;
use Yii;

//设置时区
date_default_timezone_set('PRC');


class GrabCqSsc
{

    /**
     * 信息来源网：http://cp.360.cn/ssccq/?r_a=26ruY
     * 最新开奖信息查询网
     */
    const URL = 'http://cp.360.cn/ssccq/?r_a=26ruYj';

    /* 抓取后的数据 array */
    private $data;

    /* 重庆时时彩 上传的数据包1 数组 */
    private $data_packet;

    /* 重庆时时彩 上传的数据包2 数组 */
    private $data_packet_2;

    /* 重庆时时彩 上传的数据包1 txt 文本内容 */
    private $data_packet_txt;

    /* 重庆时时彩 上传的数据包2 txt 文本内容 */
    private $data_packet_txt_2;

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
        new Reserve('cq');
    }

    /**
     * 邮件报警
     */
    private function warning(){
        new Alarm('cq');
    }

    /**
     * curl 访问 开奖数据
     */
    private function get_data(){
        include_once('simplehtmldom_1_5/simple_html_dom.php');
        $simple_html_dom = new \simple_html_dom();

        //zlib 解压 并转码
        $data = false;
        $data = @file_get_contents("compress.zlib://".self::URL);
        if(!$data){
            $this->setLog(false,'重庆时时彩-开奖数据抓取失败');
            exit('重庆时时彩-数据抓取失败,请尽快联系网站管理员'."\r\n");
        }

        //转换成 UTF-8编码
        $encode = mb_detect_encoding($data, array('ASCII','UTF-8','GB2312',"GBK",'BIG5'));
        $content = iconv($encode,'UTF-8',$data);

        $simple_html_dom->load($content);
        //开奖期号
        $qihao = $simple_html_dom->find('div[class=aside]',0)->find('h3',0)->find('em',0)->plaintext;
        //开奖号
        $code = $simple_html_dom->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',1)->plaintext;

        if($code == '--'){
            exit('重庆时时彩-等待开奖...'."\r\n");
        }

        $isKaiJiang = $simple_html_dom->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->plaintext;
        if($isKaiJiang == '--' && $isKaiJiang == '开奖中'){
            exit('重庆时时彩-等待开奖...'."\r\n");
        }
        $simple_html_dom->clear();

        //将开奖号中间的空格去掉
        $code = str_replace(" ", '', $code);
        //开奖时间
        $kjsj = date('Y-m-d H:i:s');

        $this->data = ['qihao'=>$qihao, 'kjsj'=>$kjsj, 'code'=>$code];
    }

    /**
     * 记录到 mysql
     */
    private function insert_mysql(){
        $exists = Cqssc::findOne(['qishu'=>$this->data['qihao'],'code'=>$this->data['code']]);
        if($exists){
            exit("重庆时时彩数据已经采集过了 时间:".date('Y-m-d H:i:s')."\r\n");
        }

        //开奖前三 号码
        $q3 = $this->data['code'][0].$this->data['code'][1].$this->data['code'][2];
        //开奖中三 号码
        $z3 = $this->data['code'][1].$this->data['code'][2].$this->data['code'][3];
        //后奖中三 号码
        $h3 = $this->data['code'][2].$this->data['code'][3].$this->data['code'][4];
        $this->analysisCode();

        list($q3_data1_lucky,$q3_data1_regert,$q3_data2_lucky,$q3_data2_regert) = $this->isLucky($q3); //前三中奖情况
        list($z3_data1_lucky,$z3_data1_regert,$z3_data2_lucky,$z3_data2_regert) = $this->isLucky($z3); //中三是否中奖
        list($h3_data1_lucky,$h3_data1_regert,$h3_data2_lucky,$h3_data2_regert) = $this->isLucky($h3); //侯三是否中奖

        //前三是组6还是组3
        $q3_type = $this->is_type($q3);
        //中三是组6还是组3
        $z3_type = $this->is_type($z3);
        //后三是组6还是组3
        $h3_type = $this->is_type($h3);

        //开启事物
        $innerTransaction = Yii::$app->db->beginTransaction();
        try{
            /* 插入 开奖记录表数据 */
            $cqsscModel = new Cqssc();
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

            /* 插入 开奖记录关联的 数据分析表 数据包1解析的结果 */
            $analysisCqsscModel = new AnalysisCqssc();
            $analysisCqsscModel->cqssc_id                = $cqsscModel->id;
            $analysisCqsscModel->front_three_lucky_txt   = $q3_data1_lucky;
            $analysisCqsscModel->front_three_regret_txt  = $q3_data1_regert;
            $analysisCqsscModel->center_three_lucky_txt  = $z3_data1_lucky;
            $analysisCqsscModel->center_three_regret_txt = $z3_data1_regert;
            $analysisCqsscModel->after_three_lucky_txt   = $h3_data1_lucky;
            $analysisCqsscModel->after_three_regret_txt  = $h3_data1_regert;
            $analysisCqsscModel->data_txt                = $this->data_packet_txt;
            $analysisCqsscModel->type                    = 1; //数据包1解析的数据
            $analysisCqsscModel->time                    = time();
            $analysisCqsscModel->save();

            /* 插入 开奖记录关联的 数据分析表 数据包2解析的结果 */
            $analysisCqsscModel = new AnalysisCqssc();
            $analysisCqsscModel->cqssc_id                = $cqsscModel->id;
            $analysisCqsscModel->front_three_lucky_txt   = $q3_data2_lucky;
            $analysisCqsscModel->front_three_regret_txt  = $q3_data2_regert;
            $analysisCqsscModel->center_three_lucky_txt  = $z3_data2_lucky;
            $analysisCqsscModel->center_three_regret_txt = $z3_data2_regert;
            $analysisCqsscModel->after_three_lucky_txt   = $h3_data2_lucky;
            $analysisCqsscModel->after_three_regret_txt  = $h3_data2_regert;
            $analysisCqsscModel->data_txt                = $this->data_packet_txt_2;
            $analysisCqsscModel->type                    = 2; //数据包2解析的数据
            $analysisCqsscModel->time                    = time();
            $analysisCqsscModel->save();

            $innerTransaction->commit(); //事物提交

            $this->setLog(true,'重庆时时彩数据抓取成功');
            echo "重庆时时彩数据抓取成功 时间:".date('Y-m-d H:i:s')."\r\n";
        } catch (\Exception $e){
            $innerTransaction->rollBack();
            $this->setLog(false,'重庆时时彩数据与数据分析存入失败');
            exit("重庆时时彩数据分析存入失败 时间:".date('Y-m-d H:i:s')."\r\n");
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
        //重庆时时彩的数据包1
        $model = Comparison::findOne(['type'=>2]);
        $data = $model->txt;
        $this->data_packet_txt = $model->txt;
        $dataTxts = str_replace("\r\n", ' ', $data); //将回车转换为空格
        $dataArr = explode(' ',$dataTxts);
        $dataArr = array_filter($dataArr);
        $this->data_packet = $dataArr;

        //重庆时时彩的数据包2
        $model = Comparison::findOne(['type'=>22]);
        $data = $model->txt;
        $this->data_packet_txt_2 = $model->txt;
        $dataTxts = str_replace("\r\n", ' ', $data); //将回车转换为空格
        $dataArr = explode(' ',$dataTxts);
        $dataArr = array_filter($dataArr);
        $this->data_packet_2 = $dataArr;
    }

    /**
     * 数据包1里的号码是否中奖
     * @param $code 需要查询的 前三 or 中三 or 后三号码;
     * @return bool
     */
    private function isLucky($code){
        //数据包1 中的中奖号码与未中奖号码
        $data_packet = $this->data_packet;
        $lucky = null;  //中奖号码
        $regert = null; //未中奖号码
        foreach ($data_packet as $key=>$val){
            if($val == $code){
                $lucky = $val;
            }else{
                $regert .= $val."\r\n";
            }
        }

        $data1_lucky = $lucky;
        $data1_regert = $regert;

        //数据包2 中的中奖号码与未中奖号码
        $data_packet = $this->data_packet_2;
        $lucky = null;  //中奖号码
        $regert = null; //未中奖号码
        foreach ($data_packet as $key=>$val){
            if($val == $code){
                $lucky = $val;
            }else{
                $regert .= $val."\r\n";
            }
        }

        $data2_lucky = $lucky;   //数据包2中的中奖号码
        $data2_regert = $regert; //数据包2中的未中奖号码

        return [$data1_lucky,$data1_regert,$data2_lucky,$data2_regert];
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