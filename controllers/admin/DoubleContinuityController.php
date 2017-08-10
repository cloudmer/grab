<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-8-9
 * Time: 上午9:40
 */

namespace app\controllers\admin;


use app\models\DoubleContinuity;
use Yii;

class DoubleContinuityController extends BaseController
{

    public function actionIndex(){
        $model = DoubleContinuity::find()->all();
        return $this->render('index',['model'=>$model]);
    }

    public function actionForm(){
        $model = new DoubleContinuity();
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post('DoubleContinuity');
            $model = new DoubleContinuity();
            if($post['id']){
                $model = $model->find()->where(['id'=>$post['id']])->one();
                if($model->validate() && $model->save()){
                    //编辑
                    $this->redirect('/admin/double-continuity/');
                }
            }

            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->save()){
                //添加
                $this->redirect('/admin/double-continuity/');
            }
        }
    }

    public function actionSee(){
        $id = Yii::$app->request->get('id');
        $model = DoubleContinuity::find()->where(['id' => $id])->one();
        return $this->render('see',['model'=>$model]);
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = DoubleContinuity::find()->where(['id'=>$id])->one();

        return $this->render('_form',[
            'model' => $model
        ]);
    }

    /**
     * 删除数据包
     */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        DoubleContinuity::deleteAll(['id'=>$id]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }

}