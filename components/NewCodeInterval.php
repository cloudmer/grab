<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-6-22
 * Time: 下午1:39
 */

namespace app\components;


use app\models\Newcode;
use app\models\Mailbox;
use Yii;

class NewCodeInterval
{

    /* 彩票类型 */
    protected $cptype;

    /* 彩票名 */
    protected $cp_name;

    /* 彩票别名 */
    protected $cp_alias_name;

    /* 数据包 */
    protected $config;

    /* 当前数据包 */
    protected $contents;

    /* 当前数据包 别名 */
    protected $alias;

    /* 当前数据包 报警期数 */
    protected $number;

    /* 当前数据包 报警开始时间 */
    protected $start;

    /* 当前数据包 报警结束时间 */
    protected $end;

    /* 当前数据包 报警状态 */
    protected $status;

    /* 邮件内容 */
    protected $danger_email_contents;

    /* 最新开奖号码 */
    protected $codes;

    public function __construct($type)
    {
        $this->cptype = $type;
        //获取后台添加的预定报警号码
        $this->get_config();
        // 暂无配置项
        if(!$this->config){
            return;
        }
        //获取开奖号
        $this->get_codes();
    }

    /**
     * 获取添加的预定报警号码
     */
    private function get_config(){
        $this->cptype == 1 ? $this->cp_name = '江西' : false;
        $this->cptype == 2 ? $this->cp_name = '广东' : false;
        $this->cptype == 3 ? $this->cp_name = '山东' : false;
        $this->cptype == 4 ? $this->cp_name = '上海' : false;

        $this->cptype == 1 ? $this->cp_alias_name = '江' : false;
        $this->cptype == 2 ? $this->cp_alias_name = '广' : false;
        $this->cptype == 3 ? $this->cp_alias_name = '山' : false;
        $this->cptype == 4 ? $this->cp_alias_name = '海' : false;

        $config = \app\models\Newcodeinterval::find()->where(['type' => $this->cptype])->all();
        if(!$config){
            echo '系统还未添加 -['.$this->cp_name.'彩票] 间隔数据号码,请先添加'."\r\n";
        }
        $this->config = $config;
    }

    /**
     * 获取开奖号
     */
    private function get_codes(){
        //查询最新300期开奖号码
        $codes = Newcode::find()->where(['type' => $this->cptype])->orderBy('time DESC')->all();
        sort($codes);
        $this->codes = $codes;

        //获取报警配置项内的警报期数
        foreach ($this->config as $key=>$val){
            $dataTxts = str_replace("\r\n", ' ', $val->contents); //将回车转换为空格
            $dataArr = explode(' ',$dataTxts);
            $dataArr = array_filter($dataArr);
            $dataArr = array_chunk($dataArr,5);

            $this->contents      = $dataArr;             //数据包 数组
            $this->alias         = $val['alias'];         //数据包别名
            $this->number        = $val['number'];        //数据包报警期数
            $this->start         = $val['start'];         //数据包报警开始时间
            $this->end           = $val['end'];           //数据包报警结束时间
            $this->status           = $val['status'];           //数据包报警结束时间
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
        if ($this->status == false){
            echo $this->cp_name.' 数据包别名: '.$this->alias. " - 时时彩报警关闭 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }
        //检查是否在报警时段
        if( ($this->start && $this->end) && (date('H') < $this->start || date('H') > $this->end) ){
            //当前非报警时段
            echo $this->cp_name.' 数据包别名: '.$this->alias. " - 时时彩报警通知非接受时段 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }

        //检查当前期数内 是否未中奖 报警
        $this->inspect_alarm();
    }

    private function inspect_alarm(){
        $number = 0;
        $reference = false;
        $log_html = '<br/>';
        foreach ($this->codes as $key=>$val){
            $log_html .= ' 数据包别名:'. $this->alias . '最新一期开奖号码:' .$val->one.$val->two.$val->three.$val->four.$val->five;
            $code = array($val->one, $val->two, $val->three, $val->four, $val->five);
            sort($code);
            $log_html .= ' 排序后:'. $code[0] . $code[1] . $code[2] . $code[3] . $code[4] ;
            $in = in_array($code, $this->contents);

            //开奖号没有上一期 开奖数据 参考对象 and 开奖号出现在数据包里
            if(!$reference && $in){
                $number = $number + 1;
                $log_html .= ' 上一期 不在此数据包内 并且 本期在 数据包内 +1 = '.$number;
            }else if($reference && $in){
                //有上一期 开奖数据 参考对象 and 开奖号出现在数据包里
                $number = 0;
                $number = $number + 1;
                $log_html .= ' 上一期 在此数据包内 并且 本期也在数据包内 清零 再 +1 = '.$number;
            }

            $in ? $reference = true : $reference = false;
            $log_html .= '<br/>';
        }

        //最近的一期有数据包里的数据 才报警
        !$reference ? $number = 0 : false;

        if ($reference && $number >= $this->number){
            $this->danger_email_contents .= '11选5 通知类型:' .$this->cp_alias_name . ' 包含-数据包别名:' .$this->alias . ' 期数:'.$number . ' 报警';
            $this->danger_email_contents .= $log_html;
        }

        if ($this->danger_email_contents){
            $this->send_mail($this->danger_email_contents);
        }
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
                echo $this->cp_name."11选5 ".$this->cp_name." 包含-数据包报警 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo $this->cp_name."11选5 ".$this->cp_name." 包含-数据包报警 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }

}