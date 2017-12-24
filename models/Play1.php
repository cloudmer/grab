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
            'alias' => 'Alias',
            'package_a' => 'Package A',
            'package_b' => 'Package B',
            'status' => 'Status',
            'start' => 'Start',
            'end' => 'End',
            'number' => 'Number',
        ];
    }
}
