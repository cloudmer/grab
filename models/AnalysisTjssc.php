<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "analysisTjssc".
 *
 * @property integer $id
 * @property integer $tjssc_id
 * @property string $front_three_lucky_txt
 * @property string $front_three_regret_txt
 * @property string $center_three_lucky_txt
 * @property string $center_three_regret_txt
 * @property string $after_three_lucky_txt
 * @property string $after_three_regret_txt
 * @property string $data_txt
 * @property integer $time
 *
 * @property Tjssc $tjssc
 */
class AnalysisTjssc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'analysisTjssc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tjssc_id', 'time'], 'required'],
            [['tjssc_id', 'time'], 'integer'],
            [['front_three_lucky_txt', 'front_three_regret_txt', 'center_three_lucky_txt', 'center_three_regret_txt', 'after_three_lucky_txt', 'after_three_regret_txt', 'data_txt'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tjssc_id' => 'Tjssc ID',
            'front_three_lucky_txt' => 'Front Three Lucky Txt',
            'front_three_regret_txt' => 'Front Three Regret Txt',
            'center_three_lucky_txt' => 'Center Three Lucky Txt',
            'center_three_regret_txt' => 'Center Three Regret Txt',
            'after_three_lucky_txt' => 'After Three Lucky Txt',
            'after_three_regret_txt' => 'After Three Regret Txt',
            'data_txt' => 'Data Txt',
            'time' => 'Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTjssc()
    {
        return $this->hasOne(Tjssc::className(), ['id' => 'tjssc_id']);
    }
}
