<?php

namespace app\controllers;
use app\components\Alarm;
use app\components\Grab;
use app\components\GrabBjSsc;
use app\components\GrabCqSsc;
use app\components\GrabOld;
use app\components\GrabTjSsc;
use app\components\GrabTxFfc;
use app\components\GrabXjSsc;
use app\components\NewGrab;
use app\components\Reserve;
use app\models\Cqssc;

class GrabController extends \yii\web\Controller
{
    public function actionIndex()
    {
        new NewGrab(1);
        new NewGrab(2);
        new NewGrab(3);
        new NewGrab(4);
//        new Grab('http://cp.360.cn/dlcjx/?r_a=7zIRFz');
//        new Grab('http://cp.360.cn/gd11/?r_a=yiiEJb');
//        new Grab('http://cp.360.cn/yun11/?r_a=JfMbIz');
//        new GrabOld('http://cp.360.cn/ssccq/?r_a=26ruYj'); // 重庆 - 老时时彩
    }

    public function actionOld(){
        new GrabOld('http://cp.360.cn/ssccq/?r_a=26ruYj'); // 重庆 - 老时时彩
    }


    /**
     * 抓取重庆时时彩数据
     */
    public function actionCqssc(){
        new GrabCqSsc();
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

    /**
     * 抓取北京时时彩数据
     */
    public function actionBjssc(){
        new GrabBjSsc();
    }

    /**
     * 抓取腾讯分分彩数据
     */
    public function actionTxffc(){
        new GrabTxFfc();
    }

}
