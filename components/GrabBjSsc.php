<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-3-30
 * Time: 下午2:01
 */

namespace app\components;


//设置时区
use app\models\AnalysisBjssc;
use app\models\Bjdata;
use app\models\Bjssc;
use app\models\Log;
use Yii;

date_default_timezone_set('PRC');

class GrabBjSsc
{

    /**
     * 信息来源网：http://www.txrflfj.com/
     * POST 数据 打开浏览器调试模式 查看 AJAX 加载地址：http://www.txrflfj.com/shishicai/kaijiang
     * 最新开奖信息查询网
     */
    const URL = 'http://www.txrflfj.com/shishicai/kaijiang';

    const URL_YL = 'http://www.yulin268.com/Chart.aspx?lotteryType=44&type=wuxing&tn=30';

    const URL_TW = 'http://www.taiwanlottery.com.tw/lotto/BingoBingo/drawing.aspx';

    /* 抓取后的数据 array */
    private $data;

    /* 天津时时彩 上传的数据包 数组 */
    private $data_packet;

    /* 天津时时彩 上传的数据包 txt 文本内容 */
    private $data_packet_txt;

    public function __construct()
    {
        ini_set('memory_limit','888M');
        $this->get_data();     //抓取数据
        $this->insert_mysql(); //记录数据
        $this->reserve_warning(); //预定号码报警
        $this->warning();      //邮件报警
        $this->containCode();  //包含报警
        $this->packet();      //包含数据包
        $this->tailCode();     //尾号玩法
    }

    /**
     * 预定号码报警
     */
    private function reserve_warning(){
        new Reserve('bj');
    }

    /**
     * 邮件报警
     */
    private function warning(){
        new Alarm('bj');
    }

    /**
     * 包含报警
     */
    private function containCode(){
        new ContainCode('bj');
    }

    /**
     * 包含数据包
     */
    private function packet(){
        new Packet('bj');
    }

    /**
     * 尾号玩法
     */
    private function tailCode(){
        new TailCode('bj');
    }

    /**
     * curl 访问 开奖数据
     */
    private function get_data(){
        include_once('simplehtmldom_1_5/simple_html_dom.php');
        $simple_html_dom = new \simple_html_dom();

        //zlib 解压 并转码
        $data = false;
        $data = @file_get_contents("compress.zlib://".self::URL_TW);
        if(!$data){
            $this->setLog(false,'北京时时彩-开奖数据抓取失败');
            exit('北京时时彩-数据抓取失败,请尽快联系网站管理员'."\r\n");
        }

        //转换成 UTF-8编码
        $encode = mb_detect_encoding($data, array('ASCII','UTF-8','GB2312',"GBK",'BIG5'));
        $content = iconv($encode,'UTF-8',$data);

        $simple_html_dom->load($content);
        //开奖期号
        $qihao = $simple_html_dom->find('span[id=lblBBDrawTerm]',0)->plaintext;

        //开奖号
        $code1 = $simple_html_dom->find('div[class=ball_tx]',0)->plaintext;
        $code2 = $simple_html_dom->find('div[class=ball_tx]',1)->plaintext;
        $code3 = $simple_html_dom->find('div[class=ball_tx]',2)->plaintext;
        $code4 = $simple_html_dom->find('div[class=ball_tx]',3)->plaintext;
        $code5 = $simple_html_dom->find('div[class=ball_tx]',4)->plaintext;
        $code6 = $simple_html_dom->find('div[class=ball_tx]',5)->plaintext;
        $code7 = $simple_html_dom->find('div[class=ball_tx]',6)->plaintext;
        $code8 = $simple_html_dom->find('div[class=ball_tx]',7)->plaintext;
        $code9 = $simple_html_dom->find('div[class=ball_tx]',8)->plaintext;
        $code10 = $simple_html_dom->find('div[class=ball_tx]',9)->plaintext;
        $code11 = $simple_html_dom->find('div[class=ball_tx]',10)->plaintext;
        $code12 = $simple_html_dom->find('div[class=ball_tx]',11)->plaintext;
        $code13 = $simple_html_dom->find('div[class=ball_tx]',12)->plaintext;
        $code14 = $simple_html_dom->find('div[class=ball_tx]',13)->plaintext;
        $code15 = $simple_html_dom->find('div[class=ball_tx]',14)->plaintext;
        $code16 = $simple_html_dom->find('div[class=ball_tx]',15)->plaintext;
        $code17 = $simple_html_dom->find('div[class=ball_tx]',16)->plaintext;
        $code18 = $simple_html_dom->find('div[class=ball_tx]',17)->plaintext;
        $code19 = $simple_html_dom->find('div[class=ball_tx]',18)->plaintext;
        $code20 = $simple_html_dom->find('div[class=ball_tx]',19)->plaintext;

        $code_1 = (int)$code1 + (int)$code2 + (int)$code3 + (int)$code4;
        $code_1 = (string)$code_1;
        $code_1 = $code_1[strlen($code_1) - 1];

        $code_2 = (int)$code5 + (int)$code6 + (int)$code7 + (int)$code8;
        $code_2 = (string)$code_2;
        $code_2 = $code_2[strlen($code_2) - 1];

        $code_3 = (int)$code9 + (int)$code10 + (int)$code11 + (int)$code12;
        $code_3 = (string)$code_3;
        $code_3 = $code_3[strlen($code_3) - 1];

        $code_4 = (int)$code13 + (int)$code14 + (int)$code15 + (int)$code16;
        $code_4 = (string)$code_4;
        $code_4 = $code_4[strlen($code_4) - 1];

        $code_5 = (int)$code17 + (int)$code18 + (int)$code19 + (int)$code20;
        $code_5 = (string)$code_5;
        $code_5 = $code_5[strlen($code_5) - 1];

        $simple_html_dom->clear();

        $code = $code_1.$code_2.$code_3.$code_4.$code_5;

        //将开奖号中间的空格去掉
        $code = str_replace(" ", '', $code);

        //将开奖期号中间的空格去掉
        $qihao = str_replace(" ", '', $qihao);

        //开奖时间
        $kjsj = date('Y-m-d H:i:s');

        $this->data = ['qihao'=>$qihao, 'kjsj'=>$kjsj, 'code'=>$code];
    }

    /**
     * curl 访问 开奖数据
     */
    private function get_data2(){
        $url = self::URL. '?date='.date('Y-m-d');
        $data = file_get_contents($url);
        $codeArr = json_decode($data,true);
        if(count($codeArr) <= 0){
            exit('北京时时彩-数据抓取失败,请尽快联系网站管理员'."\r\n");
        }

        //最新的一期是数组的0下标
        $new = $codeArr[0];
        //期号
        $qihao = $new['date'];
        //开奖时间
        $kjsj = strtotime(date('Y-m-h').' '.$new['time']);
        $kjsj = (string)$kjsj;
        //开奖号码
        $code = $new['drawNo'];
        $code = explode(',',$code);
        $code = implode('',$code);
        $this->data = ['qihao'=>$qihao, 'kjsj'=>$kjsj, 'code'=>$code];
    }

    /**
     * 记录到 mysql
     */
    private function insert_mysql(){
        $exists = Bjssc::findOne(['qishu'=>$this->data['qihao'],'code'=>$this->data['code']]);
        if($exists){
            exit("北京时时彩数据已经采集过了 时间:".date('Y-m-d H:i:s')."\r\n");
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
            exit("北京时时彩数据包还未上传,当前不存储数据,请尽快上传数据包 时间:".date('Y-m-d H:i:s')."\r\n");
        }

        //开启事物
        $innerTransaction = Yii::$app->db->beginTransaction();
        try{
            /* 插入 开奖记录表数据 */
            $Model = new Bjssc();
            $Model->qishu             = $this->data['qihao'];
            $Model->one               = $this->data['code'][0];
            $Model->two               = $this->data['code'][1];
            $Model->three             = $this->data['code'][2];
            $Model->four              = $this->data['code'][3];
            $Model->five              = $this->data['code'][4];
            $Model->code              = $this->data['code'];
            $Model->front_three_type  = $q3_type;
            $Model->center_three_type = $z3_type;
            $Model->after_three_type  = $h3_type;
            $Model->kj_time           = $this->data['kjsj'];
            $Model->time              = time();
            $Model->save();

            /* 插入 开奖记录关联的 数据分析表 解析的结果 */
            foreach ($isLucky as $key=>$val) {
                $analysisBjsscModel = new AnalysisBjssc();
                $analysisBjsscModel->bjssc_id                = $Model->id;
                $analysisBjsscModel->front_three_lucky_txt   = $val['q3_lucky'];
                $analysisBjsscModel->front_three_regret_txt  = $val['q3_regert'];
                $analysisBjsscModel->center_three_lucky_txt  = $val['z3_lucky'];
                $analysisBjsscModel->center_three_regret_txt = $val['z3_regert'];
                $analysisBjsscModel->after_three_lucky_txt   = $val['h3_lucky'];
                $analysisBjsscModel->after_three_regret_txt  = $val['h3_regert'];
                //$analysisBjsscModel->data_txt                = $this->data_packet_txt[$key]; //当前数据包文本内容
                $analysisBjsscModel->data_txt                = null; //当前数据包文本内容
                $analysisBjsscModel->type                    = $key; //数据包的id
                $analysisBjsscModel->time                    = time();
                $analysisBjsscModel->save();
            }

            $innerTransaction->commit(); //事物提交

            $this->setLog(true,'北京时时彩数据抓取成功');
            echo "北京时时彩数据抓取成功 时间:".date('Y-m-d H:i:s')."\r\n";
        } catch (\Exception $e){
            $innerTransaction->rollBack();
            $this->setLog(false,'北京时时彩数据与数据分析存入失败');
            exit("北京时时彩数据分析存入失败 时间:".date('Y-m-d H:i:s')."\r\n");
        }
    }

    /**
     * 解析 上传数据
     */
    private function analysisCode(){
        //循环将 重庆数据包 内的数据 转换成数据放在全局变量里
        $data = Bjdata::find()->select('id,data_txt')->all();
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
     * 是组6 还是组3
     * @param $code
     * @return int
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