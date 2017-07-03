<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-7-3
 * Time: 下午3:32
 */

namespace app\controllers\admin;


use app\models\Alarm;
use Yii;

class AlarmController extends BaseController
{

    public function actionIndex(){
        $model = Alarm::find()->all();
        return $this->render('index', [ 'model' => $model ]);
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = Alarm::find()->where(['id'=>$id])->one();
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post('Alarm');
            if($post['id']){
                $model = Alarm::findOne(['id'=>$post['id']]);
                $model->load(Yii::$app->request->post());
                if($model->save()){
                    //编辑
                    $this->redirect('/admin/alarm/index');
                }
            }
        }
    }
}