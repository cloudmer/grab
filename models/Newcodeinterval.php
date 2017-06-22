<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "newcodeinterval".
 *
 * @property integer $id
 * @property string $contents
 * @property string $alias
 * @property string $start
 * @property string $end
 * @property integer $time
 * @property integer $status
 * @property integer $type
 * @property integer $number
 */
class Newcodeinterval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newcodeinterval';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contents', 'alias', 'start', 'end', 'time', 'status', 'type', 'number'], 'required'],
            [['contents'], 'string'],
            [['time', 'status', 'type', 'number'], 'integer'],
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
            'contents' => '数据包',
            'alias' => '别名',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'time' => 'Time',
            'status' => '报警状态',
            'type' => '类型',
            'number' => '期数',
        ];
    }
}
