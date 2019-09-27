<?php


namespace app\components;


use app\models\Mailbox;
use app\models\Newcode;
use Yii;

/**
 * 加减统计
 *
 * Class AdditionSubtractionStatistics
 * @package app\components
 */
class AdditionSubtractionStatistics
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

    // 报警周期
    private $num;

    public function __construct($cp_type, $_intNum = 4)
    {
        $this->num = $_intNum;
        $this->cp_type = $cp_type;
        $strPrefix = $this->cp_type_prefix[$cp_type];
        ini_set('memory_limit', '888M');
        echo $this->cp_type_arr[$this->cp_type] . ' - [新时时彩] 11选5 和差统计报警 ' . "\r\n";

        $this->play($strPrefix);
    }

    public function play()
    {
        // 近50期 开奖号码
        $code = Newcode::find()->where(['type'=>$this->cp_type])->orderBy('time DESC')->limit(50)->all();
        sort($code);

        // 上期 和
        $intPreTheSum = 0;
        // 上期 差
        $intPreDifference = 0;

        // 是否连续包含
        $boolContinuity = false;
        // 连续几次
        $intContinuity = 0;

        // 期数
        $intNumber = 0;

        // 是否清空过
        $boolEmpty = false;

        // 连续数
        $aryContinuity = [];

        foreach ($code as $key => $objCode) {
            echo "本期开奖号码: {$objCode->one} {$objCode->two} {$objCode->three} {$objCode->four} {$objCode->five} \r\n";
            $this->strHtmlLog .= "本期开奖号码: {$objCode->one} {$objCode->two} {$objCode->three} {$objCode->four} {$objCode->five} <br/>";

            echo "上期 和 {$intPreTheSum} 差 {$intPreDifference} \r\n";
            $this->strHtmlLog .= "上期 和 {$intPreTheSum} 差 {$intPreDifference} <br/>";

            // 改变前的开奖号码
            $intOriginalOne = $objCode->one;
            $intOriginalTwo = $objCode->two;
            $intOriginalThree = $objCode->three;
            $intOriginalFour = $objCode->four;
            $intOriginalFive = $objCode->five;

            $one = $objCode->one;
            $two = $objCode->two;
            $three = $objCode->three;
            $four = $objCode->four;
            $five = $objCode->five;

            // 和
            $intTheSum = 0;
            // 差
            $intDifference = 0;

            // 11  1 这种特殊情况就这样处理算了用1+1=2 然后用11-1=10，只有这个数字会这样
            if (($two == 11 && $three == 1) ||($three == 11 && $two == 1)) {
                $intTheSum = 1 + 1;
                $intDifference = 11 -1;
                echo "本期 和 2 差 10 \r\n";
                $this->strHtmlLog .= "本期 和 2 差 10 <br/>";
            }else {

                if ($two == 11 || $three == 11) {
                    $two == 11 ? $two = 1 : null;
                    $three == 11 ? $three = 1 : null;
                }

                // 总和 = 两个数相加
                $intTheSum = $two + $three;
                $intTheSum > 11 ? $intTheSum = $intTheSum - 11 : null;

                // 大数
                $intBig = $two > $three ? $two : $three;
                // 小数
                $intSmall = $two < $three ? $two : $three;

                // 差 = 大数 减 小数
                $intDifference = $intBig - $intSmall;

                echo "本期 和 {$intTheSum} 差 {$intDifference} \r\n";
                $this->strHtmlLog .= "本期 和 {$intTheSum} 差 {$intDifference} <br/>";
            }

            // 包含 几位
            $intShow = 0;
            if ( ($intPreTheSum && $intPreDifference) && ($intOriginalOne == $intPreTheSum || $intOriginalTwo == $intPreTheSum || $intOriginalThree == $intPreTheSum || $intOriginalFour == $intPreTheSum || $intOriginalFive == $intPreTheSum) ) {
                $intShow += 1;
            }
            if ( ($intPreTheSum && $intPreDifference) && ($intOriginalOne == $intPreDifference || $intOriginalTwo == $intPreDifference || $intOriginalThree == $intPreDifference || $intOriginalFour == $intPreDifference || $intOriginalFive == $intPreDifference) ) {
                $intShow += 1;
            }
            if ($intShow == 1) {
                $boolContinuity = true;
                $intContinuity += 1;

                echo "本期 包含上期 和 {$intPreTheSum} 差 {$intPreDifference} 其中一位 \r\n";
                echo "连续{$intContinuity} \r\n";
                $this->strHtmlLog .= "本期 包含 和 {$intPreTheSum} 差 {$intPreDifference} 其中一位 <br/>";
                $this->strHtmlLog .= "连续{$intContinuity} <br/>";
            }else {
                if ($intContinuity > 0) {
                    $aryContinuity[] = $intContinuity;
                }
                if ($intContinuity) {
                    $intNumber += 1;
                }
                $boolContinuity = false;
                $intContinuity = 0;

                echo "本期 不包含 \r\n";
                echo "周期{$intNumber} \r\n";
                $this->strHtmlLog .= "本期 不包含 <br/>";
                $this->strHtmlLog .= "周期{$intNumber} <br/>";
            }

            /*
            if (
             ( ($intPreTheSum && $intPreDifference) && ($one == $intPreTheSum || $two == $intPreTheSum || $three == $intPreTheSum || $four == $intPreTheSum || $five == $intPreTheSum) )
                ||
             ( ($intPreTheSum && $intPreDifference) && ($one == $intPreDifference || $two == $intPreDifference || $three == $intPreDifference || $four == $intPreDifference || $five == $intPreDifference) )
            ) {
                $boolContinuity = true;
                $intContinuity += 1;

                echo "本期 包含 和 {$intPreTheSum} 差 {$intPreDifference} 其中一位 \r\n";
                echo "连续{$intContinuity} \r\n";
                $this->strHtmlLog .= "本期 包含 和 {$intPreTheSum} 差 {$intPreDifference} 其中一位 <br/>";
                $this->strHtmlLog .= "连续{$intContinuity} <br/>";
            }else {
                if ($intContinuity) {
                    $intNumber += 1;
                }
                $boolContinuity = false;
                $intContinuity = 0;

                echo "本期 不包含 \r\n";
                echo "周期{$intNumber} \r\n";
                $this->strHtmlLog .= "本期 不包含 <br/>";
                $this->strHtmlLog .= "周期{$intNumber} <br/>";
            }
            */

            /*
            if ($intNumber == $this->num && $boolContinuity == false && ($key != count($code) - 1) ) {
                $intNumber = 0;
                $boolEmpty = true;

                echo "清空本轮统计 \r\n";
                echo "周期{$intNumber} \r\n";
                $this->strHtmlLog .= "清空本轮统计 <br/>";
                $this->strHtmlLog .= "周期{$intNumber} <br/>";
            }
            */

            // 上期 和
            $intPreTheSum = $intTheSum;
            // 上期 差
            $intPreDifference = $intDifference;
        }

        $intQishu = count($aryContinuity);
        // 报警
        if ($intQishu >= 6 && $intContinuity == 1) {
            echo json_encode($aryContinuity). " \r\n";
            echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 期数 {$intQishu} 和差统计报警 ". json_encode(array_slice($aryContinuity,-6)) ."\r\n";

            $strMail = $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 期数 {$intQishu} 和差统计报警 ". json_encode(array_slice($aryContinuity,-6)) ."<br/>";
            //$strMail .= $this->strHtmlLog;
            $this->send_mail($strMail);
        }

        /*
        // 报警
        if ($intContinuity > 0 && $boolEmpty == true && $intNumber == 0) {
            echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 和差统计报警 " ."\r\n";

            $strMail = "11选5 和差 报警提示 连续".  json_encode($aryContinuity) ."<br/>";
            $strMail .= $this->cp_type_arr[$this->cp_type]." - [新时时彩] 和差 统计报警:" ."<br/>";
            $strMail .= $this->strHtmlLog;
            $this->send_mail($strMail);
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
                echo $this->cp_type_arr[$this->cp_type]."报警 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo $this->cp_type_arr[$this->cp_type]."报警 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }

}