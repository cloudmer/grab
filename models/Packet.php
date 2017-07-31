<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "packet".
 *
 * @property integer $id
 * @property string $alias
 * @property string $data_txt
 * @property string $start
 * @property string $end
 * @property integer $regret_number
 * @property integer $forever
 * @property integer $state
 * @property integer $type
 * @property integer $time
 * @property integer $cycle
 * @property integer $cycle_number
 */
class Packet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'packet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'data_txt', 'start', 'end', 'regret_number', 'type', 'time', 'cycle', 'cycle_number'], 'required'],
            [['data_txt'], 'string'],
            [['regret_number', 'forever', 'state', 'type', 'time', 'cycle', 'cycle_number'], 'integer'],
            [['alias'], 'string', 'max' => 100],
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
            'alias' => '别名',
            'data_txt' => '数据包内容',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'regret_number' => '未中奖报警期数',
            'forever' => '是否开启每一期通知',
            'state' => '报警状态',
            'type' => 'Type',
            'time' => 'Time',
            'cycle' => '1周期设定',
            'cycle_number' => '周期报警数',
        ];
    }
}
