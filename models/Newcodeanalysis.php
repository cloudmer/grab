<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "newcodeanalysis".
 *
 * @property integer $id
 * @property integer $lucky
 * @property integer $newcodedata_id
 * @property integer $newcode_id
 */
class Newcodeanalysis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newcodeanalysis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lucky', 'newcodedata_id'], 'required'],
            [['lucky', 'newcodedata_id', 'newcode_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lucky' => 'Lucky',
            'newcodedata_id' => 'Newcodedata ID',
            'newcode_id' => 'Newcode ID',
        ];
    }
}
