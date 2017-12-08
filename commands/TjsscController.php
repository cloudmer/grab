<?php
/**
 * Created by PhpStorm.
 * User: Cloud
 * Date: 2017/12/8
 * Time: 17:10
 */

namespace app\commands;


use app\components\GrabTjSsc;
use yii\console\Controller;

class TjsscController extends Controller
{

    public function actionIndex(){
        new GrabTjSsc();
    }

}