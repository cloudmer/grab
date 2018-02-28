<?php
/**
 * Created by PhpStorm.
 * User: Cloud
 * Date: 2018/2/24
 * Time: 14:32
 */

namespace app\controllers\admin;


use app\models\Play2;
use \Yii;

class Play2Controller extends BaseController
{

    public function actionIndex(){
        $cycle = Yii::$app->request->get('cycle');
        $model = Play2::findAll([ 'cycle' => $cycle ]);
        return $this->render('index', [ 'model' => $model ]);
    }

    public function actionForm(){
        return $this->render('_form',[
            'model' => new Play2()
        ]);
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = Play2::find()->where(['id'=>$id])->one();
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    public function actionDelete(){
        Play2::deleteAll([ 'id' => \Yii::$app->request->post('id') ]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post('Play2');
            if($post['id']){
                $model = Play2::findOne(['id'=>$post['id']]);
                $model->load(Yii::$app->request->post());
                if($model->save()){
                    //编辑
                    return $this->redirect('/admin/play2/index');
                }
            }else{
                $model = new Play2();
                $model->load(\Yii::$app->request->post());
                $model->time = time();
                if ($model->save()){
                    // 新增
                    return $this->redirect('/admin/play2/index');
                }
            }
        }
    }

}