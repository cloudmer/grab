<?php
/**
 * Created by PhpStorm.
 * User: yeyun
 * Date: 17-6-22
 * Time: 下午12:48
 */

namespace app\controllers\admin;


use app\models\Newcodeinterval;
use Yii;

class NewCodeIntervalController extends BaseController
{

    public function actionIndex(){
        $model = Newcodeinterval::find()->all();
        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionForm(){
        $type = Yii::$app->request->get('type');
        $model = new Newcodeinterval();
        return $this->render('_form',[
            'type' => $type,
            'model' => $model
        ]);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $type = Yii::$app->request->post('type');
            $model = new Newcodeinterval();

            $post = Yii::$app->request->post('Newcodeinterval');
            if($post['id']){
                $model = $model->find()->where(['id'=>$post['id']])->one();
                $model->load(Yii::$app->request->post());
                if($model->validate() && $model->save()){
                    //编辑
                    return $this->redirect('/admin/new-code-interval/index');
                }
            }

            //添加全部
            if($type == 0){
                $model->load(Yii::$app->request->post());
                for ($i=1; $i<=4; $i++){
                    $m = new Newcodeinterval();
                    $m->number        = Yii::$app->request->post('Newcodeinterval')['number'];
                    $m->alias         = Yii::$app->request->post('Newcodeinterval')['alias'];
                    $m->contents      = Yii::$app->request->post('Newcodeinterval')['contents'];
                    $m->start         = Yii::$app->request->post('Newcodeinterval')['start'];
                    $m->end           = Yii::$app->request->post('Newcodeinterval')['end'];
                    $m->status        = Yii::$app->request->post('Newcodeinterval')['status'];
                    $m->type          = $i;
                    $m->time          = time();
                    $m->save();
                }
                $this->redirect('/admin/new-code-interval/index');
                return;
            }

            //单个添加
            $model->load(Yii::$app->request->post());
            $model->type = $type;
            $model->time = time();
            if($model->validate() && $model->save()){
                //添加
                $this->redirect('/admin/new-code-interval/index');
            }
        }
    }


    /**
     * 查看数据包
     */
    public function actionSee(){
        $id = Yii::$app->request->get('id');
        $model = Newcodeinterval::find()->where(['id' => $id])->one();
        return $this->render('see',['model'=>$model]);
    }

    /**
     * 删除数据包
     */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        Newcodeinterval::deleteAll(['id'=>$id]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = Newcodeinterval::find()->where(['id'=>$id])->one();

        return $this->render('_form',[
            'model' => $model
        ]);
    }

}