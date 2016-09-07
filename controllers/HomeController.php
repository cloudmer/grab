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

}
