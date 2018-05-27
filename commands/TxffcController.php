<?php
/**
 * Created by PhpStorm.
 * User: wang.haibo
 * Date: 2018/5/27
 * Time: 11:06
 */

namespace app\commands;


use app\components\GrabTxFfc;
use yii\console\Controller;

class TxffcController extends Controller
{

    /**
     * 新疆时时彩抓取
     */
    public function actionIndex(){
        new GrabTxFfc();
    }

}