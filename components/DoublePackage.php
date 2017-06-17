<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-6-2
 * Time: 下午8:17
 */

namespace app\components;


use app\models\Bjssc;
use app\models\Cqssc;
use app\models\Double;
use app\models\Tjssc;
use app\models\Xjssc;
use app\models\Mailbox;
use Yii;

class DoublePackage
{

    protected $cptype;

    /* 当前的彩票类型 */
    protected $cp_name;

    /* 后台添加的预定报警号码 */
    protected $config;

    /* 当前的彩票类型数据模型 */
    public $model;

    /* 报警期数 >= 当前 */
    public $number;

    /* 邮件报警内容 */
    public $content;

    /* 报警开始时间 */
    protected $start;

    /* 报警结束时间 */
    protected $end;

    /* 报警状态 */
    protected $status;

    /* 别名 */
    protected $alias;

    /* 数据包A 数组 */
    protected $package_a;

    /* 数据包B 数组 */
    protected $package_b;

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

    private function get_config(){
        if($this->cptype == 'cq'){
            //重庆时时彩
            $this->cp_name = '庆';
        }
        if($this->cptype == 'tj'){
            ///天津时时彩
            $this->cp_name = '津';
        }
        if($this->cptype == 'xj'){
            //新疆时时彩
            $this->cp_name = '疆';
        }
        if($this->cptype == 'bj'){
            //北京时时彩
            $this->cp_name = '台';
        }
        $config = Double::find()->all();
        if(!$config){
            echo '系统还未添加 -['.$this->cp_name.'彩票] 双包,请先添加'."\r\n";
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
            $this->alias = $val['alias'];
            $this->number = $val['number'];
            $this->status = $val['status'];
            $this->start = $val['start'];
            $this->end = $val['end'];
            $this->analysis($val['package_a'], 'a');
            $this->analysis($val['package_b'], 'b');
            $this->query_code();
        }

        if($this->content){
            $this->send_mail($this->content);
        }
    }

    /**
     * 解析 上传数据
     */
    private function analysis($data, $type){
        //将 数据包 内的数据 转换成数组
        $dataTxts = str_replace("\r\n", ' ', $data); //将回车转换为空格
        $dataArr = explode(' ',$dataTxts);
        $dataArr = array_filter($dataArr);
        if ($type == 'a') {
            $this->package_a = $dataArr; //重庆数据包 内的数据 转换成数据放在全局变量里
        }else {
            $this->package_b = $dataArr; //重庆数据包 内的数据 转换成数据放在全局变量里
        }
    }

    private function query_code(){
        if(!$this->status){
            //报警关闭
            echo $this->cp_name. " - 间隔 时时彩报警 报警状态关闭 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }
        //检查是否在报警时段
        if( ($this->start && $this->end) && (date('H') < $this->start || date('H') > $this->end) ){
            //当前非报警时段
            echo $this->cp_name. " - 间隔 时时彩报警通知非接受时段 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }

        $codes = $this->model->find()->orderBy('time desc')->limit('350')->all();
        sort($codes);
//        $this->analysisCode($codes);
//        $this->intervalAnalysisCode($codes);

        $q3s = [];
        $z3s = [];
        $h3s = [];
        foreach ($codes as $key=>$val) {
            $q3 = $val->one.$val->two.$val->three; //前三号码
            $z3 = $val->two.$val->three.$val->four; //中三号码
            $h3 = $val->three.$val->four.$val->five;//
            $q3s[] = $q3;
            $z3s[] = $z3;
            $h3s[] = $h3;
        }
        $this->analysisCodes($q3s, 'q3');
        $this->analysisCodes($z3s, 'z3');
        $this->analysisCodes($h3s, 'h3');

        /*
        $codes = [
            '81364',
            '15390',
            '21955',
            '46616',
            '44075',
            '42864',
            '70844',
            '03959',
            '03002',
            '77826',
            '02157',
            '86477',
            '40469',
            '85161',
            '18402',
            '23391',
            '38276',
            '85053',
            '79293',
            '59328',
            '66130',
            '42012',
            '27037',
            '95928',
            '76726',
            '77352',
            '79500',
            '31900',
            '48116',
            '85413',
            '85413',
        ];

        $q3s = [];
        $z3s = [];
        $h3s = [];
        foreach ($codes as $key=>$val){
            $q3s[] = $val[0].$val[1].$val[2];
            $z3s[] = $val[1].$val[2].$val[3];
            $h3s[] = $val[2].$val[3].$val[4];
        }

        $this->analysisCodes($q3s, 'q3');
        exit;
        */

    }

    /**
     * @param $code
     * @param $position
     */
    private function analysisCodes($code, $position){
        //var_dump(array_intersect($this->package_a, $this->package_b));exit;
        $position == 'q3' ? $p_name = '前' : false;
        $position == 'z3' ? $p_name = '中' : false;
        $position == 'h3' ? $p_name = '后' : false;
        $number = 0;
        $status = false;

        $log_html = '<br/>';

        foreach ($code as $key=>$val){
            $in_a = in_array($val, $this->package_a);
            $in_b = in_array($val, $this->package_b);
            /*
            echo '开奖号:'. $val;
            echo $in_a ? " 在A包里" : false;
            echo $in_b ? " 在B包里" : false;
            */
            $log_html .= '开奖号:'. $val;
            $in_a ? $log_html .= ' 在A包里' : false;
            $in_b ? $log_html .= ' 在B包里' : false;

            if ($in_a && $in_b){
                if ($key == 0){
                    $number = 0 ;
                    $number = $number + 1;
                    $status = true;
                    $log_html .= ' AB同时出现 300期的第一期, 只+1 =1<br/>';
                    continue;
                }

                $pre_code = $code[$key-1];
                $pre_in_a = in_array($pre_code, $this->package_a);
                if ($pre_in_a){
                    $number = 0 ;
                    $number = $number + 1;
                    $status = true;
                    $log_html .= ' AB同时出现 上一期包含A 清零 再 +1 = 1<br/>';
                    continue;
                }else {
                    $number = $number + 1;
                    $status = true;
                    $log_html .= ' AB同时出现 上一期不包含A +1 = '.$number.'<br/>';
                    continue;
                }
            }

            if ($in_a){
                $number = $number + 1;
                $status = true;
                /*
                echo " A包 +1 =".$number;
                echo "<br/>";
                */
                $log_html .= ' A包 +1 ='. $number . '<br/>';
                continue;
            }

            if ($in_b){
                if ($key == 0){
                    $log_html .= ' B包 300期的第一期 这期不管<br/>';
                    $status = false;
                    continue;
                }

                $pre_code = $code[$key-1];
                $pre_in_a = in_array($pre_code, $this->package_a);
                if ($pre_in_a){
                    $number = 0 ;
                    $status = true;
                    $log_html .= ' B包 上一期包含A 清零 = 0<br/>';
                    continue;
                }else {
                    $log_html .= ' B包 上一期没包含A 这期不管<br/>';
                    $status = false;
                    continue;
                }
            }

            $status = false;
            /*
            echo " 不在AB里";
            echo "<br/>";
            */
            $log_html .= ' 不在AB里' . '<br/>';
        }

        //报警期数达到了就报警
        if ($number >= $this->number && $status ) {
            $this->content .= $this->cp_name . ' - ' . ' 双包玩法 '. ' 别名: ' . $this->alias. ' 位置:' . $p_name . ' 期数: '. $number . ' 报警<br/>';
            //$this->content .= $log_html;
        }
    }

    /**
     * 分析开奖号
     * @param $codes     开奖号
     */
    private function analysisCode($codes){
        $benchmark = false; //基准
        $number = 0;
        foreach ($codes as $key=>$val){
            //$q3 = $val->one.$val->two.$val->three; //前三号码
            //$z3 = $val->two.$val->three.$val->four; //中三号码
            //$h3 = $val->three.$val->four.$val->five;//

            //echo $val->one.$val->two.$val->three.$val->four.$val->five  ."\r\n";

            if (!$benchmark) {
                $benchmark = $this->getBenchmark($val);
                //echo '基准是 :'. $benchmark. "\r\n";
                continue; // 一直找基准 跳出本次循环 进入下次循环
            }

            if ($benchmark){
                $bool = $this->inPackageB($val, $benchmark);
                if (!$bool) {
                    $number = $number + 1;
                    //echo '本次+1'. "\r\n";
                }else {
                    $number = 0; //清零
                    $benchmark = false; //重新找基准
                    //echo '本次 = 0'. "\r\n";
                }
            }

        }

        //报警期数达到了就报警
        if ($number >= $this->number ) {
            $this->content .= $this->cp_name . ' - ' . ' 双包玩法 '. ' 别名: ' . $this->alias . ' 期数: '. $number . ' 未开<br/>';
        }
    }

    /**
     * 间隔
     * @param $codes
     */
    private function intervalAnalysisCode($codes){
        $status = false;
        $number = 0;
        foreach ($codes as $key=>$val){
            $benchmark = $this->getBenchmark($val);
            if ($benchmark){
                $number = $number + 1;
                $status = true;
                continue;
            }

            $bool = $this->inPackageB($val, $benchmark);
            if ($bool){
                $number = 0;
                $status = false;
                continue;
            }

            $status = false;
        }

        //报警期数达到了就报警
        if ($number >= $this->number && $status ) {
            $this->content .= $this->cp_name . ' - ' . ' 双包玩法 - 【间隔】 '. ' 别名: ' . $this->alias . ' 期数: '. $number . ' 未开<br/>';
        }
    }

    /**
     * 获取基准
     * @param $code
     * @return string
     */
    private function getBenchmark($code){
        $q3 = $code->one.$code->two.$code->three; //前三号码
        $z3 = $code->two.$code->three.$code->four; //中三号码
        $h3 = $code->three.$code->four.$code->five;//
        $q3_in = in_array($q3, $this->package_a);
        $z3_in = in_array($z3, $this->package_a);
        $h3_in = in_array($h3, $this->package_a);

        if ($q3_in && $z3_in && $h3_in) {
            return 'h3';
        }

        if ($q3_in && $z3_in) {
            return 'z3';
        }

        if ($z3_in && $h3_in) {
            return 'h3';
        }

        if ($q3_in && $h3_in) {
            return 'h3';
        }

        if ($q3_in) {
            return 'q3';
        }

        if ($z3_in) {
            return 'z3';
        }

        if ($h3_in) {
            return 'h3';
        }

        return false;
    }

    /**
     * 是否在B包
     * @param $code
     * @param $benchmark
     * @return bool
     */
    private function inPackageB($code, $benchmark){
        $q3 = $code->one.$code->two.$code->three; //前三号码
        $z3 = $code->two.$code->three.$code->four; //中三号码
        $h3 = $code->three.$code->four.$code->five;//

        $in = false;
        if ($benchmark == 'q3') {
            $in = in_array($q3, $this->package_b);
        }

        if ($benchmark == 'z3'){
            $in = in_array($z3, $this->package_b);
        }

        if ($benchmark == 'h3') {
            $in = in_array($h3, $this->package_b);
        }
        return $in;
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
                echo $this->cp_name."双包玩法报警 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo $this->cp_name."双包玩法报警 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }

}