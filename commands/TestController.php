<?php


namespace app\commands;


use app\components\FixedNumber;
use app\components\NewGrab;
use app\components\Test;
use yii\console\Controller;

class TestController extends Controller
{

    /**
     * 江西
     */
    public function actionJx() {
        new Test(1);
    }

    /**
     * 广东
     */
    public function actionGd() {
        new Test(2);
    }

    /**
     * 山东
     */
    public function actionSd() {
        new Test(3);
    }

    /**
     * 上海
     */
    public function actionSh() {
        new Test(4);
    }

    public function actionFixed() {
        new FixedNumber(1);
    }

}