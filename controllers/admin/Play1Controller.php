<?php
/**
 * Created by PhpStorm.
 * User: Cloud
 * Date: 2017/12/24
 * Time: 13:08
 */

namespace app\controllers\admin;

use Yii;
use app\models\Play1;

/**
 * a出现几期的b 的玩法
 *
 * Class Play1Controller
 * @package app\controllers\admin
 */
class Play1Controller extends BaseController
{

    public function actionIndex(){
        $model = Play1::find()->all();
        return $this->render('index',['model'=>$model]);
    }

    public function actionForm(){
        $model = new Play1();
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post('Play1');
            $model = new Play1();
            if($post['id']){
                $model = $model->find()->where(['id'=>$post['id']])->one();
                if($model->validate() && $model->save()){
                    //编辑
                    $this->redirect('/admin/play1/');
                }
            }

            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->save()){
                //添加
                $this->redirect('/admin/play1/');
            }
        }
    }

    public function actionSee(){
        $id = Yii::$app->request->get('id');
        $model = Play1::find()->where(['id' => $id])->one();
        return $this->render('see',['model'=>$model]);
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = Play1::find()->where(['id'=>$id])->one();

        return $this->render('_form',[
            'model' => $model
        ]);
    }

    /**
     * 删除数据包
     */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        Play1::deleteAll(['id'=>$id]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }

}