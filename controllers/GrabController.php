<?php

namespace app\controllers;
use app\components\Grab;
use app\components\GrabOld;

class GrabController extends \yii\web\Controller
{
    public function actionIndex()
    {
        new Grab('http://cp.360.cn/dlcjx/?r_a=7zIRFz');
        new Grab('http://cp.360.cn/gd11/?r_a=yiiEJb');
        new Grab('http://cp.360.cn/yun11/?r_a=JfMbIz');
    }

    public function actionOld(){
        new GrabOld('http://cp.360.cn/ssccq/?r_a=26ruYj'); // 重庆 - 老时时彩
    }

}
