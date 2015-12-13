<?php

namespace app\controllers\admin;

use app\models\Log;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

class LogController extends BaseController
{
    public function actionIndex()
    {
        $data = Log::find()->orderBy('time DESC');
        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '10']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->all();


        if($page = \Yii::$app->request->get('page')){
            if(intval(ceil($data->count()/10)) < $page){
                return false;
            }
            return $this->renderAjax('_list',['model'=>$model]);
        }

        return $this->render('index',['model'=>$model]);
    }

}
