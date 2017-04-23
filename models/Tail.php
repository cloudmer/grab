<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tail".
 *
 * @property integer $id
 * @property string $zero
 * @property string $one
 * @property string $two
 * @property string $three
 * @property string $four
 * @property string $five
 * @property string $six
 * @property string $seven
 * @property string $eight
 * @property string $nine
 * @property integer $continuity
 * @property integer $discontinuous
 * @property string $start
 * @property string $end
 * @property integer $status
 * @property integer $time
 */
class Tail extends \yii\db\ActiveRecord
{

    public $time;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'continuity', 'discontinuous', 'start', 'end', 'status', 'time'], 'required'],
            [['continuity', 'discontinuous', 'status', 'time'], 'integer'],
            [['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'start', 'end'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zero' => '0开奖号码',
            'one' => '1开奖号码',
            'two' => '2开奖号码',
            'three' => '3开奖号码',
            'four' => '4开奖号码',
            'five' => '5开奖号码',
            'six' => '6开奖号码',
            'seven' => '7开奖号码',
            'eight' => '8开奖号码',
            'nine' => '9开奖号码',
            'continuity' => '连续报警期数',
            'discontinuous' => '未连续报警期数',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'status' => '报警状态',
            'time' => 'Time',
        ];
    }
}
