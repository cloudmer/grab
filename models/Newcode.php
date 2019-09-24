<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "newcode".
 *
 * @property integer $id
 * @property string $qihao
 * @property string $one
 * @property string $two
 * @property string $three
 * @property string $four
 * @property string $five
 * @property integer $type
 * @property integer $time
 */
class Newcode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newcode';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qihao', 'one', 'two', 'three', 'four', 'five', 'type', 'time'], 'required'],
            [['type'], 'integer'],
            [['qihao'], 'string', 'max' => 20],
            [['one', 'two', 'three', 'four', 'five'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'qihao' => 'Qihao',
            'one' => 'One',
            'two' => 'Two',
            'three' => 'Three',
            'four' => 'Four',
            'five' => 'Five',
            'type' => 'Type',
            'time' => 'Time',
        ];
    }

    /**
     * @param $newcodedata_id
     * @return \yii\db\ActiveQuery
     */
    public function getAnalysis($newcodedata_id)
    {
        return $this->hasOne(Newcodeanalysis::className(), ['newcode_id' => 'id'])->where(['newcodedata_id'=>$newcodedata_id]);
        //return $this->hasOne(Newcodeanalysis::className(), ['newcode_id' => 'id']);
    }

}
