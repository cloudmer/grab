<?php

namespace app\controllers;
use app\components\Grab;

class GrabController extends \yii\web\Controller
{
    public function actionIndex()
    {
        new Grab('http://cp.360.cn/dlcjx/?r_a=7zIRFz');
        new Grab('http://cp.360.cn/gd11/?r_a=yiiEJb');
        new Grab('http://cp.360.cn/yun11/?r_a=JfMbIz');
    }

    public function actionJiangxi(){
        new Grab('http://cp.360.cn/dlcjx/?r_a=7zIRFz');
    }

    public function actionGuangdong(){
        new Grab('http://cp.360.cn/gd11/?r_a=yiiEJb');
    }

    public function actionShandong(){
        new Grab('http://cp.360.cn/yun11/?r_a=JfMbIz');
    }

}
