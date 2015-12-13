<?php

namespace app\controllers;

use app\models\Code;

class HomeController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $type = 1;
        if(\Yii::$app->request->get('type')){
            $type = \Yii::$app->request->get('type');
        }
        $model = Code::find()->where(['type'=>$type])->orderBy('time DESC')->all();
        return $this->render('index',['model'=>$model]);
    }

}
