<?php


namespace app\components;


use app\models\Mailbox;
use app\models\Newcode;
use app\models\Newcodedata;
use Yii;

/**
 * 新时时彩 加法剑法 玩法
 *
 * Class AdditionSubtraction
 * @package app\components
 */
class AdditionSubtraction
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

    public function __construct($cp_type)
    {
        $this->cp_type = $cp_type;
        $strPrefix = $this->cp_type_prefix[$cp_type];
        ini_set('memory_limit','888M');
        echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] 加减玩法 '."\r\n";
        $this->play($strPrefix);
    }

    /**
     * 玩法
     *
     * @param string $_strPrefix 彩票前缀
     */
    public function play($_strPrefix) {
        $code = Newcode::find()->where(['type'=>$this->cp_type])->orderBy('time DESC')->limit(2)->all();
        sort($code);

        $intTheSum = 0;
        $intDifference = 0;
        foreach ($code as $key => $ary) {
            if ($key == 0) {
                echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] 第一期开奖号码为:'. " {$ary->two} {$ary->three} " ."\r\n";
                $this->strLog .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] 第一期开奖号码为:'. " {$ary->two} {$ary->three} " ."\r\n";

                $two = $ary->two;
                $three = $ary->three;

                if ($two == 11) {
                    echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] 第二位号码为:'. " {$ary->two} 更改值为1 " ."\r\n";
                    $this->strLog .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] 第二位号码为:'. " {$ary->two} 更改值为1 " ."\r\n";
                }else if ($three == 11) {
                    echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] 第三位号码为:'. " {$ary->two} 更改值为1 " ."\r\n";
                    $this->strLog .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] 第三位号码为:'. " {$ary->two} 更改值为1 " ."\r\n";
                }

                if ($two == 11 || $three == 11) {
                    $two == 11 ? $two = 1 : null;
                    $three == 11 ? $three = 1 : null;
                }

                echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] 计算前第二位的值是:'. " $two " ."\r\n";
                $this->strLog .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] 计算前第二位的值是:'. " $two " ."\r\n";
                echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] 计算前第三位的值是:'. " $three " ."\r\n";
                $this->strLog .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] 计算前第三位的值是:'. " $three " ."\r\n";

                // 总和 = 两个数相加
                $intTheSum = $two + $three;
                echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " {$two} + {$three} = {$intTheSum} " ."\r\n";
                $this->strLog .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " {$two} + {$three} = {$intTheSum} " ."\r\n";
                $intTheSum > 11 ? $intTheSum = $intTheSum - 11 : null;
                echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " {$intTheSum} 是否 大于11 " ."\r\n";
                $this->strLog .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " {$intTheSum} 是否 大于11 " ."\r\n";

                $intBig = $two > $three ? $two : $three;
                $intSmall = $two < $three ? $two : $three;

                // 差 = 大数 减 小数
                $intDifference = $intBig - $intSmall;
                echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 最大数 - 最小数 {$intBig} - {$intSmall} = {$intDifference} " ."\r\n";
                $this->strLog .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 最大数 - 最小数 {$intBig} - {$intSmall} = {$intDifference} " ."\r\n";

            } else {
                echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] 第二期开奖号码为:'. "{$ary->one} {$ary->two} {$ary->three} {$ary->four} {$ary->five}" ."\r\n";
                $this->strLog .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] 第二期开奖号码为:'. "{$ary->one} {$ary->two} {$ary->three} {$ary->four} {$ary->five}" ."\r\n";

                if (
                    ($ary->one == $intTheSum || $ary->two == $intTheSum || $ary->three == $intTheSum || $ary->four == $intTheSum || $ary->five == $intTheSum)
                    &&
                    ($ary->one == $intDifference || $ary->two == $intDifference || $ary->three == $intDifference || $ary->four == $intDifference || $ary->five == $intDifference)
                ) {
                    // 邮件报警了
                    echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 包含 {$intTheSum} {$intDifference} " ."\r\n";
                    $this->strLog .= $this->cp_type_arr[$this->cp_type].' - [新时时彩] '. " 包含 {$intTheSum} {$intDifference} " ."\r\n";

                    $strMail = '加减玩法 报警提示'."\r\n";;
                    $strMail .= $this->strLog;
                    $this->send_mail($strMail);
                }
            }
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