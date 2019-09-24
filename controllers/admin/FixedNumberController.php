<?php


namespace app\controllers\admin;


use app\models\FixedNumber;
use app\models\Newcodedata;
use Yii;

/**
 * 固定号码报警设置
 *
 * Class FixedNumberController
 * @package app\controllers\admin
 */
class FixedNumberController extends BaseController
{

    public function actionIndex() {
        $model = FixedNumber::find()->where([ 'status' => 1 ])->all();
        return $this->render('index', [
            'model' => $model
        ]);
    }


    public function actionForm() {
        $type = Yii::$app->request->get('type');
        $model = new FixedNumber();
        return $this->render('_form',[
            'type' => $type,
            'model' => $model
        ]);
    }

    public function actionSubmit(){
        if(Yii::$app->request->post()){
            $type = Yii::$app->request->post('type');
            $model = new FixedNumber();

            $post = Yii::$app->request->post('FixedNumber');
            if($post['id']){
                $model = $model->find()->where(['id'=>$post['id']])->one();
                $model->load(Yii::$app->request->post());
                if($model->validate() && $model->save()){
                    //编辑
                    return $this->redirect('/admin/fixed-number/index');
                }
            }

            //添加全部
            if($type == 0){
                $model->load(Yii::$app->request->post());
                for ($i=1; $i<=4; $i++){
                    $m = new FixedNumber();
                    $m->number        = Yii::$app->request->post('FixedNumber')['number'];
                    $m->num           = Yii::$app->request->post('FixedNumber')['num'];
                    $m->status        = Yii::$app->request->post('FixedNumber')['status'];
                    $m->type          = $i;
                    $m->create_at     = date('Y-m-d H:i:s');
                    $m->save();
                }
                $this->redirect('/admin/fixed-number/index');
                return;
            }

            //单个添加
            $model->load(Yii::$app->request->post());
            $model->type = $type;
            $model->time = time();
            if($model->validate() && $model->save()){
                //添加
                $this->redirect('/admin/fixed-number/index');
            }
        }
    }

    /**
     * 删除数据包
     */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        FixedNumber::deleteAll(['id'=>$id]);
        return json_encode(['state'=>true,'msg'=>'删除成功']);
    }

    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $model = FixedNumber::find()->where(['id'=>$id])->one();

        return $this->render('_form',[
            'model' => $model
        ]);
    }

}