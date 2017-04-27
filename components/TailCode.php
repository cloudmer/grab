<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-4-23
 * Time: 下午4:55
 */

namespace app\components;


use app\models\Bjssc;
use app\models\Cqssc;
use app\models\Tjssc;
use app\models\Xjssc;
use app\models\Mailbox;
use Yii;

class TailCode
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

    /* 报警开始时间 */
    protected $start;

    /* 报警结束时间 */
    protected $end;

    /* 报警状态 */
    protected $status;

    /* 连续报警期数  */
    protected $continuity;

    /* 未连续报警期数  */
    protected $discontinuous;

    /* 邮件内容 */
    protected $email_contents;


    protected $zero;
    protected $one;
    protected $two;
    protected $three;
    protected $four;
    protected $five;
    protected $six;
    protected $seven;
    protected $eight;
    protected $nine;

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

        $this->cptype == 'cq' ? $this->cp_name = '重庆' : false;
        $this->cptype == 'tj' ? $this->cp_name = '天津' : false;
        $this->cptype == 'xj' ? $this->cp_name = '新疆' : false;
        $this->cptype == 'bj' ? $this->cp_name = '台湾' : false;

        $this->cptype == 'cq' ? $this->cp_alias_name = '庆' : false;
        $this->cptype == 'tj' ? $this->cp_alias_name = '津' : false;
        $this->cptype == 'xj' ? $this->cp_alias_name = '疆' : false;
        $this->cptype == 'bj' ? $this->cp_alias_name = '台' : false;

        $config = \app\models\Tail::find()->all();
        if(!$config){
            echo '系统还未添加 -['.$this->cp_name.'彩票] 尾号玩法,请先添加'."\r\n";
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
            $this->start         = $val['start'];         //报警开始时间
            $this->end           = $val['end'];           //报警结束时间
            $this->status        = $val['status'];        //报警状态

            $this->zero           = $val['zero'];
            $this->one            = $val['one'];
            $this->two            = $val['two'];
            $this->three          = $val['three'];
            $this->four           = $val['four'];
            $this->five           = $val['five'];
            $this->six            = $val['six'];
            $this->seven          = $val['seven'];
            $this->eight          = $val['eight'];
            $this->nine           = $val['nine'];

            $this->discontinuous  = $val['discontinuous'];
            $this->continuity     = $val['continuity'];

            //查询报警期数内的
            $this->query_codes();
        }

        if($this->email_contents){
            $this->send_mail($this->email_contents);
        }
    }

    /**
     * 查询开奖号码
     */
    private function query_codes(){
        if(!$this->status){
            //报警关闭
            echo $this->cp_name.' 数据包别名: '.$this->cp_alias_name. " - 时时彩报警 报警状态关闭 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }
        //检查是否在报警时段
        if( ($this->start && $this->end) && (date('H') < $this->start || date('H') > $this->end) ){
            //当前非报警时段
            echo $this->cp_name.' 数据包别名: '.$this->cp_alias_name. " - 时时彩报警通知非接受时段 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }

        $codes = $this->model->find()->orderBy('time DESC')->limit('100')->all();
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

        $contents = null;
        $contents .= $this->analysis_continuity($q3s, 'q3');
        $contents .= $this->analysis_discontinuous($q3s, 'q3');

        $contents .= $this->analysis_continuity($z3s, 'z3');
        $contents .= $this->analysis_discontinuous($z3s, 'z3');

        $contents .= $this->analysis_continuity($h3s, 'h3');
        $contents .= $this->analysis_discontinuous($h3s, 'h3');


        $this->email_contents = $contents;
    }

    /**
     * 数据分析 未连续开奖
     * @param $code     开奖号码
     * @param $position 位置
     * @return string
     */
    private function analysis_discontinuous($code, $position){
        $referent = null;
        $number = 0;
        foreach ($code as $key=>$val){
            //第一次 不作计算 直接累加
            if($referent == null){
                $number = $number + 1;

                $referent = $this->getTailCode($val[2]);
                continue;
            }

            $in_reserve_number = $this->in_reserve_number($val, $referent);

            //echo '上一期开奖号码是:'. $code[$key-1] . ' 本期开奖号码是 ' . $val . ' 上一期的开奖尾号是 '. $code[$key-1][2] . ' 参考对象是 ' . $referent . ' 本期号码 ' . $val . ' 是否包含 '. $referent . ' 结果是 ' . $in_reserve_number .   '<br/>';

            if($in_reserve_number){
                $number = 0;
            }else{
                $number = $number +1;
            }

            $referent = $this->getTailCode($val[2]);
        }

        if($number >= $this->discontinuous){
            $position == 'q3' ? $position = '前' : false;
            $position == 'z3' ? $position = '中' : false;
            $position == 'h3' ? $position = '后' : false;
            return $this->cp_alias_name . ' ' . $position . ' 尾 ' . ' 未连续 ' . $number . '<br/>';
        }
        return false;
    }

    /**
     * 数据分析 连续开奖
     * @param $code     开奖号码
     * @param $position 位置
     * @return string
     */
    private function analysis_continuity($code, $position){
        $referent = null;
        $number = 0;
        foreach ($code as $key=>$val){
            //第一次 不作计算 直接累加
            if($referent == null){
                $number = 0;
                $referent = $this->getTailCode($val[2]);
                continue;
            }

            $in_reserve_number = $this->in_reserve_number($val, $referent);

            //echo '上一期开奖号码是:'. $code[$key-1] . ' 本期开奖号码是 ' . $val . ' 上一期的开奖尾号是 '. $code[$key-1][2] . ' 参考对象是 ' . $referent . ' 本期号码 ' . $val . ' 是否包含 '. $referent . ' 结果是 ' . $in_reserve_number .   '<br/>';

            if($in_reserve_number){
                $number = $number +1;
            }else{
                $number = 0;
            }
            $referent = $this->getTailCode($val[2]);
        }

        if($number >= $this->continuity){
            $position == 'q3' ? $position = '前' : false;
            $position == 'z3' ? $position = '中' : false;
            $position == 'h3' ? $position = '后' : false;
            return $this->cp_alias_name . ' ' . $position . ' 尾 ' . ' 连续 ' . $number . '<br/>';
        }
        return false;
    }

    /**
     * 获取尾号号码
     * @param $number 尾号
     */
    private function getTailCode($number){
        if($number == 0){
            return $this->zero;
        }
        if($number == 1){
            return $this->one;
        }
        if($number == 2){
            return $this->two;
        }
        if($number == 3){
            return $this->three;
        }
        if($number == 4){
            return $this->four;
        }
        if($number == 5){
            return $this->five;
        }
        if($number == 6){
            return $this->six;
        }
        if($number == 7){
            return $this->seven;
        }
        if($number == 8){
            return $this->eight;
        }
        if($number == 9){
            return $this->nine;
        }
    }


    /**
     * 是否包含预定号码
     * @param $code 要查询的前3 or 中3 or 后3
     * @param $reserve_number 包含号码
     * @return bool
     */
    private function in_reserve_number($code, $reserve_number){
        $status = false;
        $num_arr = str_split($code); //检测的号码
        $numberArr = str_split($reserve_number); // 当前预定号组
        foreach ($numberArr as $key=>$val){
            if(in_array($val,$num_arr)){
                $status = true;
            }
        }
        return $status;
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
                echo $this->cp_name."尾号报警 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo $this->cp_name."尾号报警 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }


}