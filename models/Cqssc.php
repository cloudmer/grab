<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cqssc".
 *
 * @property integer $id
 * @property string $qishu
 * @property integer $one
 * @property integer $two
 * @property integer $three
 * @property integer $four
 * @property integer $five
 * @property string $code
 * @property string $front_three_type
 * @property string $center_three_type
 * @property string $after_three_type
 * @property string $kj_time
 * @property integer $time
 *
 * @property AnalysisCqssc[] $analysisCqsscs
 * @property AnalysisCqssc[] $analysisCqsscsData1
 * @property AnalysisCqssc[] $analysisCqsscsData2
 */
class Cqssc extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cqssc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qishu', 'one', 'two', 'three', 'four', 'five', 'code', 'front_three_type', 'center_three_type', 'after_three_type', 'time'], 'required'],
            [['one', 'two', 'three', 'four', 'five', 'time'], 'integer'],
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
    public function getAnalysisCqsscs()
    {
        return $this->hasOne(AnalysisCqssc::className(), ['cqssc_id' => 'id']);
    }

    /**
     * 重庆时时彩数据包1解析数据
     * @return \yii\db\ActiveQuery
     */
    public function getAnalysisCqsscsData1(){
        return $this->hasOne(AnalysisCqssc::className(), ['cqssc_id' => 'id'])->where(['type'=>1]);
    }

    /**
     * 重庆时时彩数据包2解析数据
     * @return \yii\db\ActiveQuery
     */
    public function getAnalysisCqsscsData2(){
        return $this->hasOne(AnalysisCqssc::className(), ['cqssc_id' => 'id'])->where(['type'=>2]);
    }

}
