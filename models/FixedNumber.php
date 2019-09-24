<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fixed_number".
 *
 * @property integer $id
 * @property integer $type
 * @property string $number
 * @property integer $num
 * @property integer $status
 * @property string $create_at
 */
class FixedNumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fixed_number';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'number', 'num', 'status', 'create_at'], 'required'],
            [['type', 'num', 'status'], 'integer'],
            [['create_at'], 'safe'],
            [['number'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '彩票类型',
            'number' => '统计号码',
            'num' => '报警期数',
            'status' => '报警状态',
            'create_at' => '添加时间',
        ];
    }
}
