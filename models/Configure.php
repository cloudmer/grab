<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configure".
 *
 * @property integer $id
 * @property string $start_time
 * @property string $end_time
 * @property integer $regret_number
 * @property integer $forever
 * @property integer $state
 */
class Configure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'configure';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regret_number', 'forever', 'state'], 'integer'],
            [['start_time', 'end_time'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'regret_number' => 'Regret Number',
            'forever' => 'Forever',
            'state' => 'State',
        ];
    }
}
