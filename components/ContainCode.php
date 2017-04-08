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

    /* 报警期数 */
    protected $number;

    /* 报警开始时间 */
    protected $start;

    /* 报警结束时间 */
    protected $end;

    /* 邮件报警内容 */
    protected $email_content;

    /* 当前期 前三 与包含号码的 重复次数 */
    protected $q3_repeat_number;

    /* 当前期 中三 与包含号码的 重复次数 */
    protected $z3_repeat_number;

    /* 当前期 后三 与包含号码的 重复次数 */
    protected $h3_repeat_number;

    /* 当前期 前三 是否是组六形态 */
    protected $q3_is_six;

    /* 当前期 中三 是否是组六形态 */
    protected $z3_is_six;

    /* 当前期 后三 是否是组六形态 */
    protected $h3_is_six;

    /* 参考对象， 只要有一组开奖号码包含了 大白话 需要递归吗 默认需要*/
    protected $referent = true;

    /* 期数 */
    protected $qishu;

    /* 开奖号码 */
    protected $kjcode;

    /* 是否需要报警 */
    protected $alarm_status = true;

    /* 基准 */
    /**
     * 基准规则
     * 如果 只有 前三 or 中三 or 后三 单一组的开奖号码 包含了号码的2位数 >=2位数, 那么下一期就只检查 前3 or 中3 or 后3开奖号 对应这期是哪个位置包含了2位
     * 如果是 前三 and 中三 都同时包含了2位数字，那么下一期就检查 中三
     * 如果是 中三 and 后三 都同时包含了2位数字，那么下一期就检查 后三
     * 如果是 前三 and 中三 and 后三 都同时包含了2位数字，那么下一期就以后三位基准
     * 默认没有基准 == false
     * == q3 下一期以前三位基准 检查
     * == z3 下一期以中三为基准 检查
     * == h3 下一期以后三位基准 检查
     */
    protected $benchmark = false;

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
        //获取报警配置项内的警报期数
        foreach ($this->config as $key=>$val){
            $this->contents      = $val['contents'];      //包含号码
            $this->number        = $val['number'];        //报警期数
            $this->start         = $val['start'];         //报警开始时间
            $this->end           = $val['end'];           //报警结束时间

            //检查是否在报警时段
            if( ($this->start && $this->end) && (date('H') < $this->start || date('H') > $this->end) ){
                //当前非报警时段
                echo $this->cp_name.' 包含组: '. $this->contents . " - 报警通知非接受时段 时间:".date('Y-m-d H:i:s')."\r\n";
                return;
            }

            //准备递归查询 近期的数据 并检查第一个参考对象处于什么位置
            $danger_num = $this->recursionCodes();

            $this->setEmailContent($danger_num);
        }

        if(!$this->email_content){
            //不到达报警提示
            return;
        }
        $this->send_mail($this->email_content);
    }

    /**
     * 设置邮件内容
     * @param $danger_num
     */
    private function setEmailContent($danger_num){
        if($danger_num >= $this->number && $this->alarm_status == true){
            $this->email_content .= $this->cp_alias_name. ' - 期:' . $this->qishu . ' - 数:'. $this->kjcode. ' - 含:'. $this->contents . ' - 出现:'.$danger_num . "<br/>";
        }
    }

    /**
     * 递归开奖数据
     * @param int $limit 默认查询当前 最新的100期内容
     */
    /**
     * @param int $limit
     * @return mixed
     */
    private function recursionCodes($limit = 100){
        $count = $this->model->find()->count();
        $codes = $this->model->find()->orderBy('time DESC')->limit($limit)->all();

        //倒序排列 按开奖号 递增方式排列
        $codes = array_reverse($codes);
        $danger_num = $this->get_analysis_danger_number($codes);

        //出现过基准 不需要递归
        if($this->referent == false){
            //echo '出现过基准';
            return $danger_num;
        }

        if(count($codes) == $count){
            //echo "还是没有找到清零的位置,数据库中所有的条数 等于 当前查询的数据 证明已经查询了所有数据<br/>";exit;
            return $danger_num;
        }

        //当前还没出现个基准 继续往前查询数据 翻1倍的数据查询再解析
        return $this->recursionCodes($limit + $limit);
    }

    /**
     * 按照算法 分析开奖号后的 危险警报次数
     * @param $codes 开奖号码;
     * @return array
     */
    private function get_analysis_danger_number($codes){
        $danger_num = 0; //初始化警报次数
        foreach ($codes as $key=>$val){
            $this->qishu = $val->qishu;
            $this->kjcode = $val->one.$val->two.$val->three.$val->four.$val->five;
            $q3 = $val->one.$val->two.$val->three; //前三号码
            $z3 = $val->two.$val->three.$val->four; //中三号码
            $h3 = $val->three.$val->four.$val->five;//后三号码

            //前三与 包含号码 重复位数
            $this->q3_repeat_number = $this->getRepeatDigitNumber($q3);
            //中三与 包含号码 重复位数
            $this->z3_repeat_number = $this->getRepeatDigitNumber($z3);
            //后三与 包含号码 重复位数
            $this->h3_repeat_number = $this->getRepeatDigitNumber($h3);

            //前三是否是组六形态
            $this->q3_is_six = $this->is_six($q3);
            //中三是否是组六形态
            $this->z3_is_six = $this->is_six($z3);
            //后三是否是组六形态
            $this->h3_is_six = $this->is_six($h3);

            //警报是否需要升级
            $danger_num = $this->alarmUpgrade($danger_num);

            //获取下期的基准 参考
            $this->getBenchmark();
        }
        return $danger_num;
    }

    /**
     * 开奖号获取与包含号码 重复次数
     * @param $code 开奖号
     * @return int 开奖号与包含号重复 位数
     */
    private function getRepeatDigitNumber($code){
        //开奖号 字符串转数组
        $code = str_split($code);
        //去重
        $code = array_unique($code);
        //包含号 字符串转数组
        $contents = str_split($this->contents);
        //去重
        $contents = array_unique($contents);

        //求两个数组的交集
        $intersection = array_intersect($code, $contents);
        return count($intersection);
    }

    /**
     * 是否是组6形态
     * @param $number 开奖号
     * @return bool
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
     * 获取下期的基准 参考
     */
    private function getBenchmark(){
        //前三 and 中三 and 后三 都不包含2位 下期则没有基准
        if($this->q3_repeat_number <2 && $this->z3_repeat_number <2 && $this->h3_repeat_number <2){
            return $this->benchmark = false;
        }

        //前三 and 中三 and 后三 都包含2位 下期则以后三为基准
        if($this->q3_repeat_number >=2 && $this->z3_repeat_number >=2 && $this->h3_repeat_number >=2){
            return $this->benchmark = 'h3';
        }

        //前三 and 中三 都包含2位 下期则以中三为基准
        if($this->q3_repeat_number >=2 && $this->z3_repeat_number >=2){
            return $this->benchmark = 'z3';
        }

        //中三 and 后三 都包含2位 下期则以后三为基准
        if($this->z3_repeat_number >=2 && $this->h3_repeat_number >=2){
            return $this->benchmark = 'h3';
        }

        //前三 and 后三 都包含2位 下期则以后三为基准 以右位基准
        if($this->q3_repeat_number >=2 && $this->h3_repeat_number >=2){
            return $this->benchmark = 'h3';
        }

        //前三 包含2位 下期则以前三为基准
        if($this->q3_repeat_number >= 2){
            return $this->benchmark = 'q3';
        }

        //中三 包含2位 下期则以中三为基准
        if($this->z3_repeat_number >= 2){
            return $this->benchmark = 'z3';
        }

        //后三 包含2位 下期则以后三为基准
        if($this->h3_repeat_number >= 2){
            return $this->benchmark = 'h3';
        }
    }

    /**
     * 警报是否提升
     * 规则就是 有基准的情况下，就当前期要是开的是组6形态，并且，只包含1位就清零，
     * 没有基准的情况下 如果是 包含2位就该+1 下一期就有基准了， 如果是包含1位组6 组3 就不+，只要没有基准 不是包含2位就不+
     * @param $danger_num 当前警报数
     * @return mixed
     */
    private function alarmUpgrade($danger_num){
        //前三 or 中三 or 后三 有一个位置包含了2位, 警报提高一级
        if($this->q3_repeat_number >= 2 || $this->z3_repeat_number >= 2 || $this->h3_repeat_number >= 2){
            // 需要报警
            $this->alarm_status = true;
            $danger_num = $danger_num + 1;
            return $danger_num;
        }

        //有基准的情况
        if($this->benchmark){
            //基准是 前三 and 前三包含1位 and 前三是组6 清零
            if($this->benchmark == 'q3' && $this->q3_repeat_number == 1 && $this->q3_is_six == true){
                // 需要报警
                $this->alarm_status = true;
                $danger_num = 0;
                //不递归了 已经找到清零的地方了
                $this->referent = false;
                return $danger_num;
            }

            //基准是 中三 and 中三包含1位 and 中三是组6 清零
            if($this->benchmark == 'z3' && $this->z3_repeat_number == 1 && $this->z3_is_six == true){
                // 需要报警
                $this->alarm_status = true;
                $danger_num = 0;
                //不递归了 已经找到清零的地方了
                $this->referent = false;
                return $danger_num;
            }

            //基准是 后3 and 后三包含1位 and 后三是组6 清零
            if($this->benchmark == 'h3' && $this->h3_repeat_number == 1 && $this->h3_is_six == true){
                // 需要报警
                $this->alarm_status = true;
                $danger_num = 0;
                //不递归了 已经找到清零的地方了
                $this->referent = false;
                return $danger_num;
            }
        }

        //本次没有提升报警级别 所以不需要报警
        //至于本次计算 不+级别的 才不需要报警
        $this->alarm_status = false;
        return $danger_num;
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
                echo $this->cp_name."包含号码报警 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo $this->cp_name."包含号码报警 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }

}