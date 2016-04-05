<?php
namespace app\components;
use app\models\Analysis;
use app\models\Analysisold;
use app\models\Code;
use app\models\Codeold;
use app\models\Comparison;
use app\models\Configure;
use app\models\Log;
use app\models\Mailbox;
use Yii;
use yii\helpers\Url;

class GrabOld{
    private $grab;

    private $urlArr = array(
        '重庆 - [老时时彩] '=>'http://cp.360.cn/ssccq/?r_a=26ruYj',
    );

    private $codeType = array(
        'http://cp.360.cn/ssccq/?r_a=26ruYj'=>'1',
    );

    private $shishicaiUrl = array(
        '重庆 - [老时时彩] '=>'http://www.shishicai.cn/cqssc/touzhu/',
    );

    public function __construct($url){
        ini_set('memory_limit','888M');
        include_once('simplehtmldom_1_5/simple_html_dom.php');
        $this->grab = new \simple_html_dom();
        $content = $this->getHtml($url);
        $this->grab->load($content);
        $this->html_str($url);
        $this->grab->clear();
    }

    private function getHtml($url,$encoded="UTF-8"){
        //zlib 解压 并转码
        $data = false;
        $data = @file_get_contents("compress.zlib://".$url);
        if($data){
            $encode = mb_detect_encoding($data, array('ASCII','UTF-8','GB2312',"GBK",'BIG5'));
            $content = iconv($encode,$encoded,$data);
            return $content;
        }else{
            //抓取网页失败 记录日志
            $urlName = array_keys($this->urlArr,$url);
            $logModel = new Log();
            $logModel->type = 0;
            $logModel->content = $urlName[0].'.开奖信息抓取失败';
            $logModel->time = time();
            $logModel->save();
            echo $urlName[0].' - [新时时彩] 开奖信息抓取失败,请尽快通知网站管理员<br/>';
            return;
        }
    }

    private function html_str($url){
        $qihao = $this->grab->find('div[class=aside]',0)->find('h3',0)->find('em',0)->plaintext;
        $code = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',1)->plaintext;

        if($code != '--'){
            $isKaiJiang = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->plaintext;
            if($isKaiJiang != '--' && $isKaiJiang != '开奖中'){
                //能读取到数据
                $h3_shape = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->find('span',0)->plaintext;
                $h3_size = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->find('span',1)->plaintext;
                $h3_jiou = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->find('span',2)->plaintext;

                $h2_shape = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',3)->find('span',0)->plaintext;
                $h2_10wei = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',3)->find('span',1)->plaintext;
                $h2_gewei = $this->grab->find('div[class=aside]',0)->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',3)->find('span',2)->plaintext;

                /*
                echo '开奖期号:'.$qihao;
                echo '<br/>';
                echo '开奖号码:'.$code;
                echo '<br/>';
                echo '<br/>';

                echo '后3形态:'.$h3_shape;
                echo '<br/>';
                echo '后3大小比:'.$h3_size;
                echo '<br/>';
                echo '后3奇偶比:'.$h3_jiou;
                echo '<br/>';
                echo '<br/>';

                echo '后2形态:'.$h2_shape;
                echo '<br/>';
                echo '后2大小比:'.$h2_10wei;
                echo '<br/>';
                echo '后2奇偶比:'.$h2_gewei;
                */

                $result = Codeold::findOne(['qishu'=>$qihao,'type'=>$this->codeType[$url]]);
                if($result){
                    $urlName = array_keys($this->urlArr,$url);
                    echo $urlName[0].' 最新数据已经采集过了<br/>';
                    return;
                }

                $model = new Codeold();
                $model->qishu = $qihao;
                $model->code = $code;
                $model->after_three_shape = $h3_shape;
                $model->after_three_size = $h3_size;
                $model->after_three_jiou = $h3_jiou;
                $model->after_two_shape = $h2_shape;
                $model->after_two_tens_place = $h2_10wei;
                $model->after_two_the_unit = $h2_gewei;
                $model->type = $this->codeType[$url];
                $model->time = time();
                if($model->validate() && $model->save()){
                    $urlName = array_keys($this->urlArr,$url);
                    $logModel = new Log();
                    $logModel->type = 1;
                    $logModel->content = $urlName[0].'.开奖信息抓取成功';
                    $logModel->time = time();
                    $logModel->save();
                    echo $urlName[0].'.开奖信息抓取成功<br/>';
                    $this->find($qihao,$urlName[0],$code,$model->id);
                    return;
                }

            }else{
                $urlName = array_keys($this->urlArr,$url);
                echo $urlName[0].'等待开奖...<br/>';
            }
        }else{
            $urlName = array_keys($this->urlArr,$url);
            echo $urlName[0].'等待开奖...<br/>';
        }
    }

    private function find($qihao,$urlName,$code,$code_id){
        //数据分析
        $config = Configure::findOne(['type'=>2]); //旧时时彩 系统报警配置
        $model = Comparison::findOne(['type'=>2]); //旧时时彩 数据本
        $data = $model->txt;
        //记录中奖与未中奖号码
        $dataTxts = str_replace("\r\n", ' ', $data); //将回车转换为空格
        $dataArr = explode(' ',$dataTxts);
        $dataArr = array_filter($dataArr);

        //将中奖号码前三 后三
        $codeTxts = str_replace(" ", '', $code);
        $qian3 = $codeTxts[0].$codeTxts[1].$codeTxts[2];
        $center3 = $codeTxts[1].$codeTxts[2].$codeTxts[3];
        $hou3 = $codeTxts[2].$codeTxts[3].$codeTxts[4];
        $qian3 = intval($qian3);
        $center3 = intval($center3);
        $hou3 = intval($hou3);

        //当前开奖号码 对比 数据库
        $qian3_lucky = array();
        $qian3_regret = $dataArr;
        $center3_lucky = array();
        $center3_regret = $dataArr;
        $hou3_lucky = array();
        $hou3_regret = $dataArr;
        foreach($dataArr as $key=>$val){
            //前三对比
            if(intval($val) == $qian3){
                array_push($qian3_lucky,$val); // 添加到前三中奖数据里
                unset($qian3_regret[$key]);
            }
            //中三对比
            if(intval($val) == $center3){
                array_push($center3_lucky,$val); // 添加到前三中奖数据里
                unset($center3_regret[$key]);
            }
            //后三对比
            if(intval($val) == $hou3){
                array_push($hou3_lucky,$val); // 添加到后三中奖数据里
                unset($hou3_regret[$key]);
            }
        }

        //分析的数据转换成 上传数据本的格式
        $qian3_lucky_txt = null;
        foreach($qian3_lucky as $key=>$val){
            $qian3_lucky_txt .= $val."\r\n";
        }
        $qian3_regret_txt = null;
        foreach($qian3_regret as $key=>$val){
            $qian3_regret_txt .= $val."\r\n";
        }
        $center3_lucky_txt = null;
        foreach($center3_lucky as $key=>$val){
            $center3_lucky_txt .= $val."\r\n";
        }
        $center3_regret_txt = null;
        foreach($center3_regret as $key=>$val){
            $center3_regret_txt .= $val."\r\n";
        }
        $hou3_lucky_txt = null;
        foreach($hou3_lucky as $key=>$val){
            $hou3_lucky_txt .= $val."\r\n";
        }
        $hou3_regret_txt = null;
        foreach($hou3_regret as $key=>$val){
            $hou3_regret_txt .= $val."\r\n";
        }

        //分析后的数据 存入数据库
        $analysisold = new Analysisold();
        $analysisold->code_id = $code_id;
        $analysisold->front_three_lucky_txt = $qian3_lucky_txt;
        $analysisold->front_three_regret_txt = $qian3_regret_txt;
        $analysisold->center_three_lucky_txt = $center3_lucky_txt;
        $analysisold->center_three_regret_txt = $center3_regret_txt;
        $analysisold->after_three_lucky_txt = $hou3_lucky_txt;
        $analysisold->after_three_regret_txt = $hou3_regret_txt;
        $analysisold->data_txt = $data;
        $analysisold->time = time();
        $analysisold->save();

        $config = Configure::findOne(['type'=>2]); //旧时时彩 系统报警配置
        if($config->state == 1){
            //系统开启邮件 通知
            if(date('H',time()) > intval($config->start_time) && date('H',time()) < intval($config->end_time) ) {
//            if(true ) {
                //报警时间段内
                if($config->forever == 1){
                    //每一期 邮件通知打开
                    $cfg = array(
                        'type'=>1,
                        'qihao'=>$qihao,
                        'code'=>$code,
                        'urlName'=>$urlName,
                        'qian3_lucky_txt'=>$qian3_lucky_txt,
                        'qian3_regret_txt'=>$qian3_regret_txt,
                        'center3_lucky_txt'=>$center3_lucky_txt,
                        'center3_regret_txt'=>$center3_regret_txt,
                        'hou3_lucky_txt'=>$hou3_lucky_txt,
                        'hou3_regret_txt'=>$hou3_regret_txt
                    );
                    $this->send($cfg);
                }
                // 用户设置 几期都未中奖 报警通知
                $NewestCodes = Codeold::find()->orderBy('time DESC')->limit($config->regret_number)->all();
                if(count($NewestCodes) == $config->regret_number){
                    $codeQian3Lucky = true;
                    $codeCenter3Lucky = true;
                    $codeHou3Lucky = true;
                    $q3_number = 0;
                    $c3_number = 0;
                    $h3_number = 0;
                    foreach($NewestCodes as $codeold){
                        if(!empty($codeold->analysisolds->front_three_lucky_txt)){
                            //前三有中奖
                            $codeQian3Lucky = false;
                            $q3_number += 1;
                        }
                        if(!empty($codeold->analysisolds->center_three_lucky_txt)){
                            //中三有中奖
                            $codeCenter3Lucky = false;
                            $c3_number += 1;
                        }
                        if(!empty($codeold->analysisolds->after_three_lucky_txt)){
                            //后三有中奖
                            $codeHou3Lucky = false;
                            $h3_number += 1;
                        }
                    }

                    if($codeQian3Lucky || $codeHou3Lucky){
                        //发送报警通知 当前 $config->regret_number 内 都未中奖
                        $cfg = array(
                            'type'=>2,
                            'regret_number'=>$config->regret_number,
                            'NewestCodes'=>$NewestCodes, //最新三期 未中奖 数据
                            'q3'=>$q3_number,
                            'c3'=>$c3_number,
                            'h3'=>$h3_number
                        );
                        $this->send($cfg);
                    }
                }
            }
        }
    }

    private function send($arr){
        $recipientsMailboxs = Mailbox::find()->where(['type'=>1])->all();
        $addresserMailbox = Mailbox::find()->where(['type'=>0])->all();
        $email = $addresserMailbox[array_rand($addresserMailbox,1)];

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

        if($arr['type'] == 1){
            $arr['qian3_lucky_txt'] ? $luckyQian3Str = '<br/>'.str_replace("\r\n", '<br/>', $arr['qian3_lucky_txt']) : $luckyQian3Str = '没有中奖 T.T';
            $arr['center3_lucky_txt'] ? $luckyCenter3Str = '<br/>'.str_replace("\r\n", '<br/>', $arr['center3_lucky_txt']) : $luckyCenter3Str = '没有中奖 T.T';
            $arr['hou3_lucky_txt'] ? $luckyHou3Str = '<br/>'.str_replace("\r\n", '<br/>', $arr['hou3_lucky_txt']) : $luckyHou3Str = '没有中奖 T.T';
            $html = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
                .'<a href="'.$this->shishicaiUrl[$arr['urlName']].'">传送门--->'.$arr['urlName'].'</a><br/>'
                .'当前彩种:'.$arr['urlName'].' - [新时时彩]<br/>'
                .'当前期号:'.$arr['qihao'] .'<br/>'
                .'开奖号码:'.$arr['code'].'<br/>'
                .'前三中奖:'.$luckyQian3Str .'<br/>'
                .'中三中奖:'.$luckyCenter3Str .'<br/>'
                .'后三中奖:'.$luckyHou3Str;
        }

        if($arr['type'] == 2){
            $html = '老-N-'.$arr['regret_number'].'   前3&nbsp;Y'.$arr['q3'].'次'.'   中3&nbsp;Y'.$arr['c3'].'次'.'   后3&nbsp;Y'.$arr['h3'].'次';
            /*
            $html = '报警提醒:<br/>当前'.$arr['regret_number']
                .'期内 前三没有一组中奖号码,或者,后三没有一组中奖号码！！！！！！<br/>'
                .'<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
                .'以下是彩种信息:<br/><br/>';

            foreach($arr['NewestCodes'] as $newstcode){
                $newstcode->analysisolds->front_three_lucky_txt ? $qian3zjh = $newstcode->analysisolds->front_three_lucky_txt : $qian3zjh = '没有中奖 T.T';
                $newstcode->analysisolds->after_three_lucky_txt ? $hou3zjh = $newstcode->analysisolds->after_three_lucky_txt : $hou3zjh = '没有中奖 T.T';

                $url = array_keys($this->codeType, $newstcode->type);
                $url = $url[0];
                $urlName = array_keys($this->urlArr, $url);
                $urlName = $urlName[0];
                $shishicaiUrl = $this->shishicaiUrl[$urlName];

                $html .= '<a href="'.$shishicaiUrl.'">传送门--->'.$urlName.'</a><br/>'
                    .'当前彩种:'.$urlName.' - [旧时时彩]<br/>'
                    .'当前期号:'.$newstcode->qishu .'<br/>'
                    .'开奖号码:'. $newstcode->code .'<br/>'
                    .'前三中奖:'. $qian3zjh .'<br/>'
                    .'后三中奖:'. $hou3zjh .'<br/><br/>';
            }
            */
        }

        foreach($recipientsMailboxs as $obj){
            $mail= Yii::$app->mailer->compose();
            $mail->setTo($obj->email_address);
            $mail->setSubject("小蛮牛提醒");
            //$mail->setTextBody('zheshisha');   //发布纯文字文本
            $mail->setHtmlBody($html);    //发布可以带html标签的文本

            if($mail->send()){
                if($arr['type']==1 ){
                    $emailType = '每一期中奖邮寄通知 <br/> ';
                }else{
                    $emailType = 'N期未中奖邮件通知 <br/> ';
                }
                echo $emailType."邮件通知发送成功.success<br/>";
            }else{
                echo "邮件通知发送失败.failse";
            }
        }

    }

}
?>