<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alarm_record".
 *
 * @property integer $id
 * @property integer $alarm_id
 * @property integer $number
 * @property integer $cycle
 * @property string $title
 * @property integer $cp_type
 * @property integer $position
 * @property string $created_at
 */
class AlarmRecord extends \yii\db\ActiveRecord
{

    const cqType = 1;
    const xjType = 3;

    const q3 = 1;
    const z3 = 2;
    const h3 = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alarm_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alarm_id', 'number', 'cycle', 'title', 'cp_type', 'position', 'created_at'], 'required'],
            [['alarm_id', 'number', 'cycle', 'cp_type', 'position'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alarm_id' => 'Alarm ID',
            'number' => 'Number',
            'cycle' => 'Cycle',
            'title' => 'Title',
            'cp_type' => 'Cp Type',
            'position' => 'Position',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 重庆分组周期
     */
    public function cqGrupCqCycle(){
        $ary = self::find()->select('cycle')->where([ 'cp_type' => self::cqType ])->groupBy('cycle')->asArray()->all();

        $ids = [];
        foreach ($ary as $key=>$val) {
            $ids[] = $val['cycle'];
        }
        return $ids;
    }

    /**
     * 新疆分组周期
     */
    public function xjGrupCqCycle(){
        $ary = self::find()->select('cycle')->where([ 'cp_type' => self::xjType ])->groupBy('cycle')->asArray()->all();

        $ids = [];
        foreach ($ary as $key=>$val) {
            $ids[] = $val['cycle'];
        }
        return $ids;
    }
}
