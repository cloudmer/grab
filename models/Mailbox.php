<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mailbox".
 *
 * @property integer $id
 * @property integer $type
 * @property string $email_address
 * @property string $password
 * @property integer $time
 */
class Mailbox extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mailbox';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'email_address', 'time'], 'required'],
            [['type', 'time'], 'integer'],
            [['email_address', 'password'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'email_address' => 'Email Address',
            'password' => 'Password',
            'time' => 'Time',
        ];
    }
}
