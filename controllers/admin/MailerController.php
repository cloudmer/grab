<?php

namespace app\controllers\admin;
use app\models\Comparison;
use app\models\Configure;
use app\models\Mailbox;
use Yii;

class MailerController extends BaseController
{
    public function actionIndex()
    {
        $recipients = Mailbox::findAll(['type'=>1]);
        $sender = Mailbox::findAll(['type'=>0]);
        return $this->render('index',['recipients'=>$recipients,'sender'=>$sender]);
    }


    public function actionFrom(){
        $type = \Yii::$app->request->post('type');
        $id = \Yii::$app->request->post('id');
        $model = new Mailbox();
        $model->type = $type;
        if($id){
            $model = Mailbox::findOne(['id'=>$id]);
        }
        return $this->renderAjax('/admin/mailer/_form.php',['type'=>$type,'model'=>$model]);
    }

    public function actionSave(){
        if(Yii::$app->request->isAjax){
            $model = new Mailbox();
            $model->load(Yii::$app->request->post());
            $model->time = time();
            if($model->validate() && $model->save()){
                return json_encode([
                    'state'=>true,
                    'type'=>'add',
                    'class'=> $model->type == 1 ? 'addressee' : 'sender',
                    'html'=>$this->renderAjax('/admin/mailer/_list',['type'=>'ajax','data'=>$model])
                ]);
            }
            return json_encode(['state'=>false]);
        }
    }

    public function actionUpdate(){
        if(Yii::$app->request->isAjax){
            $mailbox = \Yii::$app->request->post('Mailbox');
            $mailboxModel = Mailbox::findOne(['id'=>$mailbox['id']]);
            $mailboxModel->load(Yii::$app->request->post());
            if($mailboxModel->validate() && $mailboxModel->save()){
                return json_encode([
                    'state'=>true,
                    'type'=>'update',
                    'class'=> $mailboxModel->type == 1 ? 'addressee' : 'sender',
                    'data'=>$mailboxModel->attributes,
                    'html'=>$this->renderAjax('/admin/mailer/_list',['type'=>'ajax','data'=>$mailboxModel])
                ]);
            }
            return json_encode(['state'=>false]);
        }
    }

    public function actionDelete(){
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            Mailbox::deleteAll(['id'=>$id]);
            return json_encode(['state'=>true]);
        }
    }

}
