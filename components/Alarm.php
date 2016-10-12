<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-9-14
 * Time: 下午7:49
 */

namespace app\components;


use app\models\Configure;
use app\models\Cqdata;
use app\models\Cqssc;
use app\models\Tjdata;
use app\models\Tjssc;
use app\models\Xjdata;
use app\models\Xjssc;
use app\models\Mailbox;
use Yii;

class Alarm
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

    /* 当前数据包id */
    protected $data_txt_id;

    /* 当前数据包别名 */
    protected $alias;

    /* 当前数据包报警期数 */
    protected $regret_number;

    /* 当前数据包报警开始时间 */
    protected $start;

    /* 当前数据包报警结束时间 */
    protected $end;

    /* 当前数据包是否开启每一期开奖通知 */
    protected $forever;

    /* 当前数据包是否开启每一期开奖通知邮件通知内容 */
    protected $forever_email_contents;

    /* 当前数据包 N期未中奖 邮件通知内容 */
    protected $danger_email_contents;

    /**
     * 警报
     * Alarm constructor.
     */
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
            $this->data_txt_id  = $val['id'];             //数据包id
            $this->alias         = $val['alias'];         //数据包别名
            $this->regret_number = $val['regret_number']; //数据包报警期数
            $this->start         = $val['start'];         //数据包报警开始时间
            $this->end           = $val['end'];           //数据包报警结束时间
            $this->forever       = $val['forever'];       //数据包是否开启每期报警通知
            //查询报警期数内的
            $this->query_codes();
        }

        //每期开奖号 邮件通知
        if($this->forever_email_contents){
            $this->send_mail($this->forever_email_contents,1);
        }

        //当前数据包 N 期未中 邮件报警
        if($this->danger_email_contents){
            $this->send_mail($this->danger_email_contents,2);
        }
    }

    private function query_codes(){
        //检查是否在报警时段
        if( ($this->start && $this->end) && (date('H') < $this->start || date('H') > $this->end) ){
            //当前非报警时段
            echo $this->cp_name.' 数据包别名: '.$this->alias. " - 时时彩报警通知非接受时段 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }

        //是否开启每期开奖通知
        if($this->forever){
            $this->forever();
        }

        //检查当前期数内 是否未中奖 报警
        $this->inspect_alarm();
    }

    /**
     * 检查当前期数内 是否未中奖 报警
     */
    private function inspect_alarm(){
        $connection = \Yii::$app->db;

        if($this->type == 'cq'){
            $db_ssc_table_name = 'cqssc';
            $db_analysis_table_name = 'analysisCqssc';
            $db_foreign_key_id = 'cqssc_id';
        }
        if($this->type == 'tj'){
            $db_ssc_table_name = 'tjssc';
            $db_analysis_table_name = 'analysisTjssc';
            $db_foreign_key_id = 'tjssc_id';
        }
        if($this->type == 'xj'){
            $db_ssc_table_name = 'xjssc';
            $db_analysis_table_name = 'analysisXjssc';
            $db_foreign_key_id = 'xjssc_id';
        }


        //查询 当前数据包 前三中奖最新一次的出现位置
//        $sql_q3_lucky_index_id = 'SELECT cqssc.id FROM cqssc LEFT JOIN analysisCqssc ON(cqssc.id=analysisCqssc.cqssc_id) WHERE analysisCqssc.front_three_lucky_txt != \'\' AND analysisCqssc.type='.$this->data_txt_id.' ORDER BY cqssc.time DESC LIMIT 1;';
        $sql_q3_lucky_index_id = 'SELECT '.$db_ssc_table_name.'.id FROM '.$db_ssc_table_name.' LEFT JOIN '.$db_analysis_table_name.' ON('.$db_ssc_table_name.'.id='.$db_analysis_table_name.'.'.$db_foreign_key_id.') WHERE '.$db_analysis_table_name.'.front_three_lucky_txt != \'\' AND '.$db_analysis_table_name.'.type='.$this->data_txt_id.' ORDER BY '.$db_ssc_table_name.'.time DESC LIMIT 1;';
        $command = $connection->createCommand($sql_q3_lucky_index_id);
        $result_q3_lucky_index_id = $command->queryOne();
        $result_q3_lucky_index_id ? $q3_lucky_index_id = intval($result_q3_lucky_index_id['id']) : $q3_lucky_index_id = 0;

        //查询 当前数据包 中三中奖最新一次的出现位置
//        $sql_z3_lucky_index_id = 'SELECT cqssc.id FROM cqssc LEFT JOIN analysisCqssc ON(cqssc.id=analysisCqssc.cqssc_id) WHERE analysisCqssc.center_three_lucky_txt != \'\' AND analysisCqssc.type='.$this->data_txt_id.' ORDER BY cqssc.time DESC LIMIT 1;';
        $sql_z3_lucky_index_id = 'SELECT '.$db_ssc_table_name.'.id FROM '.$db_ssc_table_name.' LEFT JOIN '.$db_analysis_table_name.' ON('.$db_ssc_table_name.'.id='.$db_analysis_table_name.'.'.$db_foreign_key_id.') WHERE '.$db_analysis_table_name.'.center_three_lucky_txt != \'\' AND '.$db_analysis_table_name.'.type='.$this->data_txt_id.' ORDER BY '.$db_ssc_table_name.'.time DESC LIMIT 1;';
        $command = $connection->createCommand($sql_z3_lucky_index_id);
        $result_z3_lucky_index_id = $command->queryOne();
        $result_z3_lucky_index_id ? $z3_lucky_index_id = intval($result_z3_lucky_index_id['id']) : $z3_lucky_index_id = 0;

        //查询 当前数据包 后三中奖最新一次的出现位置
//        $sql_h3_lucky_index_id = 'SELECT cqssc.id FROM cqssc LEFT JOIN analysisCqssc ON(cqssc.id=analysisCqssc.cqssc_id) WHERE analysisCqssc.after_three_lucky_txt != \'\' AND analysisCqssc.type='.$this->data_txt_id.' ORDER BY cqssc.time DESC LIMIT 1;';
        $sql_h3_lucky_index_id = 'SELECT '.$db_ssc_table_name.'.id FROM '.$db_ssc_table_name.' LEFT JOIN '.$db_analysis_table_name.' ON('.$db_ssc_table_name.'.id='.$db_analysis_table_name.'.'.$db_foreign_key_id.') WHERE '.$db_analysis_table_name.'.after_three_lucky_txt != \'\' AND '.$db_analysis_table_name.'.type='.$this->data_txt_id.' ORDER BY '.$db_ssc_table_name.'.time DESC LIMIT 1;';
        $command = $connection->createCommand($sql_h3_lucky_index_id);
        $result_h3_lucky_index_id = $command->queryOne();
        $result_h3_lucky_index_id ? $h3_lucky_index_id = intval($result_h3_lucky_index_id['id']) : $h3_lucky_index_id = 0;


        //获取 前三 中三 后三 中奖 的最前一条数据 比如
        // 1期 前3中 中3不中 后3不中
        // 2期 前3不中 中3中 后3不中
        // 3期 前3不中 中3中 后3中
        //那么 取最前一条数据 取1期
        $least_id = ($q3_lucky_index_id < $z3_lucky_index_id ? $q3_lucky_index_id : $z3_lucky_index_id) < $h3_lucky_index_id
            ? ($q3_lucky_index_id < $z3_lucky_index_id ? $q3_lucky_index_id : $z3_lucky_index_id)
            : $h3_lucky_index_id;

//        if($this->data_txt_id == 2){
//            echo '<pre>';
//            echo $least_id;
//            var_dump($q3_lucky_index_id);
//            var_dump($z3_lucky_index_id);
//            var_dump($h3_lucky_index_id);exit;
//        }


        //从最新一条数据 开始查询 到最新开奖期号的数据
        //有可能 前三 中三 后三 都没有中奖过 那么查询全部数据 并分析
//        $codes = $this->model->find()->andWhere(['>=','id',$least_id])->all();
        $codes = $this->model->find()->andWhere(['>=','id',$least_id])->orderBy('time ASC')->all();

        $q3_lucky_number = 0;
        $z3_lucky_number = 0;
        $h3_lucky_number = 0;

        if($this->data_txt_id == 2){
//            echo '<pre>';
//            var_dump($codes);
//            exit;
        }

        foreach ($codes as $key=>$m){
            //获取当前彩种 分析数据中的 数据包 N 的分析数据
            $analysis_data = $m->getAnalysis($this->data_txt_id)->one();

            //当前 N 期内 前3号码 中过奖
            if($analysis_data->front_three_lucky_txt){
                $q3_lucky_number = 0;  //中奖了 归0
            }else{
                $q3_lucky_number += 1; //未中奖 增加1次计数
            }
            //当前 N 期内 中3号码 中过奖
            if($analysis_data->center_three_lucky_txt){
                $z3_lucky_number = 0;  //中奖了 归0
            }else{
                $z3_lucky_number += 1; //未中奖 增加1次计数
            }

            //当前 N 期内 后3号码 中过奖
            if($analysis_data->after_three_lucky_txt){
                $h3_lucky_number = 0;  //中奖了 归0
            }else{
                $h3_lucky_number += 1; //未中奖 增加1次计数
            }
        }

        $mail_contents = null; //初始化邮件内容
        //前三 中奖次数是否达到 报警状态 大于报警期数 不报警 等到周期走完
        if($q3_lucky_number == $this->regret_number){
            $mail_contents .= '前:'.$this->regret_number .' N <br/>';
        }

        //中三 中奖次数是否达到 报警状态 大于报警期数 不报警 等到周期走完
        if($z3_lucky_number == $this->regret_number){
            $mail_contents .= '中:'.$this->regret_number .' N <br/>';
        }

        //后三 中奖次数是否达到 报警状态 大于报警期数 不报警 等到周期走完
        if($h3_lucky_number == $this->regret_number){
            $mail_contents .= '后:'.$this->regret_number .' N <br/>';
        }

        $title = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
            .'通知类型:'.$this->cp_alias_name.' - 当前 '.$this->regret_number.' 期 警报<br/>'
            .'数据包别名:'.$this->alias .'<br/>';

        //如果 达到报警条件 则报警
        if($mail_contents){
            $mail_contents = $title.$mail_contents;
        }

        echo '当前彩种: '.$this->cp_name.' 数据包别名:'.$this->alias
            ."\r\n".' 前三:'.$q3_lucky_number.' N '
            ."\r\n".' 中三:'.$z3_lucky_number.' N '
            ."\r\n".' 后三:'.$h3_lucky_number.' N'."\r\n";

        $this->danger_email_contents .= $mail_contents;

        /*
        //多少期内 未中奖的报警期数
        $regret_number = $this->regret_number;
        $codes = $this->model->find()->orderBy('time DESC')->limit($regret_number)->all();
        //如果 用户设置的报警期数 不等于 查询出来的数据条数 则不执行报警 (数据库里的数据小于报警期数)
        if(count($codes) != $regret_number){
            echo $this->cp_name.' 数据包别名: '.$this->alias. " - 时时彩报抓取数据还不满报警期数条数,请等待达到报警条件 时间:".date('Y-m-d H:i:s')."\r\n";
            return;
        }

        $q3_status = false; //前3 是 在当前警报期内中过奖 默认false
        $z3_status = false; //中3 是 在当前警报期内中过奖 默认false
        $h3_status = false; //后3 是 在当前警报期内中过奖 默认false

        foreach ($codes as $key=>$m){
            //获取当前彩种 分析数据中的 数据包 N 的分析数据
            $analysis_data = $m->getAnalysis($this->data_txt_id)->one();
            //当前 N 期内 前3号码 中过奖
            if($analysis_data->front_three_lucky_txt){
                $q3_status = true;
            }
            //当前 N 期内 中3号码 中过奖
            if($analysis_data->center_three_lucky_txt){
                $z3_status = true;
            }
            //当前 N 期内 后3号码 中过奖
            if($analysis_data->after_three_lucky_txt){
                $h3_status = true;
            }
        }

        //当前警报期内 前3 中过 中3 中过 后3中过
        if($q3_status && $z3_status && $h3_status){
            return;
        }

        $q3_status ? $q3 = 'Y' : $q3 = 'N';
        $z3_status ? $z3 = 'Y' : $z3 = 'N';
        $h3_status ? $h3 = 'Y' : $h3 = 'N';

        $mail_contents = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
            .'通知类型:'.$this->cp_alias_name.' - 当前 '.$regret_number.' 期 警报<br/>'
            .'数据包别名:'.$this->alias .'<br/>'
            .'前:'.$q3 .'<br/>'
            .'中:'.$z3 .'<br/>'
            .'后:'.$h3 .'<br/><br/>';

        $this->danger_email_contents .= $mail_contents;
        */
    }

    /**
     * 每一期开奖通知
     */
    private function forever(){
        $newest = $this->model->find()->orderBy('time DESC')->one();
        //获取当前彩种 分析数据中的 数据包 N 的分析数据
        $analysis_data = $newest->getAnalysis($this->data_txt_id)->one();
        //数据包N 分析的结果
        $analysis_data->front_three_lucky_txt  ? $q3 = 'Y' : $q3 = 'N' ;
        $analysis_data->center_three_lucky_txt ? $z3 = 'Y' : $z3 = 'N' ;
        $analysis_data->after_three_lucky_txt  ? $h3 = 'Y' : $h3 = 'N' ;

        $mail_contents = '<a href="http://'.$_SERVER['SERVER_NAME'].'">传送门--->小蛮牛数据平台</a><br/>'
            .'通知类型:'.$this->cp_name.' - [时时彩] 每一期开奖通知<br/>'
            .'当前期号:'.$newest->qishu .'<br/>'
            .'开奖号码:'.$newest->code.'<br/>'
            .'数据包别名:'.$this->alias.' - 前三中奖:'.$q3 .'<br/>'
            .'数据包别名:'.$this->alias.' - 中三中奖:'.$z3 .'<br/>'
            .'数据包别名:'.$this->alias.' - 后三中奖:'.$h3 .'<br/><br/>';

        $this->forever_email_contents .= $mail_contents;
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
    }


    /**
     * 获取警报设置配置项
     */
    private function get_config(){
        if($this->type == 'cq'){
            $config = Cqdata::find()->where(['state'=>1])->select('id,alias,regret_number,start,end,forever')->asArray()->all();
        }
        if($this->type == 'tj'){
            $config = Tjdata::find()->where(['state'=>1])->select('id,alias,regret_number,start,end,forever')->asArray()->all();
        }
        if($this->type == 'xj'){
            $config = Xjdata::find()->where(['state'=>1])->select('id,alias,regret_number,start,end,forever')->asArray()->all();
        }
        $this->config = $config;
    }

    /**
     * 发送邮件
     * @param $content  邮件内容;
     * @param int $type 邮件类容 1为 每一期中奖邮件通知 2 为 N期未中奖邮件通知;
     */
    private function send_mail($content ,$type = 1){
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
                $type == 1
                    ? $msg = $this->cp_name.'时时彩 每一期邮寄通知'
                    : $msg = $this->cp_name.'时时彩 N期未中奖邮件通知';
                echo $msg." 邮件发送成功 时间:".date('Y-m-d H:i:s')."\r\n";
            }else{
                echo " 邮件通知发送失败,请尽快与管理员联系 时间:".date('Y-m-d H:i:s')."\r\n";
            }
        }
    }

}