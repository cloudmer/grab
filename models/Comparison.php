<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comparison".
 *
 * @property integer $id
 * @property string $txt
 * @property integer $type
 * @property integer $time
 */
class Comparison extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comparison';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt', 'type', 'time'], 'required'],
            [['txt'], 'string'],
            [['type', 'time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'txt' => 'Txt',
            'type' => 'Type',
            'time' => 'Time',
        ];
    }
}
