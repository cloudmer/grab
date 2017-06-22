<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-6-17
 * Time: 下午1:20
 */

namespace app\components;

use app\models\Log;
use app\models\Mailbox;
use app\models\Newcode;
use app\models\Newcodeanalysis;
use app\models\Newcodedata;
use Yii;

class NewGrab
{

    private $cp_type;

    private $cp_type_arr = array(
        '1' => '江西',
        '2' => '广东',
        '3' => '山东',
        '4' => '上海',
    );

    private $cp_url_arr = array(
        '1' => 'http://cp.360.cn/dlcjx/?r_a=7zIRFz',
        '2' => 'http://cp.360.cn/gd11/?r_a=yiiEJb',
        '3' => 'http://cp.360.cn/yun11/?r_a=JfMbIz',
        '4' => 'http://cp.360.cn/sh11?r_a=7zaqqi'
    );

    private $email_contents = false;

    public function __construct($cp_type)
    {
        $this->cp_type = $cp_type;
        $url = $this->cp_url_arr[$cp_type];
        ini_set('memory_limit','888M');
        include_once('simplehtmldom_1_5/simple_html_dom.php');
        $this->grab = new \simple_html_dom();
        $content = $this->getHtml($url);
        $this->grab->load($content);
        $this->html_str($url);
        $this->grab->clear();
    }

    function getHtml($url,$encoded="UTF-8"){
        //zlib 解压 并转码
        $data = false;
        $data = @file_get_contents("compress.zlib://".$url);
        if($data){
            $encode = mb_detect_encoding($data, array('ASCII','UTF-8','GB2312',"GBK",'BIG5'));
            $content = iconv($encode,$encoded,$data);
            return $content;
        }else{
            //抓取网页失败 记录日志
            $urlName = $this->cp_type_arr[$this->cp_type];
            $logModel = new Log();
            $logModel->type = 0;
            $logModel->content = $this->cp_type_arr[$this->cp_type].'.开奖信息抓取失败';
            $logModel->time = time();
            $logModel->save();
            echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] 开奖信息抓取失败,请尽快通知网站管理员'."\r\n";
            return;
        }
    }

    function html_str($url){
        $qihao = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',0)->plaintext;
        $code = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',1)->plaintext;
        if($code != '--'){
            // == '--' 正在开奖中
            $isKaiJiang = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->plaintext;
            if($isKaiJiang != '--' && $isKaiJiang != '开奖中'){
                //能读取到数据
                $sizeProportion = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->find('span',0)->plaintext;
                $jioubili = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->find('span',1)->plaintext;
                $codeArr = explode(" ",$code);
                list($one,$two,$three,$four,$five) = $codeArr;

                $result = Newcode::findOne(['qihao'=>$qihao,'type'=>$this->cp_type]);
                if($result){
                    echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] 最新数据已经采集过了'."\r\n";
                    return;
                }

                $newcodeModel = new Newcode();
                $newcodeModel->qihao = $qihao;
                $newcodeModel->one = $one;
                $newcodeModel->two = $two;
                $newcodeModel->three = $three;
                $newcodeModel->four = $four;
                $newcodeModel->five = $five;
                $newcodeModel->type = $this->cp_type;
                $newcodeModel->time = time();
                if(!$newcodeModel->validate() || !$newcodeModel->save()){
                    echo $this->cp_type_arr[$this->cp_type].' - [新时时彩] 数据存储失败'."\r\n";
                    return;
                }
                sort($codeArr);
                $this->analysis($newcodeModel->id , $codeArr);

                $this->alert();

                new NewCodeInterval($this->cp_type);

            }else{
                echo $this->cp_type_arr[$this->cp_type].'等待开奖...'."\r\n";
            }

        }else{
            echo $this->cp_type_arr[$this->cp_type].' -[新时时彩] 等待开奖...'."\r\n";
            return;
        }
    }

    private function analysis($newcode_id, $kjhm){
        //查询数据包
        $data = Newcodedata::find()->where(['type'=>$this->cp_type])->all();
        foreach ($data as $key=>$val) {
            $newcodedata_id = $val->id;
            $contents = $val->contents;

            $dataTxts = str_replace("\r\n", ' ', $contents); //将回车转换为空格
            $dataArr = explode(' ',$dataTxts);
            $dataArr = array_filter($dataArr);
            $dataArr = array_chunk($dataArr,5);
            in_array($kjhm, $dataArr) ? $lucky = 1 : $lucky = 0;

            $analysisModel = new Newcodeanalysis();
            $analysisModel->lucky = $lucky;
            $analysisModel->newcodedata_id = $newcodedata_id;
            $analysisModel->newcode_id = $newcode_id;
            $analysisModel->save();
        }
    }

    //警报
    private function alert(){
        $data = Newcodedata::find()->where(['type'=>$this->cp_type])->all();
        foreach ($data as $key=>$val){
            $number = $val->number;
            $code = Newcode::find()->where(['type'=>$this->cp_type])->orderBy('time DESC')->limit(100)->all();
            sort($code);
            if (count($code) < $number){
                //不满足报警条件
                echo "不满足报警条件\r\n";
                continue;
            }

            $status = false;
            $num = 0;
            foreach ($code as $k=>$v){
                if($v->getAnalysis($val->id)->one()->lucky == 1){
                    $num = 0;
                }else{
                    $num = $num + 1;
                }
            }

            if($num >= $number){
                $this->email_contents .= $this->cp_type_arr[$this->cp_type]. ' 数据包别名:' . $val->alias . ' 期数: 已经连续 ' . $num . ' 未开 报警'."<br/>";
            }
        }

        if ($this->email_contents){
            $this->send_mail($this->email_contents);
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