<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cqdata".
 *
 * @property integer $id
 * @property string $alias
 * @property string $data_txt
 * @property string $start
 * @property string $end
 * @property integer $regret_number
 * @property integer $forever
 * @property integer $state
 * @property integer $time
 */
class Cqdata extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cqdata';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'data_txt', 'start', 'end', 'regret_number', 'time'], 'required'],
            [['data_txt'], 'string'],
            [['regret_number', 'forever', 'state', 'time'], 'integer'],
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
            'time' => 'Time',
        ];
    }
}
