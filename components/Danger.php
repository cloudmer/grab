<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 16-9-10
 * Time: 上午11:27
 */

namespace app\components;


use app\models\Cqssc;
use app\models\Reserve;
use app\models\Tjssc;
use app\models\Xjssc;
use app\models\Mailbox;
use Yii;

class Danger
{

    /* 彩票类型 */
    public $cptype;

    /* 后台添加的预定报警号码 */
    public $config;

    /**
     * 连续 9期 组6 没开 89组合 与 45 组合 邮件报警
     * Danger constructor.
     */
    public function __construct($cptype)
    {
        $this->cptype = $cptype;
        //获取后台添加的预定报警号码
        $this->get_config();
        $this->get_codes();
    }

    /**
     * 获取添加的预定报警号码
     */
    private function get_config(){
        if($this->cptype == 'cq'){
            //重庆时时彩
            $config = Reserve::find()->where(['cp_type'=>1])->all();
            $name = '重庆';
        }
        if($this->cptype == 'tj'){
            ///天津时时彩
            $config = Reserve::find()->where(['cp_type'=>2])->all();
            $name = '天津';
        }
        if($this->cptype == 'xj'){
            //新疆时时彩
            $config = Reserve::find()->where(['cp_type'=>3])->all();
            $name = '新疆';
        }
        if(!$config){
            echo '系统还未添加 -['.$name.'彩票] 预定报警号码,请先添加'."\r\n";
        }
        $this->config = $config;
    }


    /**
     * 获取 开奖数据
     */
    private function get_codes(){
        // 暂无配置项
        if(!$this->config){
            return;
        }

        //重庆时时彩
        if($this->cptype == 'cq'){
            $email_contents = null; // 邮件内容初始化
            //获取重庆时时彩最新的一条数据
            $newest = Cqssc::find()->orderBy('time DESC')->limit(1)->one();
            foreach ($this->config as $key=>$m){
                $type = $m->type; //报警单位, 1=>不论前3中3后3 都报警 2=>前3 3=>中3 4=>后3
                $code_type = $m->code_type; //奖号类型, 1=>组6 2=>组3
                $number = $m->number; //预定报警号码
                $qishu = $m->qishu; //几期不开则报警
                $status = $m->status; //报警状态 1=>开启 0=>关闭
                if($status == 0){
                    //当前关闭报警,跳过
                    echo '重庆时时彩,当前关闭预定号码报警阀门'."\r\n";
                    return;
                }

                //当前报警设置 前三 中三 后三 的组6 都报警
                if($type == 1 && $code_type == 1){
                    // 重庆时时彩 前三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->cq_front_three_six_show($newest,$number,$qishu);
                    // 重庆时时彩 中三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->cq_center_three_six_show($newest,$number,$qishu);
                    // 重庆时时彩 后三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->cq_after_three_six_show($newest,$number,$qishu);
                }

                //当前报警设置 前三 中三 后三 的组3 都报警
                if($type == 1 && $code_type == 1){
                    // 重庆时时彩 前三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->cq_front_three_three_show($newest,$number,$qishu);
                    // 重庆时时彩 中三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->cq_center_three_three_show($newest,$number,$qishu);
                    // 重庆时时彩 后三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->cq_after_three_three_show($newest,$number,$qishu);
                }

                //前三 组6 报警
                if($type == 2 && $code_type == 1){
                    // 重庆时时彩 前三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->cq_front_three_six_show($newest,$number,$qishu);
                }
                //前三 组3 报警
                if($type == 2 && $code_type == 2){
                    // 重庆时时彩 前三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->cq_front_three_three_show($newest,$number,$qishu);
                }
                //中三 组6 报警
                if($type == 3 && $code_type == 1){
                    // 重庆时时彩 中三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->cq_center_three_six_show($newest,$number,$qishu);
                }

                //中三 组3 报警
                if($type == 3 && $code_type == 3){
                    // 重庆时时彩 中三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->cq_center_three_three_show($newest,$number,$qishu);
                }

                //后三 组6 报警
                if($type == 4 && $code_type == 1){
                    // 重庆时时彩 后三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->cq_after_three_six_show($newest,$number,$qishu);
                }

                //后三 组3 报警
                if($type == 4 && $code_type == 2){
                    // 重庆时时彩 后三 组3 预定号码出现的位置 并报警
                    $email_contents .=  $this->cq_after_three_three_show($newest,$number,$qishu);
                }

            }

            //$email_contents 报警发送
            if($email_contents){
                $this->send_mail($email_contents);
            }
        }




        ///天津时时彩
        if($this->cptype == 'tj'){
            $email_contents = null; // 邮件内容初始化
            //获取天津时时彩最新的一条数据
            $newest = Tjssc::find()->orderBy('time DESC')->limit(1)->one();
            foreach ($this->config as $key=>$m) {
                $type = $m->type; //报警单位, 1=>不论前3中3后3 都报警 2=>前3 3=>中3 4=>后3
                $code_type = $m->code_type; //奖号类型, 1=>组6 2=>组3
                $number = $m->number; //预定报警号码
                $qishu = $m->qishu; //几期不开则报警
                $status = $m->status; //报警状态 1=>开启 0=>关闭
                if ($status == 0) {
                    //当前关闭报警,跳过
                    echo '天津时时彩,当前关闭预定号码报警阀门' . "\r\n";
                    return;
                }

                //当前报警设置 前三 中三 后三 的组6 都报警
                if($type == 1 && $code_type == 1){
                    // 天津时时彩 前三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_front_three_six_show($newest,$number,$qishu);
                    // 天津时时彩 中三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_center_three_six_show($newest,$number,$qishu);
                    // 天津时时彩 后三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_after_three_six_show($newest,$number,$qishu);
                }

                //当前报警设置 前三 中三 后三 的组3 都报警
                if($type == 1 && $code_type == 1){
                    // 天津时时彩 前三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_front_three_three_show($newest,$number,$qishu);
                    // 天津时时彩 中三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_center_three_three_show($newest,$number,$qishu);
                    // 天津时时彩 后三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_after_three_three_show($newest,$number,$qishu);
                }

                //前三 组6 报警
                if ($type == 2 && $code_type == 1) {
                    // 天津时时彩 前三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_front_three_six_show($newest, $number, $qishu);
                }
                //前三 组3 报警
                if ($type == 2 && $code_type == 2) {
                    // 天津时时彩 前三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_front_three_three_show($newest, $number, $qishu);
                }
                //中三 组6 报警
                if ($type == 3 && $code_type == 1) {
                    // 天津时时彩 中三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_center_three_six_show($newest, $number, $qishu);
                }

                //中三 组3 报警
                if ($type == 3 && $code_type == 3) {
                    // 天津时时彩 中三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_center_three_three_show($newest, $number, $qishu);
                }

                //后三 组6 报警
                if ($type == 4 && $code_type == 1) {
                    // 天津时时彩 后三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_after_three_six_show($newest, $number, $qishu);
                }

                //后三 组3 报警
                if ($type == 4 && $code_type == 2) {
                    // 天津时时彩 后三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->tj_after_three_three_show($newest, $number, $qishu);
                }
            }

            //$email_contents 报警发送
            if($email_contents){
                $this->send_mail($email_contents);
            }

        }



        //新疆时时彩
        if($this->cptype == 'xj'){

            $email_contents = null; // 邮件内容初始化
            //获取新疆时时彩最新的一条数据
            $newest = Xjssc::find()->orderBy('time DESC')->limit(1)->one();
            foreach ($this->config as $key=>$m) {
                $type = $m->type; //报警单位, 1=>不论前3中3后3 都报警 2=>前3 3=>中3 4=>后3
                $code_type = $m->code_type; //奖号类型, 1=>组6 2=>组3
                $number = $m->number; //预定报警号码
                $qishu = $m->qishu; //几期不开则报警
                $status = $m->status; //报警状态 1=>开启 0=>关闭
                if ($status == 0) {
                    //当前关闭报警,跳过
                    echo '新疆时时彩,当前关闭预定号码报警阀门' . "\r\n";
                    return;
                }

                //当前报警设置 前三 中三 后三 的组6 都报警
                if($type == 1 && $code_type == 1){
                    // 新疆时时彩 前三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_front_three_six_show($newest,$number,$qishu);
                    // 新疆时时彩 中三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_center_three_six_show($newest,$number,$qishu);
                    // 新疆时时彩 后三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_after_three_six_show($newest,$number,$qishu);
                }

                //当前报警设置 前三 中三 后三 的组3 都报警
                if($type == 1 && $code_type == 1){
                    // 新疆时时彩 前三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_front_three_three_show($newest,$number,$qishu);
                    // 新疆时时彩 中三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_center_three_three_show($newest,$number,$qishu);
                    // 天津时时彩 后三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_after_three_three_show($newest,$number,$qishu);
                }

                //前三 组6 报警
                if ($type == 2 && $code_type == 1) {
                    // 新疆时时彩 前三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_front_three_six_show($newest, $number, $qishu);
                }
                //前三 组3 报警
                if ($type == 2 && $code_type == 2) {
                    // 新疆时时彩 前三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_front_three_three_show($newest, $number, $qishu);
                }
                //中三 组6 报警
                if ($type == 3 && $code_type == 1) {
                    // 新疆时时彩 中三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_center_three_six_show($newest, $number, $qishu);
                }

                //中三 组3 报警
                if ($type == 3 && $code_type == 3) {
                    // 新疆时时彩 中三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_center_three_three_show($newest, $number, $qishu);
                }

                //后三 组6 报警
                if ($type == 4 && $code_type == 1) {
                    // 新疆时时彩 后三 组6 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_after_three_six_show($newest, $number, $qishu);
                }

                //后三 组3 报警
                if ($type == 4 && $code_type == 2) {
                    // 新疆时时彩 后三 组3 预定号码出现的位置 并报警
                    $email_contents .= $this->xj_after_three_three_show($newest, $number, $qishu);
                }
            }

            //$email_contents 报警发送
            if($email_contents){
                $this->send_mail($email_contents);
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
     * 查询重庆时时彩 前三 组6形态 设定的号码 最后一次出现的位置
     * @param $newest;    重庆时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function cq_front_three_six_show($newest,$number,$qishu){
        //设定号码 前三 组6 最新出现的位置
        $result = Cqssc::find()->andWhere(['front_three_type'=>1])->andFilterWhere(['or',"one=$number","two=$number","three=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '重庆时时彩 前三 组6,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Cqssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 重庆时时彩 - 前三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 重庆时时彩 - 前三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else{
            echo '重庆时时彩 - 前三 组6 - 预定号码:'.$number.' 已经有'.$interval.'期未开奖了'."\r\n";;
        }
        return false;
    }

    /**
     * 查询重庆时时彩 前三 组3形态 设定的号码 最后一次出现的位置
     * @param $newest;    重庆时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function cq_front_three_three_show($newest,$number,$qishu){
        //设定号码 前三 组3 最新出现的位置
        $result = Cqssc::find()->andWhere(['front_three_type'=>2])->andFilterWhere(['or',"one=$number","two=$number","three=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '重庆时时彩 前三 组3,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Cqssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 重庆时时彩 - 前三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 重庆时时彩 - 前三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else {
            echo '重庆时时彩 - 前三 组3 - 预定号码:' . $number . ' 已经有' . $interval . '期未开奖了' . "\r\n";;
        }
        return false;
    }


    /**
     * 查询重庆时时彩 中三 组6形态 设定的号码 最后一次出现的位置
     * @param $newest;    重庆时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function cq_center_three_six_show($newest,$number,$qishu){
        //设定号码 中三 组6 最新出现的位置
        $result = Cqssc::find()->andWhere(['center_three_type'=>1])->andFilterWhere(['or',"two=$number","three=$number","four=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '重庆时时彩 中三 组6,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Cqssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 重庆时时彩 - 中三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 重庆时时彩 - 中三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else{
            echo '重庆时时彩 - 中三 组6 - 预定号码:'.$number.' 已经有'.$interval.'期未开奖了'."\r\n";;
        }
        return false;
    }

    /**
     * 查询重庆时时彩 中三 组3形态 设定的号码 最后一次出现的位置
     * @param $newest;    重庆时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function cq_center_three_three_show($newest,$number,$qishu){
        //设定号码 中三 组3 最新出现的位置
        $result = Cqssc::find()->andWhere(['center_three_type'=>2])->andFilterWhere(['or',"two=$number","three=$number","four=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '重庆时时彩 中三 组3,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Cqssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 重庆时时彩 - 中三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 重庆时时彩 - 中三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else {
            echo '重庆时时彩 - 中三 组3 - 预定号码:' . $number . ' 已经有' . $interval . '期未开奖了' . "\r\n";;
        }
        return false;
    }


    /**
     * 查询重庆时时彩 后三 组6形态 设定的号码 最后一次出现的位置
     * @param $newest;    重庆时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function cq_after_three_six_show($newest,$number,$qishu){
        //设定号码 中三 组6 最新出现的位置
        $result = Cqssc::find()->andWhere(['after_three_type'=>1])->andFilterWhere(['or',"three=$number","four=$number","five=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '重庆时时彩 后三 组6,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Cqssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 重庆时时彩 - 后三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 重庆时时彩 - 后三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else{
            echo '重庆时时彩 - 后三 组6 - 预定号码:'.$number.' 已经有'.$interval.'期未开奖了'."\r\n";;
        }
        return false;
    }

    /**
     * 查询重庆时时彩 中三 组3形态 设定的号码 最后一次出现的位置
     * @param $newest;    重庆时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function cq_after_three_three_show($newest,$number,$qishu){
        //设定号码 中三 组3 最新出现的位置
        $result = Cqssc::find()->andWhere(['after_three_type'=>2])->andFilterWhere(['or',"three=$number","four=$number","five=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '重庆时时彩 后三 组3,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Cqssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 重庆时时彩 - 后三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 重庆时时彩 - 后三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else {
            echo '重庆时时彩 - 中三 后3 - 预定号码:' . $number . ' 已经有' . $interval . '期未开奖了' . "\r\n";;
        }
        return false;
    }


    /**
     * 查询天津时时彩 前三 组6形态 设定的号码 最后一次出现的位置
     * @param $newest;    天津时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function tj_front_three_six_show($newest,$number,$qishu){
        //设定号码 前三 组6 最新出现的位置
        $result = Tjssc::find()->andWhere(['front_three_type'=>1])->andFilterWhere(['or',"one=$number","two=$number","three=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '天津时时彩 前三 组6,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Tjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 天津时时彩 - 前三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 天津时时彩 - 前三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else{
            echo '天津时时彩 - 前三 组6 - 预定号码:'.$number.' 已经有'.$interval.'期未开奖了'."\r\n";;
        }
        return false;
    }

    /**
     * 查询天津时时彩 前三 组3形态 设定的号码 最后一次出现的位置
     * @param $newest;    天津时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function tj_front_three_three_show($newest,$number,$qishu){
        //设定号码 前三 组3 最新出现的位置
        $result = Tjssc::find()->andWhere(['front_three_type'=>2])->andFilterWhere(['or',"one=$number","two=$number","three=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '天津时时彩 前三 组3,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Tjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 天津时时彩 - 前三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 天津时时彩 - 前三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else {
            echo '天津时时彩 - 前三 组3 - 预定号码:' . $number . ' 已经有' . $interval . '期未开奖了' . "\r\n";;
        }
        return false;
    }


    /**
     * 查询天津时时彩 中三 组6形态 设定的号码 最后一次出现的位置
     * @param $newest;    天津时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function tj_center_three_six_show($newest,$number,$qishu){
        //设定号码 中三 组6 最新出现的位置
        $result = Tjssc::find()->andWhere(['center_three_type'=>1])->andFilterWhere(['or',"two=$number","three=$number","four=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '天津时时彩 中三 组6,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Tjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 天津时时彩 - 中三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 天津时时彩 - 中三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else{
            echo '天津时时彩 - 中三 组6 - 预定号码:'.$number.' 已经有'.$interval.'期未开奖了'."\r\n";;
        }
        return false;
    }

    /**
     * 查询天津时时彩 中三 组3形态 设定的号码 最后一次出现的位置
     * @param $newest;    天津时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function tj_center_three_three_show($newest,$number,$qishu){
        //设定号码 中三 组3 最新出现的位置
        $result = Tjssc::find()->andWhere(['center_three_type'=>2])->andFilterWhere(['or',"two=$number","three=$number","four=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '天津时时彩 中三 组3,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Tjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 天津时时彩 - 中三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 天津时时彩 - 中三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else {
            echo '天津时时彩 - 中三 组3 - 预定号码:' . $number . ' 已经有' . $interval . '期未开奖了' . "\r\n";;
        }
        return false;
    }

    /**
     * 查询天津时时彩 后三 组6形态 设定的号码 最后一次出现的位置
     * @param $newest;    天津时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function tj_after_three_six_show($newest,$number,$qishu){
        //设定号码 中三 组6 最新出现的位置
        $result = Tjssc::find()->andWhere(['after_three_type'=>1])->andFilterWhere(['or',"three=$number","four=$number","five=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '天津时时彩 后三 组6,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Tjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 天津时时彩 - 后三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 天津时时彩 - 后三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else{
            echo '天津时时彩 - 后三 组6 - 预定号码:'.$number.' 已经有'.$interval.'期未开奖了'."\r\n";;
        }
        return false;
    }

    /**
     * 查询天津时时彩 中三 组3形态 设定的号码 最后一次出现的位置
     * @param $newest;    天津时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function tj_after_three_three_show($newest,$number,$qishu){
        //设定号码 中三 组3 最新出现的位置
        $result = Tjssc::find()->andWhere(['after_three_type'=>2])->andFilterWhere(['or',"three=$number","four=$number","five=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '天津时时彩 后三 组3,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Tjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 天津时时彩 - 后三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 天津时时彩 - 后三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else {
            echo '天津时时彩 - 中三 后3 - 预定号码:' . $number . ' 已经有' . $interval . '期未开奖了' . "\r\n";;
        }
        return false;
    }


    /**
     * 查询新疆时时彩 前三 组6形态 设定的号码 最后一次出现的位置
     * @param $newest;    新疆时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function xj_front_three_six_show($newest,$number,$qishu){
        //设定号码 前三 组6 最新出现的位置
        $result = Xjssc::find()->andWhere(['front_three_type'=>1])->andFilterWhere(['or',"one=$number","two=$number","three=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '新疆时时彩 前三 组6,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Xjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 新疆时时彩 - 前三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 新疆时时彩 - 前三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else{
            echo '新疆时时彩 - 前三 组6 - 预定号码:'.$number.' 已经有'.$interval.'期未开奖了'."\r\n";;
        }
        return false;
    }

    /**
     * 查询新疆时时彩 前三 组3形态 设定的号码 最后一次出现的位置
     * @param $newest;    新疆时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function xj_front_three_three_show($newest,$number,$qishu){
        //设定号码 前三 组3 最新出现的位置
        $result = Xjssc::find()->andWhere(['front_three_type'=>2])->andFilterWhere(['or',"one=$number","two=$number","three=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '新疆时时彩 前三 组3,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Xjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 新疆时时彩 - 前三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 新疆时时彩 - 前三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else {
            echo '新疆时时彩 - 前三 组3 - 预定号码:' . $number . ' 已经有' . $interval . '期未开奖了' . "\r\n";;
        }
        return false;
    }

    /**
     * 查询新疆时时彩 中三 组6形态 设定的号码 最后一次出现的位置
     * @param $newest;    新疆时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function xj_center_three_six_show($newest,$number,$qishu){
        //设定号码 中三 组6 最新出现的位置
        $result = Xjssc::find()->andWhere(['center_three_type'=>1])->andFilterWhere(['or',"two=$number","three=$number","four=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '新疆时时彩 中三 组6,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Xjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 新疆时时彩 - 中三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 新疆时时彩 - 中三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else{
            echo '新疆时时彩 - 中三 组6 - 预定号码:'.$number.' 已经有'.$interval.'期未开奖了'."\r\n";;
        }
        return false;
    }

    /**
     * 查询新疆时时彩 中三 组3形态 设定的号码 最后一次出现的位置
     * @param $newest;    新疆时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function xj_center_three_three_show($newest,$number,$qishu){
        //设定号码 中三 组3 最新出现的位置
        $result = Xjssc::find()->andWhere(['center_three_type'=>2])->andFilterWhere(['or',"two=$number","three=$number","four=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '新疆时时彩 中三 组3,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Xjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 新疆时时彩 - 中三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 新疆时时彩 - 中三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else {
            echo '新疆时时彩 - 中三 组3 - 预定号码:' . $number . ' 已经有' . $interval . '期未开奖了' . "\r\n";;
        }
        return false;
    }

    /**
     * 查询新疆时时彩 后三 组6形态 设定的号码 最后一次出现的位置
     * @param $newest;    新疆时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function xj_after_three_six_show($newest,$number,$qishu){
        //设定号码 中三 组6 最新出现的位置
        $result = Xjssc::find()->andWhere(['after_three_type'=>1])->andFilterWhere(['or',"three=$number","four=$number","five=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '新疆时时彩 后三 组6,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Xjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 新疆时时彩 - 后三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 新疆时时彩 - 后三 组6 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else{
            echo '新疆时时彩 - 后三 组6 - 预定号码:'.$number.' 已经有'.$interval.'期未开奖了'."\r\n";;
        }
        return false;
    }

    /**
     * 查询新疆时时彩 中三 组3形态 设定的号码 最后一次出现的位置
     * @param $newest;    天津时时彩最新的数据位置;
     * @param $number;    设定的报警号码值;
     * @param $qishu;     设定的报警期数;
     * @return string|void
     */
    private function xj_after_three_three_show($newest,$number,$qishu){
        //设定号码 中三 组3 最新出现的位置
        $result = Xjssc::find()->andWhere(['after_three_type'=>2])->andFilterWhere(['or',"three=$number","four=$number","five=$number"])->orderBy('time DESC')->one();
        //数据中 暂无 此号码在 出现过 - 数据库刚创建的情况才会出现
        if(!$result){
            echo '新疆时时彩 后三 组3,当前数据库中暂没有出现过 '.$number.' 的号码'."\r\n";
            return false;
        }

        //计算有多少期没有开过 设定号码的奖了
        $interval = Xjssc::find()->andWhere(['>','qishu',$result->qishu])->andWhere(['<=','qishu',$newest->qishu])->count();
        //间隔期数 大于 或 等于 报警期数就报警
        if( $interval >= $qishu ){
            echo '警告: 新疆时时彩 - 后三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."\r\n";
            //报警操作
            return '警告: 新疆时时彩 - 后三 组3 - 预定号码:'.$number.' 已经有'.$interval.' 期未开奖了'."<br>";
        }else {
            echo '新疆时时彩 - 中三 后3 - 预定号码:' . $number . ' 已经有' . $interval . '期未开奖了' . "\r\n";;
        }
        return false;
    }

}