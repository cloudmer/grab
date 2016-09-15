<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-9-14
 * Time: 下午7:49
 */

namespace app\components;


use app\models\Configure;
use app\models\Cqssc;
use app\models\Tjssc;
use app\models\Xjssc;
use app\models\Mailbox;
use Yii;

class Alarm
{

    /* 彩票类型 */
    protected $type;

    /* 彩票名 */
    protected $cp_name;

    /* 彩票别名 */
    protected $cp_alias_name;

    /* 当前的彩票类型数据模型 */
    protected $model;

    /* 数据包1 报警配置项 */
    protected $config_one;

    /* 数据包2 报警配置项 */
    protected $config_two;

    /**
     * 警报
     * Alarm constructor.
     */
    public function __construct($type)
    {
        $this->type = $type;
        //获取彩票种类名称
        $this->get_type_name();
        //获取彩票类型的数据模型
        $this->get_model();
        //获取警报设置配置项
        $this->get_config();

        //获取最新的几期数据 进行分析报警
        $this->get_codes();
    }

    /**
     * 获取 最新几期的数据
     */
    private function get_codes(){
        $this->analysis_config_one();
        $this->analysis_config_two();
    }

    /**
     * 分析 数据包1 是否要报警
     */
    private function analysis_config_one(){
        //检查是否开启报警
        if(!$this->config_one->state){
            echo $this->cp_name."时时彩 数据包1 报警通知关闭状态 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }

        $start = $this->config_one->start_time; //报警开启时间
        $end   = $this->config_one->end_time;   //报警结束时间
        //检查是否在报警时段
        if( ($start && $end) && (date('H') < $start || date('H') > $end) ){
            //当前非报警时段
            echo $this->cp_name."时时彩报警通知非接受时段 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }

        // 当前彩种 数据包1 开启每期报警
        if($this->config_one->forever){
            $this->forever_mail(1);
        }
        $this->inspect_alarm(1);
    }

    /**
     * 分析 数据包2 是否要报警
     */
    private function analysis_config_two(){
        //检查是否开启报警
        if(!$this->config_two->state){
            echo $this->cp_name."时时彩 数据包2 报警通知关闭状态 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }

        $start = $this->config_two->start_time; //报警开启时间
        $end   = $this->config_two->end_time;   //报警结束时间
        //检查是否在报警时段
        if( ($start && $end) && (date('H') < $start || date('H') > $end) ){
            //当前非报警时段
            echo $this->cp_name."时时彩报警通知非接受时段 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }

        // 当前彩种 数据包2 开启每期报警
        if($this->config_two->forever){
            $this->forever_mail(2);
        }
        $this->inspect_alarm(2);
    }

    /**
     * 检查当前期数内 是否未中奖 报警
     */
    /**
     * @param $type 1=>数据包1的分析数据 2=>数据包2的分析数据
     */
    private function inspect_alarm($type){
        if($type == 1){
            $config = $this->config_one;
        }
        if($type == 2){
            $config = $this->config_two;
        }

        //多少期内 未中奖的报警期数
        $regret_number = $config->regret_number;
        $codes = $this->model->find()->orderBy('time DESC')->limit($regret_number)->all();
        //如果 用户设置的报警期数 不等于 查询出来的数据条数 则不执行报警 (数据库里的数据小于报警期数)
        if(count($codes) != $regret_number){
            return;
        }

        $q3_status = false; //前3 是 在当前警报期内中过奖 默认false
        $z3_status = false; //中3 是 在当前警报期内中过奖 默认false
        $h3_status = false; //后3 是 在当前警报期内中过奖 默认false

        foreach ($codes as $key=>$m){
            if($type == 1){
                $m = $this->get_data1($m);
            }
            if($type == 2){
                $m = $this->get_data2($m);
            }

            //当前 N 期内 前3号码 中过奖
            if($m->front_three_lucky_txt){
                $q3_status = true;
            }
            //当前 N 期内 中3号码 中过奖
            if($m->center_three_lucky_txt){
                $z3_status = true;
            }
            //当前 N 期内 后3号码 中过奖
            if($m->after_three_lucky_txt){
                $h3_status = true;
            }
        }

        if($q3_status && $z3_status && $h3_status){
            //当前警报期内 前3 中过 中3 中过 后3中过
            return;
        }

        $q3_status ? $q3 = 'Y' : $q3 = 'N';
        $z3_status ? $z3 = 'Y' : $z3 = 'N';
        $h3_status ? $h3 = 'Y' : $h3 = 'N';

        $mail_contents = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
            .'通知类型:'.$this->cp_alias_name.' - 当前 '.$regret_number.' 期 警报<br/>'
            .'数据包:'.$type .'<br/>'
            .'前:'.$q3 .'<br/>'
            .'中:'.$z3 .'<br/>'
            .'后:'.$h3 .'<br/>';

        $this->send_mail($mail_contents,2);
    }


    /**
     * 每期开奖都邮件报警
     * @param $type 1=> 数据报1 2=>数据包2
     */
    private function forever_mail($type){
        $newest = $this->model->find()->orderBy('time DESC')->one();
        if($type == 1){
            //数据包1分析的结果
            $data1 = $this->get_data1($newest);
            $data1->front_three_lucky_txt  ? $q3 = 'Y' : $q3 = 'N' ;
            $data1->center_three_lucky_txt ? $z3 = 'Y' : $z3 = 'N' ;
            $data1->after_three_lucky_txt  ? $h3 = 'Y' : $h3 = 'N' ;
        }

        if($type == 2){
            //数据包2分析的结果
            $data2 = $this->get_data2($newest);
            $data2->front_three_lucky_txt  ? $q3 = 'Y' : $q3 = 'N' ;
            $data2->center_three_lucky_txt ? $z3 = 'Y' : $z3 = 'N' ;
            $data2->after_three_lucky_txt  ? $h3 = 'Y' : $h3 = 'N' ;
        }

        $mail_contents = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
            .'通知类型:'.$this->cp_name.' - [时时彩] 每一期开奖通知<br/>'
            .'当前期号:'.$newest->qishu .'<br/>'
            .'开奖号码:'.$newest->code.'<br/>'
            .'数据包'.$type.' - 前三中奖:'.$q3 .'<br/>'
            .'数据包'.$type.' - 中三中奖:'.$z3 .'<br/>'
            .'数据包'.$type.' - 后三中奖:'.$h3 .'<br/>';

        //每一期开奖 邮件通知
        $this->send_mail($mail_contents,1);
    }

    /**
     * 获取当前开奖号的 数据分析 数据包1
     * @param $obj   需要查询的对象
     * @return mixed
     */
    private function get_data1($obj){
        if($this->type == 'cq'){
            return $obj->analysisCqsscsData1;
        }
        if($this->type == 'tj'){
            return $obj->analysisTjsscsData1;
        }
        if($this->type == 'xj'){
            return $obj->analysisXjsscsData1;
        }
    }

    /**
     * 获取当前开奖号的 数据分析 数据包2
     * @param $obj   需要查询的对象
     * @return mixed
     */
    private function get_data2($obj){
        if($this->type == 'cq'){
            return $obj->analysisCqsscsData2;
        }
        if($this->type == 'tj'){
            return $obj->analysisTjsscsData2;
        }
        if($this->type == 'xj'){
            return $obj->analysisXjsscsData2;
        }
    }


    /**
     * 获取彩票种类名称
     */
    private function get_type_name(){
        if($this->type == 'cq'){
            $this->cp_name       = '重庆';
            $this->cp_alias_name = '庆';
        }
        if($this->type == 'tj'){
            $this->cp_name       = '天津';
            $this->cp_alias_name = '津';
        }
        if($this->type == 'xj'){
            $this->cp_name       = '新疆';
            $this->cp_alias_name = '疆';
        }
    }

    /**
     * 获取模型
     */
    private function get_model(){
        if($this->type == 'cq'){
            $this->model = new Cqssc();
        }
        if($this->type == 'tj'){
            $this->model = new Tjssc();
        }
        if($this->type == 'xj'){
            $this->model = new Xjssc();
        }
    }


    /**
     * 获取警报设置配置项
     */
    private function get_config(){
        if($this->type == 'cq'){
            $this->config_one = Configure::findOne(['type'=>2]);  //重庆时时彩  数据包1 报警设置
            $this->config_two = Configure::findOne(['type'=>22]);  //重庆时时彩 数据包2 报警设置
        }
        if($this->type == 'tj'){
            $this->config_one = Configure::findOne(['type'=>3]);  //天津时时彩 数据包1 报警设置
            $this->config_two = Configure::findOne(['type'=>33]); //天津时时彩 数据包2 报警设置

        }
        if($this->type == 'xj'){
            $this->config_one = Configure::findOne(['type'=>4]);  //新疆时时彩 数据包1 报警设置
            $this->config_two = Configure::findOne(['type'=>44]); //新疆时时彩 数据包2 报警设置
        }
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
                    ? $msg = $this->cp_name.'时时彩 每一期邮寄通知'
                    : $msg = $this->cp_name.'时时彩 N期未中奖邮件通知';
                echo $msg." 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo " 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }

}