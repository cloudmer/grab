<?php
/**
 * Created by PhpStorm.
 * User: Cloud
 * Date: 2018/2/28
 * Time: 15:42
 */

namespace app\controllers\admin;

use app\models\Play22;
use \Yii;

class Play22Controller extends BaseController
{

    public function actionIndex(){
        $model = Play22::find()->all();
        return $this->render('index', [ 'model' => $model ]);
    }

    public function actionForm(){
        return $this->render('_form',[
            'model' => new Play22()
        ]);
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = Play22::find()->where(['id'=>$id])->one();
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    public function actionDelete(){
        Play22::deleteAll([ 'id' => \Yii::$app->request->post('id') ]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post('Play22');
            if($post['id']){
                $model = Play22::findOne(['id'=>$post['id']]);
                $model->load(Yii::$app->request->post());
                if($model->save()){
                    //编辑
                    return $this->redirect('/admin/play22/index');
                }
            }else{
                $model = new Play22();
                $model->load(\Yii::$app->request->post());
                $model->time = time();
                if ($model->save()){
                    // 新增
                    return $this->redirect('/admin/play22/index');
                }
            }
        }
    }

}