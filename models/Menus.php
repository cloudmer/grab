<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menus".
 *
 * @property integer $id
 * @property integer $father_id2
 * @property integer $father_id3
 * @property string $name
 * @property string $icon
 * @property string $controller
 * @property string $action
 * @property integer $sort
 * @property integer $state
 * @property integer $add_time
 */
class Menus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['father_id2', 'father_id3', 'sort', 'state', 'add_time'], 'integer'],
            [['name'], 'required'],
            [['name', 'icon', 'controller', 'action'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'father_id2' => '二级菜单',
            'father_id3' => '三级菜单',
            'name' => '菜单名',
            'url' => 'Url',
            'icon' => 'Icon 图标',
            'controller' => '控制器',
            'action' => '方法',
            'sort' => '排序',
            'state' => '显示/隐藏',
            'add_time' => 'Add Time',
        ];
    }

    /*
     * Menus
     * */
    public static function menus($type){
        $where = null;
        $type==1 ? $where=['father_id2'=>null,'father_id3'=>null] : null;
        $type==2 ? $where=['not',['father_id2'=>null]] : null;
        $type==3 ? $where=['not',['father_id3'=>null]] : null;

        return self::find()->where($where)->orderBy('sort DESC')->all();
    }

    /*
     * Menu 获取子菜单栏
     * */
    public static function menuSub($type,$id=null){
        $where = null;
        $type==1 ? $where=['father_id2'=>null,'father_id3'=>null] : null;
        $type==2 ? $where=['father_id2'=>$id] : null;
        $type==3 ? $where=['father_id3'=>$id] : null;

//        return self::findAll($where);
        return self::find()->where($where)->orderBy('sort DESC')->all();
    }

    /*
     * 添加菜单栏目
     * */
    public function addMenu(){
        $this->add_time = time();
        $this->load(Yii::$app->request->post());
        if($this->validate() && $this->save()){
            return $this;
        }
        return false;
    }

    /*
     * 更新菜单栏目
     * */
    public function updateData($id){
        $model = self::findOne(['id'=>$id]);
        $model->load(Yii::$app->request->post());
        if($model->validate() && $model->save()){
            return $model;
        }
        return false;
    }

    /*
     * 删除栏目
     * */
    public function deleteData($model){
        $model->delete();
        return json_encode(['state'=>true]);
    }

    /*
     * 显示 隐藏 菜单栏目
     * */
    public function is_show($model){
        $model->state = $model->state ? 0 : 1;
        $model->save();
        return json_encode([
            'state'=>true,
        ]);
    }

}
