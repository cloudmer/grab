<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contain".
 *
 * @property integer $id
 * @property integer $contents
 * @property integer $number
 * @property integer $valve
 * @property integer $cp_type
 * @property string $start
 * @property string $end
 * @property integer $created
 */
class Contain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contain';
    }

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
            [['contents', 'number', 'cp_type', 'start', 'end', 'created'], 'required'],
            [['contents', 'number', 'valve', 'cp_type', 'created'], 'integer'],
            [['start', 'end'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contents' => '包含内容',
            'number' => '报警期数',
            'valve' => '报警阀门',
            'cp_type' => '彩票类型',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'created' => '数据创建时间',
        ];
    }

    /*
   * 添加
   * */
    public function addReserve(){
        //所有彩种
        if(\Yii::$app->request->post()['Contain']['cp_type'] == 0){
            $cp_type = [1,2,3,4]; //彩票类型
            foreach ($cp_type as $key=>$val){
                $model = new self();
                $model->contents = \Yii::$app->request->post()['Contain']['contents'];
                $model->number   = \Yii::$app->request->post()['Contain']['number'];
                $model->valve    = \Yii::$app->request->post()['Contain']['valve'];
                $model->cp_type  = $val;
                $model->start    = \Yii::$app->request->post()['Contain']['start'];
                $model->end      = \Yii::$app->request->post()['Contain']['end'];
                $model->created  = time();
                $model->save();
            }
            return $model;
        }

        $this->created = time();
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
