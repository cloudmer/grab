<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-9-12
 * Time: 下午10:53
 */

namespace app\components;


use app\models\Cqssc;
use app\models\Tjssc;
use app\models\Xjssc;
use app\models\Mailbox;
use Yii;

class Reserve
{

    /* 彩票类型 */
    public $cptype;

    /* 后台添加的预定报警号码 */
    public $config;

    /* 当前的彩票类型 */
    public $cp_name;

    /* 当前的彩票类型数据模型 */
    public $model;

    /* 当前数据模型的最新一起的开奖数据 */
    public $newest;

    /* 报警期数 >= 当前 */
    public $danger_number;

    /* 预定的报警号码 */
    public $reserve_number;

    /* 邮件报警内容 */
    public $content;

    /**
     * 连续 9期及以上 连续开两组预定号码的组合 只看连续的最新的一起预定号码是不是组6 如果不是 则 邮件报警
     * Danger constructor.
     */
    public function __construct($cptype)
    {

        $this->cptype = $cptype;
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
        if($this->cptype == 'cq'){
            //重庆时时彩
            $config = \app\models\Reserve::find()->where(['cp_type'=>1])->all();
//            $this->cp_name = '重庆';
            $this->cp_name = '庆';
        }
        if($this->cptype == 'tj'){
            ///天津时时彩
            $config = \app\models\Reserve::find()->where(['cp_type'=>2])->all();
//            $this->cp_name = '天津';
            $this->cp_name = '津';
        }
        if($this->cptype == 'xj'){
            //新疆时时彩
            $config = \app\models\Reserve::find()->where(['cp_type'=>3])->all();
//            $this->cp_name = '新疆';
            $this->cp_name = '疆';
        }
        if(!$config){
            echo '系统还未添加 -['.$this->cp_name.'彩票] 预定报警号码,请先添加'."\r\n";
        }
        $this->config = $config;
    }

    /**
     * 获取开奖号码
     */
    private function get_codes(){
        $this->analysis();
    }

    /**
     * 彩票类型数据分析
     */
    private function analysis(){
        //当前数据模型的最新一起的开奖数据
        $this->newest = $this->model->find()->orderBy('time DESC')->limit(1)->one();

        // 读取配置项
        foreach ($this->config as $m){
            $type = $m->type; //报警单位, 1=>不论前3中3后3 都报警 2=>前3 3=>中3 4=>后3
//            $code_type = $m->code_type; //奖号类型, 1=>组6 2=>组3
            $this->danger_number = $m->qishu; //几期不开则报警
            $this->reserve_number = $m->number; //获取预定报警号码

            $status = $m->status; //报警状态 1=>开启 0=>关闭
            //当前关闭报警,跳过
            if($status == 0){
                echo $this->cp_name.'时时彩,当前关闭预定号码报警阀门'."\r\n";
                return;
            }

            //前3 中3 后3 都要报警
            if($type == 1){
                $this->recursionCodes('q3');
                $this->recursionCodes('z3');
                $this->recursionCodes('h3');
            }

            //前3 报警
            if($type == 2){
                $this->recursionCodes('q3');
            }
            //中3 报警
            if($type == 3){
                $this->recursionCodes('z3');
            }
            //后3 报警
            if($type == 4){
                $this->recursionCodes('h3');
            }
        }

        //邮件内容不为空 邮件发送
        if($this->content){
            $this->send_mail($this->content);
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
            $mail->setSubject("小蛮牛提醒");
            //$mail->setTextBody('zheshisha');   //发布纯文字文本
            $mail->setHtmlBody($content);    //发布可以带html标签的文本

            if($mail->send()){
                echo $this->cptype."预定号码报警 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo $this->cptype."预定号码报警 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }

    /**
     * 递归开奖数据
     * @param int $limit 默认查询当前 最新的100期内容
     */
    private function recursionCodes($position,$limit = 100){
        $count = $this->model->find()->count();
        $codes = $this->model->find()->orderBy('time DESC')->limit($limit)->all();
        //倒序排列 按开奖号 递增方式排列
        $codes = array_reverse($codes);

        $codeArr = [];
        foreach ($codes as $key=>$m){
            if($position == 'q3'){
                //查询前3数据
                $codeArr[] = $m->one.$m->two.$m->three;
            }
            if($position == 'z3'){
                //查询中3数据
                $codeArr[] = $m->two.$m->three.$m->four;
            }
            if($position == 'h3'){
                //查询后3数据
                $codeArr[] = $m->three.$m->four.$m->five;
            }
        }

        list($is_show,$danger_num) = $this->get_analysis_danger_number($codeArr);

        if($is_show){
            //此组号码中 危险警报解除过 已经找到最新一期的 清0状态
//            echo "此组号码中 危险警报解除过 已经找到最新一期的 清0状态<br/>";
            $this->mail_contents($position,$danger_num); //报警内容
            return;
        }

        //数据库中所有的条数 等于 当前查询的数据 证明已经查询了所有数据 还是没有找到清0的位置
        if($count == count($codes)){
//            echo "数据库中所有的条数 等于 当前查询的数据 证明已经查询了所有数据<br/>";
            $this->mail_contents($position,$danger_num); //报警内容
            return;
        }

        //当前还没查询出清0位置 继续往前查询数据 翻1倍的数据查询再解析
//        echo '当前还没查询出清0位置 继续往前查询数据'.($limit+$limit).'<br/>';
        $this->recursionCodes($position,($limit + $limit));
    }

    /**
     * 邮件报警内容
     * @param $position    单位;
     * @param $danger_num  危险次数;
     */
    private function mail_contents($position,$danger_num){
        if($position == 'q3'){
//            $position_name = '前3';
            $position_name = '前';
        }
        if($position == 'z3'){
//            $position_name = '中3';
            $position_name = '中';
        }
        if($position == 'h3'){
//            $position_name = '后3';
            $position_name = '后';
        }

        //是否报警 当本期开的号码 的是包含 预定号码才报警
        if(!$this->is_warning($position)){
            return;
        }

        //当前几期未开 >= 报警阀值 就报警
        if($danger_num >= $this->danger_number){
            echo '警告: '.$this->cp_name.' - '.$position_name.' - 预定号码:'.$this->reserve_number.' 已经有'.$danger_num.' 期未开奖了'."\r\n";
//            $this->content .= '警告: '.$this->cp_name.' - '.$position_name.' - 预定号码:'.$this->reserve_number.' 已经有'.$danger_num.' 期未开奖了<br/>';
            $this->content .= $this->cp_name.' - '.$position_name.' - 组合:'.$this->reserve_number.' 已经有'.$danger_num.'  N<br/>';
        }
        echo $this->cp_name.' - '.$position_name.' - 预定号码:'.$this->reserve_number.' 已经有'.$danger_num.' 期未开奖了'."\r\n";
    }

    /**
     * 是否报警
     * @param $position 当本期来的是包含 预定号码才报警
     * @param $position 单位;
     * @return bool
     */
    private function is_warning($position){
        if($position == 'q3'){
            //检查前3是否包含号码
            $code = $this->newest->one.$this->newest->two.$this->newest->three;
        }
        if($position == 'z3'){
            //检查中3是否包含号码
            $code = $this->newest->two.$this->newest->three.$this->newest->four;
        }
        if($position == 'h3'){
            //检查后3是否包含号码
            $code = $this->newest->three.$this->newest->four.$this->newest->five;
        }
        return $this->in_reserve_number($code);
    }


    /**
     * 按照算法 分析开奖号后的 危险警报次数
     * @param $codes 开奖号码;
     * @return array
     */
    private function get_analysis_danger_number($codes){
        $is_show = false; //此状态为在当前号组中 已经清0 解除过报警状态
        $referent = false; //当前期是否作为下一期 参考对象
        //举例说明 898 包含89组合 作为下一期的参考对象 898 或者 897 不论是组3还是组6都将作为下一期参考对象也不论当前是否清0不清0
        $danger_num = 0; //未出现的次数 初始化为0
        foreach ($codes as $key=>$val){
            //当前号码中是否包含当前预定组合
            $in_reserve_number = $this->in_reserve_number($val);
            //是否是组6行态
            $is_six = $this->is_six($val);

            //只要当前号码 包含组合号码
            if($in_reserve_number){
                //当前期 为出现的包含组合 并且 为组6形态 并且有参考对象
                if($is_six && $referent){
                    //是组6组合 并且 包含当前预定号码 报警解除
                    $danger_num = 0;
                    $is_show = true; //此状态为在当前号组中 已经清0 解除过报警状态
                }
                //不是组6形态 并且 包含当前预定号码 报警提高一级
                if(!$is_six && $referent){
//                    echo $val.' +1='.$danger_num."<br>";
                    $danger_num +=1;
                }

                //只要包含当前预定号码 就将此期号码作为下期参考对象
                $referent = true;
            }

            //当前号码不包含预定号码 并且 有上期参考对象
            if(!$in_reserve_number && $referent){
                //不是组6组合 并且 不包含当前预定号码 报警提高一级
                $danger_num +=1;
//                echo $val.' +1='.$danger_num."<br>";
                // 本期号码不包含当前组合 也未包含预定号码 解除此期号码为参考对象
                $referent = false;
            }
        }

        return [$is_show,$danger_num];

        /*
        $testArr =['390','489','473','018','542','423','091','761','009','600','501','250','419','663','177','741','837','041','775','877','576','521','283','886','300','606','281','526','413','554','350','641','635','739','621'];
//        $testArr =['347', '165', '728', '601', '628', '783', '606', '285', '035', '341', '383', '114', '636', '552', '415', '474', '780', '477', '073', '839', '277', '083', '475', '990', '399', '025', '344', '772', '546',];
        $referent = false; //当前期是否作为下一期 参考对象
        //举例说明 898 包含89组合 作为下一期的参考对象 898 或者 897 不论是组3还是组6都将作为下一期参考对象也不论当前是否清0不清0
        $danger_num = 0; //未出现的次数 初始化为0
        foreach ($testArr as $key=>$val){
            //当前号码中是否包含当前预定组合
            $in_reserve_number = $this->in_reserve_number($val);
            //是否是组6行态
            $is_six = $this->is_six($val);

            //只要当前号码 包含组合号码
            if($in_reserve_number){
                //当前期 为出现的包含组合 并且 为组6形态 并且有参考对象
                if($is_six && $referent){
                    //是组6组合 并且 包含当前预定号码 报警解除
                    $danger_num = 0;

                }
                //不是组6形态 并且 包含当前预定号码 报警提高一级
                if(!$is_six && $referent){
                    echo $val.' +1='.$danger_num."<br>";
                    $danger_num +=1;
                }

                //只要包含当前预定号码 就将此期号码作为下期参考对象
                $referent = true;
            }

            //当前号码不包含预定号码 并且 有上期参考对象
            if(!$in_reserve_number && $referent){
                //不是组6组合 并且 不包含当前预定号码 报警提高一级
                $danger_num +=1;
                echo $val.' +1='.$danger_num."<br>";
                // 本期号码不包含当前组合 也未包含预定号码 解除此期号码为参考对象
                $referent = false;
            }
        }
        var_dump($danger_num);exit;
        */
    }

    /**
     * 是否包含预定号码
     * @param $num 需要查询的前3 or 中3 or 后3
     * @return bool
     */
    private function in_reserve_number($num){
        $status = false;
        $num_arr = str_split($num); //检测的号码
        $numberArr = str_split($this->reserve_number); // 当前预定号组
        foreach ($numberArr as $key=>$val){
            if(in_array($val,$num_arr)){
                $status = true;
            }
        }
        return $status;
    }

    /**
     * 是否是组6形态
     */
    private function is_six($number){
        $codeArr = str_split($number);
        //是组6
        if(count($codeArr) == count(array_unique($codeArr))){
            return true;
        }
        //是组3
        return false;
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
    }
}