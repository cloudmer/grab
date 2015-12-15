<?php
namespace app\components;
use app\models\Analysis;
use app\models\Code;
use app\models\Comparison;
use app\models\Configure;
use app\models\Log;
use app\models\Mailbox;
use Yii;
use yii\helpers\Url;

class Grab{

    private $grab;
    private $urlArr = array(
        '江西页面'=>'http://cp.360.cn/dlcjx/?r_a=7zIRFz',
        '广东页面'=>'http://cp.360.cn/gd11/?r_a=yiiEJb',
        '山东页面'=>'http://cp.360.cn/yun11/?r_a=JfMbIz'
    );

    private $codeType = array(
        'http://cp.360.cn/dlcjx/?r_a=7zIRFz'=>'1',
        'http://cp.360.cn/gd11/?r_a=yiiEJb'=>'2',
        'http://cp.360.cn/yun11/?r_a=JfMbIz'=>'3',
    );

    private $shishicaiUrl = array(
        '江西页面'=>'http://www.shishicai.cn/jx11x5/touzhu/',
        '广东页面'=>'http://www.shishicai.cn/gd11x5/touzhu/',
        '山东页面'=>'http://www.shishicai.cn/sd11x5/touzhu/'
    );

    public function __construct($url){
        $h = date('H',time());
        $i = date('i',time());
        if($h<9 || ($h==23 && $i>5) ){
            //早上没到9点就不工作
            //晚上 23点15以后就不工作了
            echo '休息了,亲';
            exit;
        }

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

    function html_str($url){
        /*
        $qihao = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=hd clearfix]',0)->find('em[class=red]',0)->innertext;
        $code1 = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=hd clearfix]',0)->find('ul[id=open_code_list]',0)->find('li[class=ico-ball3]',0)->innertext;
        $code2 = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=hd clearfix]',0)->find('ul[id=open_code_list]',0)->find('li[class=ico-ball3]',1)->innertext;
        $code3 = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=hd clearfix]',0)->find('ul[id=open_code_list]',0)->find('li[class=ico-ball3]',2)->innertext;
        $code4 = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=hd clearfix]',0)->find('ul[id=open_code_list]',0)->find('li[class=ico-ball3]',3)->innertext;
        $code5 = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=hd clearfix]',0)->find('ul[id=open_code_list]',0)->find('li[class=ico-ball3]',4)->innertext;
        $msg = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=hd clearfix]',0)->find('p[class=kj-date]',0)->find('em[class=kj-date-txt]',0)->innertext;

        echo $qihao.'<br/>';
        echo $code1.'<br/>';
        echo $code2.'<br/>';
        echo $code3.'<br/>';
        echo $code4.'<br/>';
        echo $code5.'<br/>';
        echo $msg.'<br/>';
        exit;
        */

        $qihao2 = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',0)->innertext;
        $code = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',1)->innertext;
        if($code != '--'){
            // == '--' 正在开奖中
            $sizeProportion = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->find('span',0)->innertext;
            $jioubili = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',2)->find('span',1)->innertext;
            $code_1 = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',1)->find('em',0)->innertext;
            $code_2 = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',1)->find('em',1)->innertext;
            $code_3 = $this->grab->find('div[class=mod-aside mod-aside-xssckj]',0)->find('div[class=bd]',0)->find('div[class=kpkjcode]',0)->find('table',0)->find('tr',1)->find('td',1)->find('em',2)->innertext;
            $code = $code_1.$code_2.$code_3;
            $codeArr = explode(" ",$code);
            list($one,$two,$three,$four,$five) = $codeArr;

            $result = Code::findOne(['qishu'=>$qihao2,'type'=>$this->codeType[$url]]);
            if($result){
                $urlName = array_keys($this->urlArr,$url);
                echo $urlName[0].' - [新时时彩] 最新数据已经采集过了<br/>';
                return;
            }

            $model = new Code();
            $model->qishu = $qihao2;
            $model->one = $one;
            $model->two = $two;
            $model->three = $three;
            $model->four = $four;
            $model->five = $five;
            $model->type = $this->codeType[$url];
            $model->size = $sizeProportion;
            $model->jiou = $jioubili;
            $model->time = time();

            if($model->validate() && $model->save()){
                $urlName = array_keys($this->urlArr,$url);
                $logModel = new Log();
                $logModel->type = 1;
                $logModel->content = $urlName[0].'.开奖信息抓取成功';
                $logModel->time = time();
                $logModel->save();
                echo $urlName[0].'.开奖信息抓取成功-[新时时彩]<br/>';
                $this->find($qihao2,$urlName[0],$codeArr,$model->id);
                return;
            }


        }else{
            $urlName = array_keys($this->urlArr,$url);
            echo $urlName[0].' -[新时时彩] 等待开奖...<br/>';
            return;
        }
    }

    function find($qihao,$urlName,$codeArr,$code_id){
        sort($codeArr); //数组从小到大排序 用户需求
        //数据分析
        $config = Configure::find()->all(); //系统报警配置
        $config = $config[0];

        //记录中奖与未中奖号码
        $model = Comparison::find()->all();
        $model = $model[0];
        $data = $model->txt;

        $dataTxts = str_replace("\r\n", ' ', $data); //将回车转换为空格
        $dataArr = explode(' ',$dataTxts);
        $dataArr = array_filter($dataArr);
        $dataArr = array_chunk($dataArr,5);

        //当前开奖号码 对比 数据库
        $lucky = array(); //中奖号
        $regret = $dataArr; //未中奖号
        foreach($dataArr as $key=>$val){
            sort($val); // 从小到大排序
            if($codeArr == $val){
                //中奖号组
                array_push($lucky,$val);
                unset($regret[$key]);
            }
        }

        //将中奖与未中奖的数组 转换为用户上传数据本的 格式 存入数据库
        $luckyStr = null;
        $regretStr = null;
        if(count($lucky) != 0){
            //有中奖号码 记录数据库
            foreach($lucky as $luc){
                foreach($luc as $l){
                    $luckyStr .= $l.' ';
                }
                $luckyStr .= "\r\n";
            }
        }
        if(count($regret) != 0){
            //有未中奖号码 记录数据库
            foreach($regret as $reg){
                foreach($reg as $r){
                    $regretStr .= $r.' ';
                }
                $regretStr .= "\r\n";
            }
        }

        //分析数据本与当前这期开奖号码 记录数据本里面 中奖号码与未中奖的号码 到数据库中
        $analysis = new Analysis();
        $analysis->codi_id = $code_id;
        $analysis->lucky_txt = $luckyStr;
        $analysis->regret_txt = $regretStr;
        $analysis->data_txt = $data;
        $analysis->time = time();
        $analysis->save();

        if($config->state == 1){
            //系统开启邮件 通知
//            if(date('H',time()) > intval($config->start_time) && date('H',time()) < intval($config->end_time) ){
            if(true ){
                //报警时间段内
                if($config->forever == 1){
                    //每一期 邮件通知打开
                    $cfg = array(
                        'type'=>1,
                        'qihao'=>$qihao,
                        'codeArr'=>$codeArr,
                        'urlName'=>$urlName,
                        'luckyStr'=>$luckyStr,
                        'regretStr'=>$regretStr
                    );
                    $this->send($cfg);
                }

                // 用户设置 几期都未中奖 报警通知
                $NewestCodes = Code::find()->orderBy('time DESC')->limit($config->regret_number)->all();
                if(count($NewestCodes) >= $config->regret_number){
                    //所有的最新的数据 必须 大于等于 用户设置的报警期数
                    $NewestCodesArr = array();
                    foreach($NewestCodes as $obj){
                        $objArr = array(
                            sprintf("%02d",$obj->one), //不足2位数字 左侧自动补全0
                            sprintf("%02d",$obj->two),
                            sprintf("%02d",$obj->three),
                            sprintf("%02d",$obj->four),
                            sprintf("%02d",$obj->five),
                        );
                        sort($objArr);//将数组排序按从大到小排序
                        array_push(
                            $NewestCodesArr,
                            $objArr
                        );
                    }

                    // 查询用户设置的报警 期数内 是否都未中奖
                    $lucky = false;
                    foreach($NewestCodesArr as $newest){
                        foreach($dataArr as $dataTxt){
                            if($dataTxt == $newest){
                                $lucky = true; //用户设置的当前 期数内有中奖
                                break;
                            }
                        }
                    }

                    if($lucky == false){
                        //发送报警通知 当前 $config->regret_number 内 都未中奖
                        $cfg = array(
                            'type'=>2,
                            'codeArr'=>$codeArr,
                            'regretCodeIds'=>$NewestCodes,
                            'regret_number'=>$config->regret_number
                        );
                        $this->send($cfg);
                    }
                }
            }

        }
    }

    public function send($arr){
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
            $arr['luckyStr'] ? $luckyStr = '<br/>'.str_replace("\r\n", '<br/>', $arr['luckyStr']) : $luckyStr = '没有中奖 T.T';
            $regretStr = str_replace("\r\n", '<br/>', $arr['regretStr']);
            $html = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
                .'<a href="'.$this->shishicaiUrl[$arr['urlName']].'">传送门--->'.$arr['urlName'].'</a><br/>'
                .'当前彩种:'.$arr['urlName'].' - [新时时彩]<br/>'
                .'当前期号:'.$arr['qihao'] .'<br/>'
                .'开奖号码:'.implode(",",$arr['codeArr']).'<br/>'
                .'中奖号码为:'.$luckyStr .'<br/>'
                .'未中奖号码为:<br/>'.$regretStr;
        }
        if($arr['type'] == 2){
            $html = '报警提醒:<br/>当前'.$arr['regret_number']
                    .'期内 没有一组中奖号码！！！！！！<br/>'
                    .'<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
                    .'以下是彩种信息:<br/><br/>';
            foreach($arr['regretCodeIds'] as $regret){
                $url = array_keys($this->codeType, $regret->type);
                $url = $url[0];
                $urlName = array_keys($this->urlArr, $url);
                $urlName = $urlName[0];
                $shishicaiUrl = $this->shishicaiUrl[$urlName];

                $html .= '<a href="'.$shishicaiUrl.'">传送门--->'.$urlName.'</a><br/>'
                    .'当前彩种:'.$urlName.' - [新时时彩]<br/>'
                    .'当前期号:'.$regret->qishu .'<br/>'
                    .'开奖号码:'. $regret->one.','.$regret->two.','.$regret->three.','.$regret->four.','.$regret->five .'<br/>'
                    .'未中奖!!!!!!!<br/><br/>';
            }
        }

        foreach($recipientsMailboxs as $obj){
            $mail= Yii::$app->mailer->compose();
            $mail->setTo($obj->email_address);
            $mail->setSubject("小蛮牛提醒");
            //$mail->setTextBody('zheshisha');   //发布纯文字文本
            $mail->setHtmlBody($html);    //发布可以带html标签的文本

            if($mail->send()){
                if($arr['type']==1 ){
                    $emailType = '每一期中奖邮寄通知  ';
                }else{
                    $emailType = 'N期未中奖邮件通知  ';
                }
                echo $emailType."邮件通知发送成功.success<br/>";
            }else{
                echo "邮件通知发送失败.failse";
            }

        }

    }

}
?>