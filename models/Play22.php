<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "play22".
 *
 * @property integer $id
 * @property integer $number
 * @property string $start
 * @property string $end
 * @property integer $status
 * @property integer $time
 */
class Play22 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'play22';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'start', 'end', 'status'], 'required'],
            [['number', 'status', 'time'], 'integer'],
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
            'number' => '报警期数',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'status' => '报警状态',
            'time' => 'Time',
        ];
    }
}
