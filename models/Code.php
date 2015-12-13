<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "code".
 *
 * @property integer $id
 * @property integer $qishu
 * @property integer $one
 * @property integer $two
 * @property integer $three
 * @property integer $four
 * @property integer $five
 * @property string $size
 * @property string $jiou
 * @property integer $type
 * @property integer $time
 *
 * @property Prize[] $prizes
 */
class Code extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'code';
    }

    public static $urlArr = array(
        '江西页面'=>'http://cp.360.cn/dlcjx/?r_a=7zIRFz',
        '广东页面'=>'http://cp.360.cn/gd11/?r_a=yiiEJb',
        '山东页面'=>'http://cp.360.cn/yun11/?r_a=JfMbIz'
    );

    public static $codeType = array(
        'http://cp.360.cn/dlcjx/?r_a=7zIRFz'=>'1',
        'http://cp.360.cn/gd11/?r_a=yiiEJb'=>'2',
        'http://cp.360.cn/yun11/?r_a=JfMbIz'=>'3',
    );

    public static $shishicaiUrl = array(
        '江西页面'=>'http://www.shishicai.cn/jx11x5/touzhu/',
        '广东页面'=>'http://www.shishicai.cn/gd11x5/touzhu/',
        '山东页面'=>'http://www.shishicai.cn/sd11x5/touzhu/'
    );

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qishu', 'one', 'two', 'three', 'four', 'five', 'size', 'jiou', 'type', 'time'], 'required'],
            [['qishu', 'one', 'two', 'three', 'four', 'five', 'type', 'time'], 'integer'],
            [['size', 'jiou'], 'string', 'max' => 45]
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
            'size' => 'Size',
            'jiou' => 'Jiou',
            'type' => 'Type',
            'time' => 'Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrizes()
    {
        return $this->hasMany(Prize::className(), ['code_id' => 'id']);
    }

    public function getAnalysis(){
        return $this->hasMany(Analysis::className(), ['codi_id' => 'id']);
    }

}
