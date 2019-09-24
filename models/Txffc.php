<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "txffc".
 *
 * @property integer $id
 * @property string $qishu
 * @property integer $one
 * @property integer $two
 * @property integer $three
 * @property integer $four
 * @property integer $five
 * @property string $code
 * @property integer $front_three_type
 * @property integer $center_three_type
 * @property integer $after_three_type
 * @property string $kj_time
 * @property integer $time
 *
 * @property AnalysisTxffc[] $analysisTxffcs
 */
class Txffc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'txffc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qishu', 'one', 'two', 'three', 'four', 'five', 'code', 'front_three_type', 'center_three_type', 'after_three_type', 'time'], 'required'],
            [['one', 'two', 'three', 'four', 'five', 'front_three_type', 'center_three_type', 'after_three_type', 'time'], 'integer'],
            [['qishu', 'code'], 'string', 'max' => 20],
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
            'front_three_type' => 'Front Three Type',
            'center_three_type' => 'Center Three Type',
            'after_three_type' => 'After Three Type',
            'kj_time' => 'Kj Time',
            'time' => 'Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalysisTxffcs()
    {
        return $this->hasMany(AnalysisTxffc::className(), ['txffc_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalysis($data_txt_id)
    {
        return $this->hasOne(AnalysisTxffc::className(), ['txffc_id' => 'id'])->where(['type'=>$data_txt_id]);
    }

}
