<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "analysisold".
 *
 * @property integer $id
 * @property integer $code_id
 * @property string $front_three_lucky_txt
 * @property string $front_three_regret_txt
 * @property string $center_three_lucky_txt
 * @property string $center_three_regret_txt
 * @property string $after_three_lucky_txt
 * @property string $after_three_regret_txt
 * @property string $data_txt
 * @property integer $time
 *
 * @property Codeold $code
 */
class Analysisold extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'analysisold';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code_id', 'data_txt', 'time'], 'required'],
            [['code_id', 'time'], 'integer'],
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
            'code_id' => 'Code ID',
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
    public function getCode()
    {
        return $this->hasOne(Codeold::className(), ['id' => 'code_id']);
    }
}
