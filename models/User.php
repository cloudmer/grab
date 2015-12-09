<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property string $nick_name
 * @property string $head_portrait
 * @property string $email
 * @property string $phone
 * @property integer $sex
 * @property integer $age
 * @property integer $role
 * @property integer $state
 * @property integer $login_time
 * @property string $login_ip
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    const ROLE_ADMIN=1;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['id', 'role', 'state', 'login_time', 'sex', 'age'], 'integer'],
            [['username', 'password', 'nick_name', 'email', 'login_ip'], 'string', 'max' => 100],
            [['auth_key', 'access_token', 'head_portrait'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'nick_name' => 'Nick Name',
            'head_portrait' => 'Head Portrait',
            'email' => 'Email',
            'phone' => 'Phone',
            'sex' => 'Sex',
            'age' => 'Age',
            'role' => 'Role',
            'state' => 'State',
            'login_time' => 'Login Time',
            'login_ip' => 'Login Ip',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
//        return $this->id;
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    /*
     * 更新用户登陆时间 更新用户登陆Ip
     * */
    public static function updateLoginInfo($id){
        self::updateAll(['login_time'=>time(),'login_ip'=>$id],'id='.Yii::$app->user->identity->getId());
    }

    /*
     * 获取用户信息
     * */
    public function userInfo($field){
        $model = self::findOne(['id'=>Yii::$app->request->post('id')]);
        echo $model->$field;
    }

}
