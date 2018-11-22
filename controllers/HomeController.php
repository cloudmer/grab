<?php

namespace app\controllers;

use app\models\AlarmRecord;
use app\models\Bjdata;
use app\models\Bjssc;
use app\models\Code;
use app\models\Codeold;
use app\models\Cqdata;
use app\models\Cqssc;
use app\models\Newcode;
use app\models\Newcodedata;
use app\models\Tjdata;
use app\models\Tjssc;
use app\models\Txdata;
use app\models\Txffc;
use app\models\Xjdata;
use app\models\Xjssc;
use yii\data\Pagination;

class HomeController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->redirect('/home/new-code?type=1');

        $this->redirect('home/cqssc');
        $type = \Yii::$app->request->get('type') ? \Yii::$app->request->get('type') : 1;
        $data = Code::find()->where(['type'=>$type])->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '3']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/3)) < $page){
                return false;
            }
            return $this->renderAjax('_list',['model'=>$model]);
        }

        return $this->render('index',['model'=>$model]);

        /*
        $type = 1;
        if(\Yii::$app->request->get('type')){
            $type = \Yii::$app->request->get('type');
        }
        $model = Code::find()->where(['type'=>$type])->orderBy('time DESC')->all();
        return $this->render('index',['model'=>$model]);
        */
    }

    public function actionOld(){
        $type = \Yii::$app->request->get('type') ? \Yii::$app->request->get('type') : 1;
        $data = Codeold::find()->where(['type'=>$type])->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '3']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/3)) < $page){
                return false;
            }
            return $this->renderAjax('_oldlist',['model'=>$model]);
        }

        return $this->render('old',['model'=>$model]);

    }

    /**
     * 重庆时时彩
     */
    public function actionCqssc(){
        $type = \Yii::$app->request->get('type');

        list($data_packet,$default) = $this->get_data_packet('cq');
        !$type ? $type = $default->id : false;

        $data = Cqssc::find()->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/10)) < $page){
                return false;
            }
            return $this->renderAjax('/home/cqssc/_list',['model'=>$model,'type'=>$type]);
        }

        return $this->render('/home/cqssc/index',['model'=>$model,'type'=>$type,'data_packet'=>$data_packet]);
    }

    /**
     * 天津时时彩
     */
    public function actionTjssc(){
        $type = \Yii::$app->request->get('type');

        list($data_packet,$default) = $this->get_data_packet('tj');
        !$type ? $type = $default->id : false;

        $data = Tjssc::find()->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/10)) < $page){
                return false;
            }
            return $this->renderAjax('/home/tjssc/_list',['model'=>$model,'type'=>$type]);
        }

        return $this->render('/home/tjssc/index',['model'=>$model,'type'=>$type,'data_packet'=>$data_packet]);
    }

    /**
     * 新疆时时彩
     */
    public function actionXjssc(){
        $type = \Yii::$app->request->get('type');

        list($data_packet,$default) = $this->get_data_packet('xj');
        !$type ? $type = $default->id : false;

        $data = Xjssc::find()->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/10)) < $page){
                return false;
            }
            return $this->renderAjax('/home/xjssc/_list',['model'=>$model,'type'=>$type]);
        }

        return $this->render('/home/xjssc/index',['model'=>$model,'type'=>$type,'data_packet'=>$data_packet]);
    }

    /**
     * 北京时时彩
     * @return bool|string
     */
    public function actionBjssc(){
        $type = \Yii::$app->request->get('type');

        list($data_packet,$default) = $this->get_data_packet('bj');
        !$type ? $type = $default->id : false;

        $data = Bjssc::find()->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/10)) < $page){
                return false;
            }
            return $this->renderAjax('/home/bjssc/_list',['model'=>$model,'type'=>$type]);
        }

        return $this->render('/home/bjssc/index',['model'=>$model,'type'=>$type,'data_packet'=>$data_packet]);
    }


    /**
     * 腾讯分分彩
     * @return bool|string
     */
    public function actionTxffc(){
        $type = \Yii::$app->request->get('type');

        list($data_packet,$default) = $this->get_data_packet('tx');
        !$type ? $type = $default->id : false;

        $data = Txffc::find()->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/10)) < $page){
                return false;
            }
            return $this->renderAjax('/home/txffc/_list',['model'=>$model,'type'=>$type]);
        }

        return $this->render('/home/txffc/index',['model'=>$model,'type'=>$type,'data_packet'=>$data_packet]);
    }

    /**
     * 数据分组
     */
    public function actionGrouping(){
        $type = \Yii::$app->request->post('type');
        !$type ? $type = 1 : false;

        $error_msg = null;
        if(\Yii::$app->request->post()){
            /*
            if(!\Yii::$app->request->post('date')){
                $error_msg = '请选择查询日期';
            }
            */
            if(!\Yii::$app->request->post('cp_type')){
                $error_msg = '请选择彩票类型';
            }
            if(!\Yii::$app->request->post('cp_unit')){
                $error_msg = '请选择分组单位';
            }
            if(\Yii::$app->request->post('cp_unit_val') == ''){
                $error_msg = '请选择单位值';
            }
        }

        $model = null;
        if(!$error_msg){
            $model = $this->getdate(\Yii::$app->request->post('cp_type'));
        }

        return $this->render('/home/grouping/index',[
            'error_msg' => $error_msg,
            'model'     => $model,
            'type'      => \Yii::$app->request->post('cp_type'),
            'name'      => $this->getUnit(\Yii::$app->request->post('cp_unit')),
            'unit'      => \Yii::$app->request->post('cp_unit'),
            'unit_val'      => \Yii::$app->request->post('cp_unit_val'),
//            'data_type'     => $type,
            'data_txt_id'     => $type,
        ]);
    }

    /**
     * 获取分组数据
     */
    private function getdate($type){
        //查询选择时间的前2天数据
        $name = $this->getUnit(\Yii::$app->request->post('cp_unit'));
        $val = \Yii::$app->request->post('cp_unit_val');

        if($type == 'cq'){
            //重庆时时彩
            //查询选择号码 最新出现的位置
            $newest = Cqssc::find()->where([$name=>$val])->orderBy('time DESC')->limit(1)->select('time')->one();
            if(!$newest){
                //还没有出现过此号码
                return false;
            }
            $end_time = $newest->time;
            $start_time = $end_time - (86400 * 2); // 比此号码出现的时间 - 2天的时间

            $cqssc = Cqssc::find()->andWhere(['>=','time',$start_time])->andWhere(['<=','time',$end_time])->orderBy('time ASC')->all();

            //检测 当前查询出来的数据中最后一起是不是最新的开奖期数 如果不是 那么他后面还有开奖数据
            if($cqssc){
                //获取查询结果的最后一条数据
                //如果只查询出一条数据
                if(count($cqssc) == 1){
                    $last = $cqssc[0];
                }else{
                    $last = $cqssc[count($cqssc)-1];
                }

                //最新一起的开奖数据 的期数
                $newest_qishu = Cqssc::find()->orderBy('time DESC')->limit(1)->select('qishu')->one();
                $newest_qishu = $newest_qishu->qishu;
                //如果 查询结果的最后一条数据的开奖期数 不等于 当前彩种最新一期的开奖起期号 那么查询结果最后一条数据 后面还有数据
                if($newest_qishu != $last->qishu){
                    $increase = Cqssc::find()->andWhere(['>','qishu',$last->qishu])->orderBy('time ASC')->one();
                    array_push($cqssc,$increase);
                }
            }

            $newCqssc = [];
            $number = 0;
            foreach ($cqssc as $key=>$m){
                if($m->$name == $val){
                    $newCqssc [] = $m;
                    $number = 1;
                }else{
                    if($number>0 && $number<2){
                        $newCqssc [] = $m;
                        $number += 1;
                    }
                    if($number == 2){
                        $number = 0;
                    }
                }
            }

            return $newCqssc;
        }
        if($type == 'tj'){
            //天津时时彩
            //查询选择号码 最新出现的位置
            $newest = Tjssc::find()->where([$name=>$val])->orderBy('time DESC')->limit(1)->select('time')->one();
            if(!$newest){
                //还没有出现过此号码
                return false;
            }
            $end_time = $newest->time;
            $start_time = $end_time - (86400 * 2); // 比此号码出现的时间 - 2天的时间

            $tjssc = Tjssc::find()->andWhere(['>=','time',$start_time])->andWhere(['<=','time',$end_time])->orderBy('time ASC')->all();


            //检测 当前查询出来的数据中最后一起是不是最新的开奖期数 如果不是 那么他后面还有开奖数据
            if($tjssc){
                //获取查询结果的最后一条数据
                //如果只查询出一条数据
                if(count($tjssc) == 1){
                    $last = $tjssc[0];
                }else{
                    $last = $tjssc[count($tjssc)-1];
                }

                //最新一起的开奖数据 的期数
                $newest_qishu = Tjssc::find()->orderBy('time DESC')->limit(1)->select('qishu')->one();
                $newest_qishu = $newest_qishu->qishu;

                //如果 查询结果的最后一条数据的开奖期数 不等于 当前彩种最新一期的开奖起期号 那么查询结果最后一条数据 后面还有数据
                if($newest_qishu != $last->qishu){
                    $increase = Tjssc::find()->andWhere(['>','qishu',$last->qishu])->orderBy('time ASC')->one();
                    array_push($tjssc,$increase);
                }
            }

            $newTjssc = [];
            $number = 0;
            foreach ($tjssc as $key=>$m){
                if($m->$name == $val){
                    $newTjssc [] = $m;
                    $number = 1;
                }else{
                    if($number>0 && $number<2){
                        $newTjssc [] = $m;
                        $number += 1;
                    }
                    if($number == 2){
                        $number = 0;
                    }
                }
            }

            return $newTjssc;
        }
        if($type == 'xj'){
            //新疆时时彩
            //查询选择号码 最新出现的位置
            $newest = Xjssc::find()->where([$name=>$val])->orderBy('time DESC')->limit(1)->select('time')->one();
            if(!$newest){
                //还没有出现过此号码
                return false;
            }
            $end_time = $newest->time;
            $start_time = $end_time - (86400 * 2); // 比此号码出现的时间 - 2天的时间

            $xjssc = Xjssc::find()->andWhere(['>=','time',$start_time])->andWhere(['<=','time',$end_time])->orderBy('time ASC')->all();

            //检测 当前查询出来的数据中最后一起是不是最新的开奖期数 如果不是 那么他后面还有开奖数据
            if($xjssc){
                //获取查询结果的最后一条数据
                //如果只查询出一条数据
                if(count($xjssc) == 1){
                    $last = $xjssc[0];
                }else{
                    $last = $xjssc[count($xjssc)-1];
                }

                //最新一起的开奖数据 的期数
                $newest_qishu = Xjssc::find()->orderBy('time DESC')->limit(1)->select('qishu')->one();
                $newest_qishu = $newest_qishu->qishu;

                //如果 查询结果的最后一条数据的开奖期数 不等于 当前彩种最新一期的开奖起期号 那么查询结果最后一条数据 后面还有数据
                if($newest_qishu != $last->qishu){
                    $increase = Xjssc::find()->andWhere(['>','qishu',$last->qishu])->orderBy('time ASC')->one();
                    array_push($xjssc,$increase);
                }
            }

            $newXjssc = [];
            $number = 0;
            foreach ($xjssc as $key=>$m){
                if($m->$name == $val){
                    $newXjssc [] = $m;
                    $number = 1;
                }else{
                    if($number>0 && $number<2){
                        $newXjssc [] = $m;
                        $number += 1;
                    }
                    if($number == 2){
                        $number = 0;
                    }
                }
            }

            return $newXjssc;
        }
        if($type == 'bj'){
            //北京时时彩
            //查询选择号码 最新出现的位置
            $newest = Bjssc::find()->where([$name=>$val])->orderBy('time DESC')->limit(1)->select('time')->one();
            if(!$newest){
                //还没有出现过此号码
                return false;
            }
            $end_time = $newest->time;
            $start_time = $end_time - (86400 * 2); // 比此号码出现的时间 - 2天的时间

            $bjssc = Bjssc::find()->andWhere(['>=','time',$start_time])->andWhere(['<=','time',$end_time])->orderBy('time ASC')->all();

            //检测 当前查询出来的数据中最后一起是不是最新的开奖期数 如果不是 那么他后面还有开奖数据
            if($bjssc){
                //获取查询结果的最后一条数据
                //如果只查询出一条数据
                if(count($bjssc) == 1){
                    $last = $bjssc[0];
                }else{
                    $last = $bjssc[count($bjssc)-1];
                }

                //最新一起的开奖数据 的期数
                $newest_qishu = Bjssc::find()->orderBy('time DESC')->limit(1)->select('qishu')->one();
                $newest_qishu = $newest_qishu->qishu;

                //如果 查询结果的最后一条数据的开奖期数 不等于 当前彩种最新一期的开奖起期号 那么查询结果最后一条数据 后面还有数据
                if($newest_qishu != $last->qishu){
                    $increase = Bjssc::find()->andWhere(['>','qishu',$last->qishu])->orderBy('time ASC')->one();
                    array_push($bjssc,$increase);
                }
            }

            $newBjssc = [];
            $number = 0;
            foreach ($bjssc as $key=>$m){
                if($m->$name == $val){
                    $newBjssc [] = $m;
                    $number = 1;
                }else{
                    if($number>0 && $number<2){
                        $newBjssc [] = $m;
                        $number += 1;
                    }
                    if($number == 2){
                        $number = 0;
                    }
                }
            }

            return $newBjssc;
        }
    }

    /**
     * 获取单位情况
     */
    private function getUnit($unit){
        $name = null;
        switch ($unit){
            case 1:
                $name = 'one';
                break;
            case 2:
                $name = 'two';
                break;
            case 3:
                $name = 'three';
                break;
            case 4:
                $name = 'four';
                break;
            case 5:
                $name = 'five';
                break;
        }
        return $name;
    }


    /**
     * 获取数据包
     * @param $type 彩种类型
     * @return array|\yii\db\ActiveRecord[]
     */
    private function get_data_packet($type){
        if($type == 'cq'){
            $model = Cqdata::find()->select('id,alias')->all();
            $default = Cqdata::find()->select('id,alias')->orderBy('time ASC')->one();
        }
        if($type == 'xj'){
            $model = Xjdata::find()->select('id,alias')->all();
            $default = Xjdata::find()->select('id,alias')->orderBy('time ASC')->one();
        }
        if($type == 'tj'){
            $model = Tjdata::find()->select('id,alias')->all();
            $default = Tjdata::find()->select('id,alias')->orderBy('time ASC')->one();
        }
        if($type == 'bj'){
            $model = Bjdata::find()->select('id,alias')->all();
            $default = Bjdata::find()->select('id,alias')->orderBy('time ASC')->one();
        }
        if ($type == 'tx'){
            $model = Txdata::find()->select('id,alias')->all();
            $default = Txdata::find()->select('id,alias')->orderBy('time ASC')->one();
        }
        return [$model,$default];
    }

    /**
     * 获取彩种类型数据包
     */
    public function actionDataPacket(){
        $type = \Yii::$app->request->post('type');
        if($type == 'cq'){
            $model = Cqdata::find()->select('id,alias')->asArray()->all();
        }
        if($type == 'xj'){
            $model = Xjdata::find()->select('id,alias')->asArray()->all();
        }
        if($type == 'tj'){
            $model = Tjdata::find()->select('id,alias')->asArray()->all();
        }
        if($type == 'bj'){
            $model = Bjdata::find()->select('id,alias')->asArray()->all();
        }
        echo json_encode($model);
    }

    public function actionNewCode(){
        $type = \Yii::$app->request->get('type');
        $package_id = \Yii::$app->request->get('package_id');
        $data_packet = Newcodedata::find()->select('id,alias')->where(['type'=>$type])->all();
        $default = Newcodedata::find()->select('id,alias')->where(['type'=>$type])->orderBy('time ASC')->one();
        !$package_id ? $package_id = $default->id : false;

        $data = Newcode::find()->where(['type'=>$type])->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/10)) < $page){
                return false;
            }
            return $this->renderAjax('/home/new-code/_list',['model'=>$model,'type'=>$type, 'package_id'=>$package_id]);
        }

        return $this->render('/home/new-code/index',['model'=>$model,'type'=>$type,'data_packet'=>$data_packet, 'package_id' => $package_id]);
    }

    /**
     * 重庆 2连 统计
     */
    public function actionCqStatistics(){
        /*
        // 分组报警期数
        $arModel = new AlarmRecord();
        $aryCycle = $arModel->cqGrupCqCycle();
        if (!$aryCycle) {
            exit('重庆暂无统计');
        }

        $cycle = \Yii::$app->request->get('cycle');
        !$cycle ? $cycle = $aryCycle[0] : false;

        $data = Cqssc::find()->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/10)) < $page){
                return false;
            }
            return $this->renderAjax('/home/cq-statistics/_list',['model'=>$model,'cycle'=>$cycle, 'aryCycle' => $aryCycle ]);
        }

        return $this->render('/home/cq-statistics/index',['model'=>$model,'cycle'=>$cycle, 'aryCycle' => $aryCycle]);
        */

        $arModel = new AlarmRecord();
        $aryCycle = $arModel->cqGrupCqCycle();
        if (!$aryCycle) {
            exit('重庆暂无统计');
        }

        $cycle = \Yii::$app->request->get('cycle');
        !$cycle ? $cycle = $aryCycle[0] : false;

        $data = AlarmRecord::find()->where([ 'cp_type' => AlarmRecord::cqType, 'cycle' => $cycle ])->orderBy('created_at DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '5']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/5)) < $page){
                return false;
            }
            return $this->renderAjax('/home/cq-statistics/_list',['model'=>$model,'cycle'=>$cycle, 'aryCycle' => $aryCycle ]);
        }

        return $this->render('/home/cq-statistics/index',['model'=>$model,'cycle'=>$cycle, 'aryCycle' => $aryCycle]);
    }

    /**
     * 新疆 2连 统计
     */
    public function actionXjStatistics(){
        // 分组报警期数
        $arModel = new AlarmRecord();
        $aryCycle = $arModel->xjGrupCqCycle();
        if (!$aryCycle) {
            exit('新疆暂无统计');
        }

        $cycle = \Yii::$app->request->get('cycle');
        !$cycle ? $cycle = $aryCycle[0] : false;

        $data = AlarmRecord::find()->where([ 'cp_type' => AlarmRecord::xjType, 'cycle' => $cycle ])->orderBy('created_at DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '5']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/5)) < $page){
                return false;
            }
            return $this->renderAjax('/home/xj-statistics/_list',['model'=>$model,'cycle'=>$cycle, 'aryCycle' => $aryCycle ]);
        }

        return $this->render('/home/xj-statistics/index',['model'=>$model,'cycle'=>$cycle, 'aryCycle' => $aryCycle]);
    }
}
