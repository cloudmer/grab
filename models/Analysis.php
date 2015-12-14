<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "analysis".
 *
 * @property integer $id
 * @property integer $codi_id
 * @property string $lucky_txt
 * @property string $regret_txt
 * @property string $data_txt
 * @property integer $time
 *
 * @property Code $codi
 */
class Analysis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'analysis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codi_id', 'data_txt', 'time'], 'required'],
            [['codi_id', 'time'], 'integer'],
            [['lucky_txt', 'regret_txt', 'data_txt'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codi_id' => 'Codi ID',
            'lucky_txt' => 'Lucky Txt',
            'regret_txt' => 'Regret Txt',
            'data_txt' => 'Data Txt',
            'time' => 'Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodi()
    {
        return $this->hasOne(Code::className(), ['id' => 'codi_id']);
    }
}
