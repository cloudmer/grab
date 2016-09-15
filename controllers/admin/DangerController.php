<?php

namespace app\controllers\admin;
use app\models\Configure;
use Yii;


class DangerController extends BaseController
{
    public function actionIndex()
    {
        $model = Configure::findOne(['type'=>1]);     //新时时彩
        $modelOld = Configure::findOne(['type'=>2]);  //重庆时时彩 数据包1 报警设置
        $modelCq2 = Configure::findOne(['type'=>22]); //重庆时时彩 数据包2 报警设置
        $modelTj = Configure::findOne(['type'=>3]);   //天津时时彩 数据包1 报警设置
        $modelTj2 = Configure::findOne(['type'=>33]); //天津时时彩 数据包2 报警设置
        $modelXj = Configure::findOne(['type'=>4]);   //新疆时时彩 数据包1 报警设置
        $modelXj2 = Configure::findOne(['type'=>44]); //新疆时时彩 数据包2 报警设置
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post('Configure');
            $type = $post['type'];
            if($type == 1){
                $model = $model; //新时时彩
            }
            if($type == 2){
                $model = $modelOld; //重庆时时彩 数据包1 报警设置
            }
            if($type == 22){
                $model = $modelCq2; //重庆时时彩 数据包2 报警设置
            }
            if($type == 3){
                $model = $modelTj;  //天津时时彩 数据包1 报警设置
            }
            if($type == 33){
                $model = $modelTj2;  //天津时时彩 数据包2 报警设置
            }
            if($type == 4){
                $model = $modelXj; //新疆时时彩 数据包1 报警设置
            }
            if($type == 44){
                $model = $modelXj2; //新疆时时彩 数据包2 报警设置
            }

            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->save()){
                return $this->render('index',[
                    'msg'.$type=>'修改成功',
                    'model'=>$model,
                    'modelOld'=>$modelOld,
                    'modelCq2'=>$modelCq2,
                    'modelTj'=>$modelTj,
                    'modelTj2'=>$modelTj2,
                    'modelXj'=>$modelXj,
                    'modelXj2'=>$modelXj2
                ]);
            }
        };

        return $this->render('index',[
            'model'=>$model,
            'modelOld'=>$modelOld,
            'modelCq2'=>$modelCq2,
            'modelTj'=>$modelTj,
            'modelTj2'=>$modelTj2,
            'modelXj'=>$modelXj,
            'modelXj2'=>$modelXj2,
        ]);
    }

}
