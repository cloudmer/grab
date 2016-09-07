<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tjssc".
 *
 * @property integer $id
 * @property integer $qishu
 * @property integer $one
 * @property integer $two
 * @property integer $three
 * @property integer $four
 * @property integer $five
 * @property string $code
 * @property string $kj_time
 * @property integer $time
 *
 * @property AnalysisTjssc[] $analysisTjsscs
 */
class Tjssc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tjssc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qishu', 'one', 'two', 'three', 'four', 'five', 'code', 'time'], 'required'],
            [['qishu', 'one', 'two', 'three', 'four', 'five', 'time'], 'integer'],
            [['code'], 'string', 'max' => 20],
            [['kj_time'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'qishu' => 'Qishu',
            'one' => 'One',
            'two' => 'Two',
            'three' => 'Three',
            'four' => 'Four',
            'five' => 'Five',
            'code' => 'Code',
            'kj_time' => 'Kj Time',
            'time' => 'Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalysisTjsscs()
    {
        return $this->hasOne(AnalysisTjssc::className(), ['tjssc_id' => 'id']);
    }
}
