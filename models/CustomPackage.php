<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "custom_package".
 *
 * @property integer $id
 * @property string $alias
 * @property string $package_a
 * @property string $package_b
 * @property integer $status
 * @property string $start
 * @property string $end
 * @property integer $continuity
 * @property integer $number
 */
class CustomPackage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'custom_package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'package_a', 'package_b', 'status', 'start', 'end', 'continuity', 'number'], 'required'],
            [['package_a', 'package_b'], 'string'],
            [['status', 'continuity', 'number'], 'integer'],
            [['alias'], 'string', 'max' => 250],
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
            'package_a' => 'A包',
            'package_b' => 'B包',
            'status' => '报警状态',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'continuity' => 'A包连续多少期 为1',
            'number' => '报警期数',
        ];
    }
}
