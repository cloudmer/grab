<?php

namespace app\controllers;
use app\components\Grab;

class GrabController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionJiangxi(){
        new Grab('http://www.shishicai.cn/jx11x5/touzhu');
    }

    public function actionGuangdong(){

    }

    public function actionShandong(){

    }

}
