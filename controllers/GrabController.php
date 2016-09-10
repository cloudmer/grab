<?php

namespace app\controllers;
use app\components\Danger;
use app\components\Grab;
use app\components\GrabCqSsc;
use app\components\GrabOld;
use app\components\GrabTjSsc;
use app\components\GrabXjSsc;

class GrabController extends \yii\web\Controller
{
    public function actionIndex()
    {
        new Grab('http://cp.360.cn/dlcjx/?r_a=7zIRFz');
        new Grab('http://cp.360.cn/gd11/?r_a=yiiEJb');
        new Grab('http://cp.360.cn/yun11/?r_a=JfMbIz');
        new GrabOld('http://cp.360.cn/ssccq/?r_a=26ruYj'); // 重庆 - 老时时彩
    }

    public function actionOld(){
        new GrabOld('http://cp.360.cn/ssccq/?r_a=26ruYj'); // 重庆 - 老时时彩
    }


    /**
     * 抓取重庆时时彩数据
     */
    public function actionCqssc(){
        new GrabCqSsc();
//        new Danger('cq');
    }

    /**
     * 抓取天津时时彩数据
     */
    public function actionTjssc(){
        new GrabTjSsc();
    }

    /**
     * 抓取新疆时时彩数据
     */
    public function actionXjssc(){
        new GrabXjSsc();
    }
}
