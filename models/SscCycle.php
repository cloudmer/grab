<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ssc_cycle".
 *
 * @property integer $id
 * @property string $alias
 * @property string $data_txt
 * @property string $start
 * @property string $end
 * @property integer $continuity
 * @property integer $b_number
 * @property integer $status
 * @property integer $cycle
 * @property string $created_at
 */
class SscCycle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ssc_cycle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'start', 'end', 'continuity', 'status', 'cycle', 'created_at', 'data_txt', 'b_number'], 'required'],
            [['data_txt'], 'string'],
            [['continuity', 'status', 'cycle'], 'integer'],
            [['created_at'], 'safe'],
            [['alias'], 'string', 'max' => 100],
            [['start', 'end'], 'string', 'max' => 2]
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
            'data_txt' => '数据包',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'continuity' => 'a包连续期数',
            'b_number' => 'a包连续后几期开b',
            'status' => '报警状态',
            'cycle' => '报警周期数',
            'created_at' => '创建时间',
        ];
    }
}
