<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "double".
 *
 * @property integer $id
 * @property string $alias
 * @property string $package_a
 * @property string $package_b
 * @property integer $status
 * @property string $start
 * @property string $end
 * @property integer $number
 */
class Double extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'double';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'package_a', 'package_b', 'start', 'end', 'number'], 'required'],
            [['package_a', 'package_b'], 'string'],
            [['status', 'number'], 'integer'],
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
            'package_a' => '数据包 A',
            'package_b' => '数据包 B',
            'status' => '报警状态',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'number' => '报警期数',
        ];
    }
}
