<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-4-3
 * Time: 上午10:44
 */

namespace app\components;


use app\models\Bjssc;
use app\models\Contain;
use app\models\Cqssc;
use app\models\Tjssc;
use app\models\Xjssc;
use app\models\Mailbox;
use Yii;

class ContainCode
{
    /* 彩票类型 */
    protected $type;

    /* 彩票名 */
    protected $cp_name;

    /* 彩票别名 */
    protected $cp_alias_name;

    /* 当前的彩票类型数据模型 */
    protected $model;

    /* 当前彩种的报警设置 */
    protected $config;

    /* 包含号码 */
    protected $contents;

    /* 包含几位 */
    protected $number;

    /* 报警开始时间 */
    protected $start;

    /* 报警结束时间 */
    protected $end;

    /* 最新的开奖数据 */
    protected $newest;

    /* 邮件报警内容 */
    protected $email_content;

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
        if($this->type == 'bj'){
            $this->cp_name       = '台湾';
            $this->cp_alias_name = '台';
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
        if($this->type == 'bj'){
            $this->model = new Bjssc();
        }
    }

    /**
     * 获取警报设置配置项
     */
    private function get_config(){

        $this->type == 'cq' ? $cp_type = 1 : false;
        $this->type == 'tj' ? $cp_type = 2 : false;
        $this->type == 'xj' ? $cp_type = 3 : false;
        $this->type == 'bj' ? $cp_type = 4 : false;

        $config = Contain::find()->where(['valve'=>1, 'cp_type'=>$cp_type])->select('id,contents,number,valve,cp_type,start,end,created')->asArray()->all();

        $this->config = $config;
    }

    /**
     * 获取 最新几期的数据
     */
    private function get_codes(){
        $this->analysis();
    }

    /**
     * 分析数据
     */
    private function analysis(){
        //最新的开奖数据
        $this->recent();

        if(!$this->newest){
            echo $this->cp_name.' 还没有开奖数据 '."\r\n";
            return;
        }

        //获取报警配置项内的警报期数
        foreach ($this->config as $key=>$val){
            $this->contents      = $val['contents'];      //包含号码
            $this->number        = $val['number'];        //包含几位
            $this->start         = $val['start'];         //报警开始时间
            $this->end           = $val['end'];           //报警结束时间
            //查询报警期数内的
            $this->query_codes();
        }

        echo $this->email_content;exit;
        if(!$this->email_content){
            //不到达报警提示
            return;
        }
        $this->send_mail($this->email_content);
    }

    private function recent(){
        $this->newest = $this->model->find()->orderBy('time DESC')->limit(1)->one();
    }

    private function query_codes(){
        //检查是否在报警时段
        if( ($this->start && $this->end) && (date('H') < $this->start || date('H') > $this->end) ){
            //当前非报警时段
            echo $this->cp_name.' 包含组: '. $this->contents . " - 报警通知非接受时段 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }
//        $this->isContain();
        $this->q3();
        $this->z3();
        $this->h3();
    }

    /**
     * 前三
     */
    private function q3(){
        $code = $this->newest->one.$this->newest->two.$this->newest->three;
        $this->isContain($code, '前');
    }

    /**
     * 中3
     */
    private function z3(){
        $code = $this->newest->two.$this->newest->three.$this->newest->four;
        $this->isContain($code, '中');
    }

    /**
     * 后3
     */
    private function h3(){
        $code = $this->newest->three.$this->newest->four.$this->newest->five;
        $this->isContain($code, '后');
    }

    /**
     * 是否包含
     * @param $code
     * @param $name
     */
    private function isContain($code, $name){
        //开奖号
        $kjcode = (string)$this->newest->one.$this->newest->two.$this->newest->three.$this->newest->four.$this->newest->five;

        $qishu = $this->newest->qishu;
        $code = (string)$code;
        $contents = (string)$this->contents;
        $num = 0; //计数器

        $len = strlen($contents);
        for ($i=0; $i<$len; $i++){
            $status = strstr($code, $contents[$i]);
            if($status == true){
                //包含
                $num+=1;
            }
        }

        if($num >= $this->number){
            $this->email_content .= $this->cp_alias_name. ' - 期:' . $qishu . " - $name" . ' - 数:'. $kjcode. ' - 含:'. $contents . ' - 出现:'.$num . "<br/>";
        }

        /*
        $qishu = $this->newest->qishu;
        $code = (string)$this->newest->one.$this->newest->two.$this->newest->three.$this->newest->four.$this->newest->five;
        $contents = (string)$this->contents;

        $num = 0; //计数器

        $len = strlen($contents);
        for ($i=0; $i<$len; $i++){
            $status = strstr($code, $contents[$i]);
            if($status == true){
                //包含
                $num+=1;
            }
        }

        if($num >= $this->number){
            $this->email_content .= $this->cp_alias_name. ' - 期:' . $qishu . ' - 数:'. $code. ' - 含:'. $contents . ' - 出现:'.$num . "<br/>";
        }
        */
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
                echo $this->cp_name."预定号码报警 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo $this->cp_name."预定号码报警 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }

}