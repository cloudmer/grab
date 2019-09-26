<?php


namespace app\components;


use app\models\Mailbox;
use app\models\Newcode;
use Yii;

/**
 * 和差统计报警
 *
 * Class FixedNumber
 * @package app\components
 */
class FixedNumber
{

    private $cp_type;

    private $cp_type_arr = array(
        '1' => '江西',
        '2' => '广东',
        '3' => '山东',
        '4' => '上海',
    );

    private $cp_type_prefix = [
        '1' => 'jx_',
        '2' => 'gd_',
        '3' => 'sd_',
        '4' => 'sh_',
    ];

    private $strLog;

    private $strHtmlLog;

    // 报警号码
    private $number;

    // 报警周期
    private $num;

    public function __construct($cp_type, $_intNumber, $_intNum = 4)
    {
        $this->number = $_intNumber;
        $this->num = $_intNum;
        $this->cp_type = $cp_type;
        $strPrefix = $this->cp_type_prefix[$cp_type];
        ini_set('memory_limit','888M');
        echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] 11选5 和差统计报警 '."\r\n";

        $this->play($strPrefix);
    }

    public function play() {
        // 近200期 开奖号码
        $code = Newcode::find()->where(['type'=>$this->cp_type])->orderBy('time DESC')->limit(200)->all();
        sort($code);

        // 连续出现包含号码几次了
        $intContinuity = 0;
        // 上期是否包含号码
        $intPrevious = false;
        // 包含号码出现次数
        $aryCode = [];
        // 上轮统计次数
        $aryPreCode = [];

        $boolEmpty = false;

        foreach ($code as $key => $objCode) {
            echo "本期开奖号码: {$objCode->one} {$objCode->two} {$objCode->three} {$objCode->four} {$objCode->five} \r\n";
            $this->strHtmlLog .= "本期开奖号码: {$objCode->one} {$objCode->two} {$objCode->three} {$objCode->four} {$objCode->five} <br/>";
            if ( $objCode->one == $this->number || $objCode->two == $this->number || $objCode->three == $this->number || $objCode->four == $this->number || $objCode->five == $this->number ) {
                $intContinuity += 1;
                $intPrevious = true;

                $this->strHtmlLog .= "包含 {$this->number} 号码 {$intContinuity} 次 <br/>";
                echo "包含 {$this->number} 号码 {$intContinuity} 次 \r\n";
            } else {
                if ($intContinuity > 0) {
                    $aryCode[] = $intContinuity;
                }
                $intContinuity = 0;
                $intPrevious = false;

                $this->strHtmlLog .= "不包含号码 <br/>";
            }

            if ( ( count($aryCode) >= $this->num ) && ( $key != count($code) - 1 ) &&  ($intPrevious == false) ) {
                $aryPreCode = $aryCode;

                $this->strHtmlLog .= "清空本轮 重新统计 <br/>";
                echo "清空本轮 重新统计 \r\n";
                /*
                echo var_dump($this->number). "\r\n";
                echo var_dump($this->num). "\r\n";
                echo var_dump($aryCode). "\r\n";
                echo var_dump($key). "\r\n";
                echo var_dump($intPrevious). "\r\n";
                echo var_dump(count($code)). "\r\n";
                */

                /*
                echo var_dump(count($aryCode) >= $this->num). "\r\n";
                echo var_dump($key != count($code) - 1). "\r\n";
                echo var_dump($intPrevious == false). "\r\n";
                */

                $aryCode = [];
                $boolEmpty = true;
            }
        }

        /*
        // 报警
        if (count($aryCode) == $this->num && $intPrevious == true) {
            echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 和差{$this->number}统计报警  " ."\r\n";
            echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 和差{$this->number}统计报警  ". "\r\n";

            $strMail = "11选5 和差 {$this->number} 报警提示"."<br/>";
            $strMail .= $this->cp_type_arr[$this->cp_type]." - [新时时彩] 和差{$this->number}统计报警:" ."<br/>";
            $strMail .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 统计出现了 ". json_encode($aryCode) ."<br/>";
            $strMail .= $this->strHtmlLog;
            $this->send_mail($strMail);
        }
        */

        // 报警
        if ($boolEmpty == true && count($aryCode) == 1) {
            echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 和差{$this->number}统计报警  " ."\r\n";

            $strMail = "11选5 和差 {$this->number} 报警提示"."<br/>";
            $strMail .= $this->cp_type_arr[$this->cp_type]." - [新时时彩] 和差{$this->number}统计报警:" ."<br/>";
            $strMail .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 统计出现了 " . json_encode($aryPreCode) ."<br/>";
            $strMail .= $this->strHtmlLog;
            $this->send_mail($strMail);
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
                echo $this->cp_type_arr[$this->cp_type]."报警 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo $this->cp_type_arr[$this->cp_type]."报警 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }

}