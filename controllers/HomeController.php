<?php

namespace app\controllers;

use app\models\Code;
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

}
