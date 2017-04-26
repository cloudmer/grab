<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-4-26
 * Time: 下午5:27
 */

namespace app\controllers\admin;


use app\models\Interval;
use Yii;

class IntervalController extends BaseController
{

    public function actionIndex(){
        $model = Interval::find()->all();
        return $this->render('index', [ 'model' => $model ]);
    }

    /**
     * 表单视图
     */
    public function actionForm(){
        $model = new Interval();
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post('Interval');
            $model = new Interval();
            if($post['id']){
                $model = $model->find()->where(['id'=>$post['id']])->one();
                if($model->validate() && $model->save()){
                    //编辑
                    $this->redirect('/admin/interval/');
                }
            }

            $model->load(Yii::$app->request->post());
            $model->time = time();
            if($model->validate() && $model->save()){
                //添加
                $this->redirect('/admin/interval/');
            }
        }
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = Interval::find()->where(['id'=>$id])->one();
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    /**
     * 删除数据包
     */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        Interval::deleteAll(['id' => $id]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }

}