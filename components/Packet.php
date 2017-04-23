<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-4-22
 * Time: 下午1:29
 */

namespace app\components;
use app\models\Bjssc;
use app\models\Cqssc;
use app\models\Tjssc;
use app\models\Xjssc;
use app\models\Mailbox;
use Yii;

/**
 * 数据包 玩法
 * Class Packet
 * @package app\components
 */
class Packet
{

    /* 彩票类型 */
    protected $cptype;

    /* 彩票名 */
    protected $cp_name;

    /* 彩票别名 */
    protected $cp_alias_name;

    /* 数据包 */
    protected $config;

    /* 数据库驱动 */
    protected $model;

    /* 当前数据包 */
    protected $data_txt;

    /* 当前数据包 别名 */
    protected $alias;

    /* 当前数据包 报警期数 */
    protected $regret_number;

    /* 当前数据包 报警开始时间 */
    protected $start;

    /* 当前数据包 报警结束时间 */
    protected $end;

    /* 当前数据包是否每期都报警 */
    protected $forever;

    /* 邮件内容 */
    protected $danger_email_contents;

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
        $this->cptype == 'cq' ? $type = 1 : false;
        $this->cptype == 'tj' ? $type = 2 : false;
        $this->cptype == 'xj' ? $type = 3 : false;
        $this->cptype == 'bj' ? $type = 4 : false;

        $this->cptype == 'cq' ? $this->cp_name = '重庆' : false;
        $this->cptype == 'tj' ? $this->cp_name = '天津' : false;
        $this->cptype == 'xj' ? $this->cp_name = '新疆' : false;
        $this->cptype == 'bj' ? $this->cp_name = '台湾' : false;

        $this->cptype == 'cq' ? $this->cp_alias_name = '庆' : false;
        $this->cptype == 'tj' ? $this->cp_alias_name = '津' : false;
        $this->cptype == 'xj' ? $this->cp_alias_name = '疆' : false;
        $this->cptype == 'bj' ? $this->cp_alias_name = '台' : false;

        $config = \app\models\Packet::find()->where(['type' => $type])->all();
        if(!$config){
            echo '系统还未添加 -['.$this->cp_name.'彩票] 预定报警号码,请先添加'."\r\n";
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

    /**
     * 获取开奖号
     */
    private function get_codes(){
        //获取报警配置项内的警报期数
        foreach ($this->config as $key=>$val){
            $dataTxts = str_replace("\r\n", ' ', $val->data_txt); //将回车转换为空格
            $dataArr = explode(' ',$dataTxts);
            $dataArr = array_filter($dataArr);

            $this->data_txt      = $dataArr;             //数据包 数组
            $this->alias         = $val['alias'];         //数据包别名
            $this->regret_number = $val['regret_number']; //数据包报警期数
            $this->start         = $val['start'];         //数据包报警开始时间
            $this->end           = $val['end'];           //数据包报警结束时间
            $this->forever       = $val['forever'];       //数据包是否开启每期报警通知
            //查询报警期数内的
            $this->query_codes();
        }

        //发送邮件
        if($this->danger_email_contents){
            $this->send_mail($this->danger_email_contents);
        }
    }

    /**
     * 查询开奖号
     */
    private function query_codes(){
        //检查是否在报警时段
        if( ($this->start && $this->end) && (date('H') < $this->start || date('H') > $this->end) ){
            //当前非报警时段
            echo $this->cp_name.' 数据包别名: '.$this->alias. " - 时时彩报警通知非接受时段 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }

        //是否开启每期开奖通知 不开通此共功能
        if($this->forever){
            //$this->forever();
        }

        //检查当前期数内 是否未中奖 报警
        $this->inspect_alarm();
    }

    private function inspect_alarm(){
        /*
        $codes = [
            '20448',
            '29375',
            '75604',
            '00774',
            '27120',
            '88603',
            '67144',
            '88494',
            '35945',
            '39553',
            '71423',
            '17809',
            '75975',
            '94260',
            '79299',
            '94247',
            '15682',
            '98703',
            '72411',
            '33603',
            '95223',
            '60326',
        ];
        sort($codes);

        $q3_number = 0;
        $z3_number = 0;
        $h3_number = 0;

        foreach ($codes as $key=>$val){
            $q3 = $val[0].$val[1].$val[2]; //前三号码
            $z3 = $val[1].$val[2].$val[3]; //中三号码
            $h3 = $val[2].$val[3].$val[4];//后三号码

            in_array($q3, $this->data_txt) ? $q3_number = 0 : $q3_number = $q3_number + 1;
            in_array($z3, $this->data_txt) ? $z3_number = 0 : $z3_number = $z3_number + 1;
            in_array($h3, $this->data_txt) ? $h3_number = 0 : $h3_number = $h3_number + 1;
        }
        $this->set_contents($q3_number, $z3_number, $h3_number);
        */

        $codes = $this->model->find()->orderBy('time DESC')->limit('100')->all();
        //数组倒叙
        sort($codes);

        $q3_number = 0;
        $z3_number = 0;
        $h3_number = 0;
        foreach ($codes as $key=>$val){
            $q3 = $val->one.$val->two.$val->three; //前三号码
            $z3 = $val->two.$val->three.$val->four; //中三号码
            $h3 = $val->three.$val->four.$val->five;//后三号码

            in_array($q3, $this->data_txt) ? $q3_number = 0 : $q3_number = $q3_number + 1;
            in_array($z3, $this->data_txt) ? $z3_number = 0 : $z3_number = $z3_number + 1;
            in_array($h3, $this->data_txt) ? $h3_number = 0 : $h3_number = $h3_number + 1;
        }

        $this->set_contents($q3_number, $z3_number, $h3_number);
    }

    /**
     * 邮件内容
     * @param $q3_number
     * @param $z3_number
     * @param $h3_number
     */
    private function set_contents($q3_number, $z3_number, $h3_number){
        $mail_contents = null; //初始化邮件内容

        if($q3_number >= $this->regret_number){
            $mail_contents .= '前:'. $q3_number .' <br/>';
        }

        if($z3_number >= $this->regret_number){
            $mail_contents .= '中:'. $z3_number .' <br/>';
        }

        if($h3_number >= $this->regret_number){
            $mail_contents .= '后:'. $h3_number .' <br/>';
        }

        $title = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
            .'通知类型:'. $this->cp_alias_name .'<br/>'
            .'包含-数据包别名:'. $this->alias .'<br/>';

        //如果 达到报警条件 则报警
        if($mail_contents){
            $mail_contents = $title.$mail_contents;
        }

        $this->danger_email_contents .= $mail_contents;
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
                echo $this->cp_name."包含-数据包报警 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo $this->cp_name."包含-数据包报警 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }


}