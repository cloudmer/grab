<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reserve".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $cp_type
 * @property integer $number
 * @property integer $qishu
 * @property integer $status
 * @property integer $time
 */
class Reserve extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reserve';
    }

    /* 报警单位 */
    public static $get_type = [
        1 =>'前后中3',
        2 =>'前3',
        3 =>'中3',
        4 =>'后3',
    ];

    /* 彩票类型 */
    public static $get_cp_type = [
        0 => '所有时时彩',
        1 => '重庆时时彩',
        2 => '天津时时彩',
        3 => '新疆时时彩',
        4 => '台湾五分彩',
    ];

    /* 报警状态 */
    public static $get_status = [
        1 => '开启',
        0 => '关闭'
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'cp_type', 'number', 'qishu', 'time'], 'required'],
            [['type', 'cp_type', 'number', 'qishu', 'status', 'time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '报警单位',
            'cp_type' => '彩票类型',
            'number' => '预定号码',
            'qishu' => '报警期数',
            'status' => '报警状态',
            'time' => 'Time',
        ];
    }


    /*
    * 添加
    * */
    public function addReserve(){
        //所有彩种
        if(\Yii::$app->request->post()['Reserve']['cp_type'] == 0){
            $cp_type = [1,2,3,4]; //彩票类型
            foreach ($cp_type as $key=>$val){
                $model = new self();
                $model->type = \Yii::$app->request->post()['Reserve']['type'];
                $model->cp_type = $val;
                $model->number = \Yii::$app->request->post()['Reserve']['number'];
                $model->qishu = \Yii::$app->request->post()['Reserve']['qishu'];
                $model->status = \Yii::$app->request->post()['Reserve']['status'];
                $model->time = time();
                $model->save();
            }
            return $model;
        }

        $this->time = time();
        $this->load(Yii::$app->request->post());
        if($this->validate() && $this->save()){
            return $this;
        }
        return false;
    }

    /*
     * 更新
     * */
    public function updateData($id){
        $model = self::findOne(['id'=>$id]);
        $model->load(Yii::$app->request->post());
        if($model->validate() && $model->save()){
            return $model;
        }
        return false;
    }

    /*
     * 删除
     * */
    public function deleteData($model){
        $model->delete();
        return json_encode(['state'=>true]);
    }

}
