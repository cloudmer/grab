<?php

namespace app\controllers\admin;
use app\models\Configure;
use Yii;


class DangerController extends BaseController
{
    public function actionIndex()
    {
        $model = Configure::findOne(['type'=>1]);
        $modelOld = Configure::findOne(['type'=>2]);
        $modelTj = Configure::findOne(['type'=>3]);
        $modelXj = Configure::findOne(['type'=>4]);
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post('Configure');
            $type = $post['type'];
            if($type == 1){
                $model = $model;
            }
            if($type == 2){
                $model = $modelOld;
            }
            if($type == 3){
                $model = $modelTj;
            }
            if($type == 4){
                $model = $modelXj;
            }
            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->save()){
                return $this->render('index',['msg'.$type=>'修改成功','model'=>$model,'modelOld'=>$modelOld,'modelTj'=>$modelTj,'modelXj'=>$modelXj]);
            }
        };
        return $this->render('index',['model'=>$model,'modelOld'=>$modelOld,'modelTj'=>$modelTj,'modelXj'=>$modelXj]);
    }

}
