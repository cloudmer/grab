<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-4-23
 * Time: 下午4:29
 */

namespace app\controllers\admin;


use app\models\Tail;
use Yii;

class TailController extends BaseController
{

    public function actionIndex(){
        $model = Tail::find()->all();
        return $this->render('index', [ 'model' => $model ]);
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = Tail::find()->where(['id'=>$id])->one();
        return $this->render('_form',[
            'model' => $model
        ]);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post('Tail');
            if($post['id']){
                $model = Tail::findOne(['id'=>$post['id']]);
                $model->load(Yii::$app->request->post());
                if($model->save()){
                    //编辑
                    $this->redirect('/admin/tail/index');
                }
            }
        }
    }

}