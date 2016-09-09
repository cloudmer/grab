<?php

namespace app\controllers;

use app\models\Code;
use app\models\Codeold;
use app\models\Tjssc;
use app\models\Xjssc;
use yii\data\Pagination;

class HomeController extends \yii\web\Controller
{
    public function actionIndex()
    {

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
        $data = Codeold::find()->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/10)) < $page){
                return false;
            }
            return $this->renderAjax('/home/cqssc/_list',['model'=>$model]);
        }

        return $this->render('/home/cqssc/index',['model'=>$model]);
    }

    /**
     * 天津时时彩
     */
    public function actionTjssc(){
        $data = Tjssc::find()->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/10)) < $page){
                return false;
            }
            return $this->renderAjax('/home/tjssc/_list',['model'=>$model]);
        }

        return $this->render('/home/tjssc/index',['model'=>$model]);
    }

    /**
     * 新疆时时彩
     */
    public function actionXjssc(){
        $data = Xjssc::find()->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();

        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/10)) < $page){
                return false;
            }
            return $this->renderAjax('/home/xjssc/_list',['model'=>$model]);
        }

        return $this->render('/home/xjssc/index',['model'=>$model]);
    }


    /**
     * 数据分组
     */
    public function actionGrouping(){
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
        ]);
    }

    /**
     * 获取分组数据
     */
    private function getdate($type){
        //查询选择时间的前2天数据
//        $date = \Yii::$app->request->post('date');
        $date = date('Y-m-d');
        $start_time = strtotime( "$date -2 day" );
        $end_time = strtotime( $date );
        $name = $this->getUnit(\Yii::$app->request->post('cp_unit'));
        $val = \Yii::$app->request->post('cp_unit_val');
        if($type == 1){
            //重庆时时彩
            $cqssc = Codeold::find()->andWhere(['>=','time',$start_time])->andWhere(['<','time',$end_time])->orderBy('time ASC')->all();
            $newCqssc = [];
            $number = 0;
            foreach ($cqssc as $key=>$m){
                //将开奖号中间的空格去掉
                $code = str_replace(" ", '', $m->code);
                if($code[\Yii::$app->request->post('cp_unit') -1 ] == $val){
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
        if($type == 2){
            //天津时时彩
            $tjssc = Tjssc::find()->andWhere(['>=','time',$start_time])->andWhere(['<','time',$end_time])->orderBy('time ASC')->all();

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
        if($type == 3){
            //新疆时时彩
            $xjssc = Xjssc::find()->andWhere(['>=','time',$start_time])->andWhere(['<','time',$end_time])->orderBy('time ASC')->all();

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

}
