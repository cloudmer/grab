<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "play1".
 *
 * @property integer $id
 * @property string $alias
 * @property string $package_a
 * @property string $package_b
 * @property integer $status
 * @property string $start
 * @property string $end
 * @property integer $continuity_number
 * @property integer $number
 */
class Play1 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'play1';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'package_a', 'package_b', 'start', 'end', 'continuity_number', 'number'], 'required'],
            [['package_a', 'package_b'], 'string'],
            [['status', 'number', 'continuity_number'], 'integer'],
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
            'alias' => '数据包别名',
            'package_a' => '数据包a',
            'package_b' => '数据包b',
            'status' => '报警状态',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'continuity_number' => '连续几b',
            'number' => '报警期数',
        ];
    }
}
