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

    /* 重庆时时彩 上传的数据包 数组 */
    private $data_packet;

    /* 重庆时时彩 上传的数据包 txt 文本内容 */
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
        new Danger('cq');
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

        list($q3_lucky,$q3_regert) = $this->isLucky($q3); //前三中奖情况
        list($z3_lucky,$z3_regert) = $this->isLucky($z3); //中三是否中奖
        list($h3_lucky,$h3_regert) = $this->isLucky($h3); //侯三是否中奖

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
            $cqsscModel->after_three_type  = $z3_type;
            $cqsscModel->kj_time           = $this->data['kjsj'];
            $cqsscModel->time              = time();
            $cqsscModel->save();

            /* 插入 开奖记录关联的 数据分析表 */
            $analysisCqsscModel = new AnalysisCqssc();
            $analysisCqsscModel->cqssc_id = $cqsscModel->id;
            $analysisCqsscModel->front_three_lucky_txt = $q3_lucky;
            $analysisCqsscModel->front_three_regret_txt = $q3_regert;
            $analysisCqsscModel->center_three_lucky_txt = $z3_lucky;
            $analysisCqsscModel->center_three_regret_txt = $z3_regert;
            $analysisCqsscModel->after_three_lucky_txt = $h3_lucky;
            $analysisCqsscModel->after_three_regret_txt = $h3_regert;
            $analysisCqsscModel->data_txt = $this->data_packet_txt;
            $analysisCqsscModel->time = time();
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
        //重庆时时彩的数据包
        $model = Comparison::findOne(['type'=>2]);
        $data = $model->txt;
        $this->data_packet_txt = $model->txt;
        $dataTxts = str_replace("\r\n", ' ', $data); //将回车转换为空格
        $dataArr = explode(' ',$dataTxts);
        $dataArr = array_filter($dataArr);
        $this->data_packet = $dataArr;
    }

    /**
     * 数据包里的号码是否中奖
     * @param $code 需要查询的 前三 or 中三 or 后三号码;
     * @return bool
     */
    private function isLucky($code){
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
        return [$lucky,$regert];
    }

    /**
     * 邮件报警
     */
    private function warning(){
        $config = Configure::findOne(['type'=>2]); //重庆时时彩 系统报警配置
        $start = $config->start_time; //报警开启时间
        $end = $config->end_time;     //报警结束时间
        $regret_number = $config->regret_number; //当前最新N期内未中奖 则报警
        $forever = $config->forever; //是否开启每一期中奖与未中奖通知;
        $state = $config->state; //是否开启报警
        //检查是否开启报警
        if(!$state){
            //当前关闭报警通知
            exit("重庆时时彩报警通知关闭状态 时间:".date('Y-m-d H:i:s')."\r\n");
        }
        //检查是否在报警时段
        if(date('H') < $start || date('H') > $end ){
            //当前非报警时段
            exit("重庆时时彩报警通知非接受时段 时间:".date('Y-m-d H:i:s')."\r\n");
        }
        //是否开启每期中奖与未接邮件通知
        if($forever){
            //每期 中奖与不中奖都邮件通知
            $this->forever_notice();
        }

        //当前 系统设置的 N 期不中奖  则邮件报警 用户设置 几期都未中奖 报警通知
        $this->danger($regret_number);
    }

    /**
     * 每期邮件通知
     */
    private function forever_notice(){
        //最新抓取的一期号码,本次进程所抓取的 开奖信息
        $new_data = Cqssc::findOne(['qishu'=>$this->data['qihao'],'code'=>$this->data['code']]);
        //重庆时时彩 数据分析
        $analysisCqsscs = $new_data->analysisCqsscs;
        $analysisCqsscs->front_three_lucky_txt
            ? $q3 = '中奖'
            : $q3 = '未中奖' ;

        $analysisCqsscs->center_three_lucky_txt
            ? $z3 = '中奖'
            : $z3 = '未中奖' ;

        $analysisCqsscs->after_three_lucky_txt
            ? $h3 = '中奖'
            : $h3 = '未中奖' ;

        $mail_contents = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
            .'通知类型:重庆 - [时时彩] 每一期开奖通知<br/>'
            .'当前彩种:重庆 - [时时彩]<br/>'
            .'当前期号:'.$this->data['qihao'] .'<br/>'
            .'开奖号码:'.$this->data['code'].'<br/>'
            .'前三中奖:'.$q3 .'<br/>'
            .'中三中奖:'.$z3 .'<br/>'
            .'后三中奖:'.$h3;

        $this->send_mail($mail_contents);
    }

    /**
     * 系统设置的N期内都不中奖 危险的情况 邮件报警
     * 当前最新N期内未中奖 则报警
     * @param $regret_number
     */
    private function danger($regret_number){
        //当前 系统设置的 N 期不中奖  则邮件报警 用户设置 几期都未中奖 报警通知
        $newestCodes = Cqssc::find()->orderBy('time DESC')->limit($regret_number)->all();
        //如果 用户设置的报警期数 不等于 查询出来的数据条数 则不执行报警 (数据库里的数据小于报警期数)
        if(count($newestCodes) != $regret_number){
            return;
        }
        $q3_lucky = false; // 最新的几期内 前三中奖状态 初始化为 false;
        $z3_lucky = false; // 最新的几期内 中三中奖状态 初始化为 false;
        $h3_lucky = false; // 最新的几期内 后三中奖状态 初始化为 false;
        foreach ($newestCodes as $obj){
            //重庆时时彩 数据分析
            $analysisCqsscs = $obj->analysisCqsscs;
            //当前 N 期内 前三号码 中过奖
            if($analysisCqsscs->front_three_lucky_txt){
                $q3_lucky = true;
            }
            //当前 N 期内 中三号码 中过奖
            if($analysisCqsscs->center_three_lucky_txt){
                $z3_lucky = true;
            }
            //当前 N 期内 后三号码 中过奖
            if($analysisCqsscs->after_three_lucky_txt){
                $h3_lucky = true;
            }
        }

        //当前 N 期内 都中奖了,不报警
        if($q3_lucky && $z3_lucky &&$h3_lucky){
            return;
        }

        $q3_lucky ? $q3_msg = '中奖' : $q3_msg = '未中奖';
        $z3_lucky ? $z3_msg = '中奖' : $z3_msg = '未中奖';
        $h3_lucky ? $h3_msg = '中奖' : $h3_msg = '未中奖';
        $mail_contents = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
            .'通知类型:重庆 - [时时彩] 当前'.$regret_number.'期内 报警提示<br/>'
            .'当前彩种:重庆 - [时时彩]<br/>'
            .'最新的'.$regret_number.'期内 前三是否中过奖: '.$q3_msg.'<br/>'
            .'最新的'.$regret_number.'期内 中三是否中过奖: '.$z3_msg.'<br/>'
            .'最新的'.$regret_number.'期内 后三是否中过奖: '.$h3_msg;

        $this->send_mail($mail_contents,0);
    }

    /**
     * 发送邮件
     * @param $content  邮件内容;
     * @param int $type 邮件类容 1为 每一期中奖邮件通知 2 为 N期未中奖邮件通知;
     */
    private function send_mail($content ,$type = 1){
        //配置文件的 发件人地址
        $sendEmailUser = Yii::$app->params['sendEmailUser'];

        /* 将最新的发件人配置信息 写入配置文件 */
        $addresserMailbox = Mailbox::find()->where(['type'=>0])->all();
        $email = $addresserMailbox[array_rand($addresserMailbox,1)];
        //数据库里的 发件人地址 与 配置文件不同时 则更新配置文件
        if($sendEmailUser != $email){
            $path = Yii::getAlias('@webroot').'/../config/mailer.php';
            $fh = fopen($path, "r+");
            $new_content = '<?php return [\'sendEmailUser\' => \''.$email->email_address.'\',\'sendEmailPassword\' => \''.$email->password.'\',\'messageConfigFrom\' => \''.$email->email_address.'\'];';
            if( flock($fh, LOCK_EX) ){//加写锁
                ftruncate($fh,0); // 将文件截断到给定的长度
                rewind($fh); // 倒回文件指针的位置
                fwrite($fh,$new_content);
                flock($fh, LOCK_UN); //解锁
            }
            fclose($fh);
        }

        //收件人列表
        $recipientsMailboxs = Mailbox::find()->where(['type'=>1])->all();
        foreach ($recipientsMailboxs as $key=>$obj){
            $mail= Yii::$app->mailer->compose();
            $mail->setTo($obj->email_address);
            $mail->setSubject("小蛮牛提醒");
            //$mail->setTextBody('zheshisha');   //发布纯文字文本
            $mail->setHtmlBody($content);    //发布可以带html标签的文本

            if($mail->send()){
                $type == 1
                    ? $msg = '重庆时时彩 每一期邮寄通知'
                    : $msg = '重庆时时彩 N期未中奖邮件通知';
                echo $msg." 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo " 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
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