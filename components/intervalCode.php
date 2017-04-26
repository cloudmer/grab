<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-4-26
 * Time: 下午5:58
 */

namespace app\components;


use app\models\Bjssc;
use app\models\Cqssc;
use app\models\Interval;
use app\models\Tjssc;
use app\models\Xjssc;
use app\models\Mailbox;
use Yii;

class intervalCode
{

    protected $cptype;

    /* 当前的彩票类型 */
    protected $cp_name;

    /* 后台添加的预定报警号码 */
    protected $config;

    /* 当前的彩票类型数据模型 */
    public $model;

    /* 报警期数 >= 当前 */
    public $danger_number;

    /* 预定的报警号码 */
    public $reserve_number;

    /* 邮件报警内容 */
    public $content;

    /* 当前间隔号码 */
    protected $interval_number;

    /* 报警开始时间 */
    protected $start;

    /* 报警结束时间 */
    protected $end;

    /* 报警状态 */
    protected $status;

    public function __construct($type)
    {
        $this->cptype = $type;
        //获取后台添加的预定报警号码
        $this->get_config();
        // 暂无配置项
        if(!$this->config){
            return;
        }
        //获取彩票类型的数据模型
        $this->get_model();
        //获取开奖号
        $this->get_codes();
    }

    /**
     * 获取添加的预定报警号码
     */
    private function get_config(){
        if($this->cptype == 'cq'){
            //重庆时时彩
            $this->cp_name = '庆';
        }
        if($this->cptype == 'tj'){
            ///天津时时彩
            $this->cp_name = '津';
        }
        if($this->cptype == 'xj'){
            //新疆时时彩
            $this->cp_name = '疆';
        }
        if($this->cptype == 'bj'){
            //北京时时彩
            $this->cp_name = '台';
        }
        $config = Interval::find()->all();
        if(!$config){
            echo '系统还未添加 -['.$this->cp_name.'彩票] 间隔号码,请先添加'."\r\n";
        }
        $this->config = $config;
    }

    /**
     * 获取模型
     */
    private function get_model(){
        if($this->cptype == 'cq'){
            $this->model = new Cqssc();
        }
        if($this->cptype == 'tj'){
            $this->model = new Tjssc();
        }
        if($this->cptype == 'xj'){
            $this->model = new Xjssc();
        }
        if($this->cptype == 'bj'){
            $this->model = new Bjssc();
        }
    }

    private function get_codes(){
        foreach ($this->config as $key=>$val){
            $this->interval_number = $val['number'];
            $this->danger_number = $val['regret_number'];
            $this->status = $val['status'];
            $this->start = $val['start'];
            $this->end = $val['end'];

            $this->query_code();
        }

        if($this->content){
            $this->send_mail($this->content);
        }
    }

    private function query_code(){
        /*
        $codes = [
            '12466',
            '12369',
            '12858',
            '13566',
            '13789',
            '16980',
            '13666',
            '12456',
            '12466',
            '12369',
            '12858',
            '13659',
            '12466',
            '12369',
            '12858',
            '13566',
            '13789',
            '16980',
            '13666',
            '12456',
            '12466',
            '12369',
            '12858',
            '13566',
            '13789',
            '16980',
            '13666',
            '12456',
        ];

        $q3s = [];
        $z3s = [];
        $h3s = [];
        foreach ($codes as $key=>$val){
            $q3 = $val[0].$val[1].$val[2]; //前三号码
            $z3 = $val[1].$val[2].$val[3]; //中三号码
            $h3 = $val[2].$val[3].$val[4];//后三号码

            $q3s[] = $q3;
            $z3s[] = $z3;
            $h3s[] = $h3;
        }

        $this->analysis($h3s, 'h3');
        */


        $codes = $this->model->find()->orderBy('time desc')->limit('100')->all();
        sort($codes);

        $q3s = [];
        $z3s = [];
        $h3s = [];
        foreach ($codes as $key=>$val){
            $q3 = $val->one.$val->two.$val->three; //前三号码
            $z3 = $val->two.$val->three.$val->four; //中三号码
            $h3 = $val->three.$val->four.$val->five;//后三号码

            $q3s[] = $q3;
            $z3s[] = $z3;
            $h3s[] = $h3;
        }

        $this->analysis($q3s, 'q3');
        $this->analysis($z3s, 'z3');
        $this->analysis($h3s, 'h3');
    }

    /**
     * 数据分析
     * @param $codes    开奖号
     * @param $position 位置
     */
    private function analysis($codes , $position){
        $reference = false;
        $tail = false;
        $number = 0;
        foreach ($codes as $key=>$val){
            //尾巴
            if(strstr($val,$this->interval_number)){
                //echo $val . ' - ' . $this->interval_number . ' true<br/>';
                $tail = true;
            }else{
                //echo $val . ' - ' . $this->interval_number . ' false<br/>';
                $tail = false;
            }

            //没有头的情况下  找到了头
            if($reference == false && strstr($val,$this->interval_number)){
                $reference = true;
                continue;
            }

            $is_six = $this->is_six($val);

            $len = strlen($this->interval_number);
            $in_number = 0;
            for($i=0; $i<$len; $i++){
                if(strstr($val,$this->interval_number[$i])){
                    $in_number = $in_number + 1;
                }
            }

            //有头 的情况下 是组6 and 包含1位 清零 并清除头 再找下一个头
            if($reference && $is_six && $in_number == 1){
                //清零
                $number = 0;
                $reference = false;
                continue;
            }

            //有头的情况下 还出现了头 不管这一组
            if($reference && $in_number >=2){
                //直接跳过不用管
                continue;
            }

            //有头的情况下 排除以上情况 就可以+
            if($reference == true){
                $number = $number + 1;
            }
        }

        //有头 有尾 and 报警期数达到了就报警
        if($reference && $tail && ( $number >= $this->reserve_number ) ){
            $position == 'q3' ? $p_name = '前' : false;
            $position == 'z3' ? $p_name = '中' : false;
            $position == 'h3' ? $p_name = '后' : false;

            $this->content .= $this->cp_name . ' - ' . $p_name . ' 号码 '. $this->interval_number . ' 间隔 '. $number . ' 未开<br/>';
        }
    }

    /**
     * 是否是组6形态
     * @param $number 开奖号
     * @return bool
     */
    private function is_six($number){
        $codeArr = str_split($number);
        //是组6
        if(count($codeArr) == count(array_unique($codeArr))){
            return true;
        }
        //是组3
        return false;
    }


    /**
     * 发送邮件
     * @param $content  邮件内容;
     */
    private function send_mail($content){
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
//            $mail->setSubject("小蛮牛提醒");
            $mail->setSubject("机房提醒");
            //$mail->setTextBody('zheshisha');   //发布纯文字文本
            $mail->setHtmlBody($content);    //发布可以带html标签的文本

            if($mail->send()){
                echo $this->cp_name."间隔报警 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo $this->cp_name."间隔包报警 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }

}