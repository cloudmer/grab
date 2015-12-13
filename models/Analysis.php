<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "analysis".
 *
 * @property integer $id
 * @property integer $codi_id
 * @property string $code
 * @property string $data_txt
 * @property integer $state
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
            [['codi_id', 'code', 'data_txt', 'state', 'time'], 'required'],
            [['codi_id', 'state', 'time'], 'integer'],
            [['code', 'data_txt'], 'string']
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
            'code' => 'Code',
            'data_txt' => 'Data Txt',
            'state' => 'State',
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
