<?php

namespace app\controllers\admin;
use app\models\Configure;
use Yii;


class DangerController extends BaseController
{
    public function actionIndex()
    {
        $model = Configure::findOne(['id'=>1]);
        if(Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->save()){
                return $this->render('index',['msg'=>'修改成功','model'=>$model]);
            }
        };
        return $this->render('index',['model'=>$model]);
    }

}
