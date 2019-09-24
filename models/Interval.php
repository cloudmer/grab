<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "interval".
 *
 * @property integer $id
 * @property string $number
 * @property integer $regret_number
 * @property integer $status
 * @property string $start
 * @property string $end
 * @property integer $time
 */
class Interval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interval';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'regret_number', 'status', 'start', 'end', 'time'], 'required'],
            [['regret_number', 'status', 'time'], 'integer'],
            [['number'], 'string', 'max' => 6],
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
            'number' => '号码',
            'regret_number' => '报警期数',
            'status' => '报警状态',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'time' => 'Time',
        ];
    }
}
