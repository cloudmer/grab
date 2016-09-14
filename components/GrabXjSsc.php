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

    /* 抓取后的数据 array */
    private $data;

    /* 新疆时时彩 上传的数据包 数组 */
    private $data_packet;

    /* 新疆时时彩 上传的数据包2 数组 */
    private $data_packet_2;

    /* 新疆时时彩 上传的数据包1 txt 文本内容 */
    private $data_packet_txt;

    /* 新疆时时彩 上传的数据包2 txt 文本内容 */
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
        new Reserve('xj');
    }

    /**
     * curl 访问 开奖数据
     */
    private function get_data(){
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

            /* 插入 开奖记录关联的 数据分析表 数据包1解析的结果 */
            $analysisXjsscModel = new AnalysisXjssc();
            $analysisXjsscModel->xjssc_id                = $xjsscModel->id;
            $analysisXjsscModel->front_three_lucky_txt   = $q3_data1_lucky;
            $analysisXjsscModel->front_three_regret_txt  = $q3_data1_regert;
            $analysisXjsscModel->center_three_lucky_txt  = $z3_data1_lucky;
            $analysisXjsscModel->center_three_regret_txt = $z3_data1_regert;
            $analysisXjsscModel->after_three_lucky_txt   = $h3_data1_lucky;
            $analysisXjsscModel->after_three_regret_txt  = $h3_data1_regert;
            $analysisXjsscModel->data_txt                = $this->data_packet_txt;
            $analysisXjsscModel->type                    = 1;
            $analysisXjsscModel->time                    = time();
            $analysisXjsscModel->save();

            /* 插入 开奖记录关联的 数据分析表 数据包1解析的结果 */
            $analysisXjsscModel = new AnalysisXjssc();
            $analysisXjsscModel->xjssc_id                = $xjsscModel->id;
            $analysisXjsscModel->front_three_lucky_txt   = $q3_data2_lucky;
            $analysisXjsscModel->front_three_regret_txt  = $q3_data2_regert;
            $analysisXjsscModel->center_three_lucky_txt  = $z3_data2_lucky;
            $analysisXjsscModel->center_three_regret_txt = $z3_data2_regert;
            $analysisXjsscModel->after_three_lucky_txt   = $h3_data2_lucky;
            $analysisXjsscModel->after_three_regret_txt  = $h3_data2_regert;
            $analysisXjsscModel->data_txt                = $this->data_packet_txt_2;
            $analysisXjsscModel->type                    = 2;
            $analysisXjsscModel->time                    = time();
            $analysisXjsscModel->save();

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
        //新疆时时彩的数据包1
        $model = Comparison::findOne(['type'=>4]);
        $data = $model->txt;
        $this->data_packet_txt = $model->txt;
        $dataTxts = str_replace("\r\n", ' ', $data); //将回车转换为空格
        $dataArr = explode(' ',$dataTxts);
        $dataArr = array_filter($dataArr);
        $this->data_packet = $dataArr;

        //新疆时时彩的数据包2
        $model = Comparison::findOne(['type'=>44]);
        $data = $model->txt;
        $this->data_packet_txt_2 = $model->txt;
        $dataTxts = str_replace("\r\n", ' ', $data); //将回车转换为空格
        $dataArr = explode(' ',$dataTxts);
        $dataArr = array_filter($dataArr);
        $this->data_packet_2 = $dataArr;
    }

    /**
     * 数据包里的号码是否中奖
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
     * 邮件报警
     */
    private function warning(){
        $config = Configure::findOne(['type'=>4]); //新疆时时彩 系统报警配置
        $start = $config->start_time; //报警开启时间
        $end = $config->end_time;     //报警结束时间
        $regret_number = $config->regret_number; //当前最新N期内未中奖 则报警
        $forever = $config->forever; //是否开启每一期中奖与未中奖通知;
        $state = $config->state; //是否开启报警
        //检查是否开启报警
        if(!$state){
            //当前关闭报警通知
            exit("新疆时时彩报警通知关闭状态 时间:".date('Y-m-d H:i:s')."\r\n");
        }
        //检查是否在报警时段
        if(date('H') < $start || date('H') > $end ){
            //当前非报警时段
            exit("新疆时时彩报警通知非接受时段 时间:".date('Y-m-d H:i:s')."\r\n");
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
        $new_data = Xjssc::findOne(['qishu'=>$this->data['qihao'],'code'=>$this->data['code']]);
        //新疆时时彩 数据包1分析
        $analysisXjsscsData1 = $new_data->analysisXjsscsData1;
        $analysisXjsscsData1->front_three_lucky_txt
            ? $q3 = '中奖'
            : $q3 = '未中奖' ;

        $analysisXjsscsData1->center_three_lucky_txt
            ? $z3 = '中奖'
            : $z3 = '未中奖' ;

        $analysisXjsscsData1->after_three_lucky_txt
            ? $h3 = '中奖'
            : $h3 = '未中奖' ;

        $mail_contents = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
            .'通知类型:新疆 - [时时彩] 每一期开奖通知<br/>'
            .'当前彩种:新疆 - [时时彩]<br/>'
            .'当前期号:'.$this->data['qihao'] .'<br/>'
            .'开奖号码:'.$this->data['code'].'<br/>'
            .'数据包1 - 前三中奖:'.$q3 .'<br/>'
            .'数据包1 - 中三中奖:'.$z3 .'<br/>'
            .'数据包1 - 后三中奖:'.$h3 .'<br/><br/>';

        //新疆时时彩 数据包2分析
        $analysisXjsscsData2 = $new_data->analysisXjsscsData2;
        $analysisXjsscsData2->front_three_lucky_txt
            ? $q3 = '中奖'
            : $q3 = '未中奖' ;

        $analysisXjsscsData2->center_three_lucky_txt
            ? $z3 = '中奖'
            : $z3 = '未中奖' ;

        $analysisXjsscsData2->after_three_lucky_txt
            ? $h3 = '中奖'
            : $h3 = '未中奖' ;

        $mail_contents .=
            '数据包2 - 前三中奖:'.$q3 .'<br/>'
            .'数据包2 - 中三中奖:'.$z3 .'<br/>'
            .'数据包2 - 后三中奖:'.$h3;


        $this->send_mail($mail_contents);
    }

    /**
     * 系统设置的N期内都不中奖 危险的情况 邮件报警
     * 当前最新N期内未中奖 则报警
     * @param $regret_number
     */
    private function danger($regret_number){
        //当前 系统设置的 N 期不中奖  则邮件报警 用户设置 几期都未中奖 报警通知
        $newestCodes = Xjssc::find()->orderBy('time DESC')->limit($regret_number)->all();
        //如果 用户设置的报警期数 不等于 查询出来的数据条数 则不执行报警 (数据库里的数据小于报警期数)
        if(count($newestCodes) != $regret_number){
            return;
        }

        $q3_data1_lucky = false; //数据包1 最新的几期内 前三中奖状态 初始化为 false;
        $z3_data1_lucky = false; //数据包1 最新的几期内 中三中奖状态 初始化为 false;
        $h3_data1_lucky = false; //数据包1 最新的几期内 后三中奖状态 初始化为 false;

        $q3_data2_lucky = false; //数据包1 最新的几期内 前三中奖状态 初始化为 false;
        $z3_data2_lucky = false; //数据包1 最新的几期内 中三中奖状态 初始化为 false;
        $h3_data2_lucky = false; //数据包1 最新的几期内 后三中奖状态 初始化为 false;

        foreach ($newestCodes as $obj){
            //新疆时时彩 数据包1 数据分析
            $analysisXjsscsData1 = $obj->analysisXjsscsData1;
            //当前 N 期内 前三号码 中过奖
            if($analysisXjsscsData1->front_three_lucky_txt){
                $q3_data1_lucky = true;
            }
            //当前 N 期内 中三号码 中过奖
            if($analysisXjsscsData1->center_three_lucky_txt){
                $z3_data1_lucky = true;
            }
            //当前 N 期内 后三号码 中过奖
            if($analysisXjsscsData1->after_three_lucky_txt){
                $h3_data1_lucky = true;
            }

            //新疆时时彩 数据包1 数据分析
            $analysisXjsscsData2 = $obj->analysisXjsscsData2;
            //当前 N 期内 前三号码 中过奖
            if($analysisXjsscsData2->front_three_lucky_txt){
                $q3_data2_lucky = true;
            }
            //当前 N 期内 中三号码 中过奖
            if($analysisXjsscsData2->center_three_lucky_txt){
                $z3_data2_lucky = true;
            }
            //当前 N 期内 后三号码 中过奖
            if($analysisXjsscsData2->after_three_lucky_txt){
                $h3_data2_lucky = true;
            }
        }

        //当前 N 期内 都中奖了,不报警
        if($q3_data1_lucky && $z3_data1_lucky &&$h3_data1_lucky && $q3_data2_lucky && $z3_data2_lucky && $h3_data2_lucky ){
            return;
        }

        /*
        $q3_data1_lucky ? $q3_data1_msg = '[数据包1] 中奖' : $q3_data1_msg = '[数据包1] 未中奖';
        $z3_data1_lucky ? $z3_data1_msg = '[数据包1] 中奖' : $z3_data1_msg = '[数据包1] 未中奖';
        $h3_data1_lucky ? $h3_data1_msg = '[数据包1] 中奖' : $h3_data1_msg = '[数据包1] 未中奖';

        $q3_data2_lucky ? $q3_data2_msg = '[数据包2] 中奖' : $q3_data2_msg = '[数据包2] 未中奖';
        $z3_data2_lucky ? $z3_data2_msg = '[数据包2] 中奖' : $z3_data2_msg = '[数据包2] 未中奖';
        $h3_data2_lucky ? $h3_data2_msg = '[数据包2] 中奖' : $h3_data2_msg = '[数据包2] 未中奖';
        */
        $q3_data1_lucky ? $q3_data1_msg = '[数据包1] Y' : $q3_data1_msg = '[数据包1] N';
        $z3_data1_lucky ? $z3_data1_msg = '[数据包1] Y' : $z3_data1_msg = '[数据包1] N';
        $h3_data1_lucky ? $h3_data1_msg = '[数据包1] Y' : $h3_data1_msg = '[数据包1] N';

        $q3_data2_lucky ? $q3_data2_msg = '[数据包2] Y' : $q3_data2_msg = '[数据包2] N';
        $z3_data2_lucky ? $z3_data2_msg = '[数据包2] Y' : $z3_data2_msg = '[数据包2] N';
        $h3_data2_lucky ? $h3_data2_msg = '[数据包2] Y' : $h3_data2_msg = '[数据包2] N';

        $mail_contents = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
            /*
            .'通知类型:新疆 - [时时彩] 当前'.$regret_number.'期内 报警提示<br/>'
            .'当前彩种:新疆 - [时时彩]<br/>'
            .'最新的'.$regret_number.'期内 前三是否中过奖: '.$q3_data1_msg.'<br/>'
            .'最新的'.$regret_number.'期内 中三是否中过奖: '.$z3_data1_msg.'<br/>'
            .'最新的'.$regret_number.'期内 后三是否中过奖: '.$h3_data1_msg.'<br/><br/>'

            .'最新的'.$regret_number.'期内 前三是否中过奖: '.$q3_data2_msg.'<br/>'
            .'最新的'.$regret_number.'期内 中三是否中过奖: '.$z3_data2_msg.'<br/>'
            .'最新的'.$regret_number.'期内 后三是否中过奖: '.$h3_data2_msg;
            */
            .'通知类型:疆 当前'.$regret_number.'期 报警提示<br/>'
            .'前: '.$q3_data1_msg.'<br/>'
            .'中: '.$z3_data1_msg.'<br/>'
            .'后: '.$h3_data1_msg.'<br/><br/>'

            .'前: '.$q3_data2_msg.'<br/>'
            .'中: '.$z3_data2_msg.'<br/>'
            .'后: '.$h3_data2_msg;

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
                    ? $msg = '新疆时时彩 每一期邮寄通知'
                    : $msg = '新疆时时彩 N期未中奖邮件通知';
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