<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-6-2
 * Time: 下午5:51
 */

namespace app\controllers\admin;


use app\models\Double;
use Yii;

class DoubleController extends BaseController
{

    public function actionIndex(){
        $model = Double::find()->all();
        return $this->render('index',['model'=>$model]);
    }

    public function actionForm(){
        $model = new Double();
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post('Double');
            $model = new Double();
            if($post['id']){
                $model = $model->find()->where(['id'=>$post['id']])->one();
                if($model->validate() && $model->save()){
                    //编辑
                    $this->redirect('/admin/double/');
                }
            }

            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->save()){
                //添加
                $this->redirect('/admin/double/');
            }
        }
    }

    public function actionSee(){
        $id = Yii::$app->request->get('id');
        $model = Double::find()->where(['id' => $id])->one();
        return $this->render('see',['model'=>$model]);
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = Double::find()->where(['id'=>$id])->one();

        return $this->render('_form',[
            'model' => $model
        ]);
    }

    /**
     * 删除数据包
     */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        Double::deleteAll(['id'=>$id]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }

}