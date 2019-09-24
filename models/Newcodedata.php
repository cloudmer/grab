<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "newcodedata".
 *
 * @property integer $id
 * @property string $alias
 * @property integer $number
 * @property string $contents
 * @property string $start
 * @property string $end
 * @property integer $status
 * @property integer $type
 * @property integer $time
 */
class Newcodedata extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newcodedata';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'number', 'contents', 'start', 'end', 'status', 'type', 'time'], 'required'],
            [['number', 'status'], 'integer'],
            [['contents'], 'string'],
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
            'number' => '报警期数',
            'contents' => '数据包',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'status' => '报警状态',
            'type' => '类型',
            'time' => 'time',
        ];
    }
}
