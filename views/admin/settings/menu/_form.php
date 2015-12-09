<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Menus;
use yii\helpers\Html;


$csrf = Yii::$app->request->getCsrfToken();
$script = <<< JS
$(document).ready(function(){
    $(".form-submit").click(function(e){
        e.preventDefault();
        var form = $(".menu-form");
        var yiiResult = form.yiiActiveForm('submitForm');
        if(yiiResult){
            $.post(form.attr('action'),form.serialize(),function(data){
                $('#myModal').modal('hide');
                if(data.state == true){
                    if(data.type=='add'){
                        toastr.success('添加成功');
                        var fatherId;
                        data.data.father_id2 ? fatherId = data.data.father_id2 : (data.data.father_id3 ? fatherId=data.data.father_id3 : null );
                        if(fatherId){
                            $(data.html).insertAfter("#menu-"+fatherId);
                        }else{
                            $("tbody").append(data.html);
                        }
                    }
                    if(data.type=='update'){
                        toastr.success('更新成功');
                        var id = data.data.id;
                        $("#menu-"+id).replaceWith(data.html);
                    }
                }else{
                    toastr.error('添加失败');
                }
            },'json');
        }
    });
});
JS;
$this->registerJs($script);

function name($type){
    $type == 1 ? $name = '顶级菜单栏' : null;
    $type == 2 ? $name = '二级菜单栏' : null;
    $type == 3 ? $name = '三级菜单栏' : null;
    return $name;
}
?>

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= $model->id ? '更新' : '添加' ?> - <?= name($type) ?></h4>
            </div>
            <?php $form = ActiveForm::begin([
                'options'=>['class'=>'form-center menu-form'],
                'action'=>$model->id ? '/admin/settings/update' : '/admin/settings/save',
                'method'=>'post'])?>
            <div class="modal-body">
                <!--添加 顶级菜单 表单-->
                <?php if($type == 1):?>
                    <?= $form->field($model, 'id')->label(false)->hiddenInput()?>
                    <?= $form->field($model, 'name')->textInput(['placeholder'=>'菜单名'])?>
                    <?= $form->field($model, 'controller')->textInput(['placeholder'=>'Controller 控制器'])?>
                    <?= $form->field($model, 'action')->textInput(['placeholder'=>'Action 方法'])?>
                    <?= $form->field($model, 'icon')->label('Icon 图标 <a href="http://wrapbootstrap.com/preview/WB0CX3745" target="_blank" class="btn btn-link"><i class="fa fa-eye"></i> 查看更多</a>')->textInput(['placeholder'=>'图标'])?>
                    <?= $form->field($model, 'sort')->label('排序 - (三位数)')->textInput(['placeholder'=>'排序','type'=>'number','maxlength'=>3])?>
                    <?= $form->field($model,'state')->dropDownList([0=>'隐藏',1=>'显示'])?>
                <?php endif ?>
                <!--添加 二级菜单 表单-->
                <?php if($type == 2):?>
                    <?= $form->field($model, 'id')->label(false)->hiddenInput()?>
                    <?= $form->field($model,'father_id2')->label('请选择父级菜单')->dropDownList(ArrayHelper::map(Menus::menus(1), 'id', 'name'))?>
                    <?= $form->field($model, 'name')->textInput(['placeholder'=>'菜单名'])?>
                    <?= $form->field($model, 'controller')->textInput(['placeholder'=>'Controller 控制器'])?>
                    <?= $form->field($model, 'action')->textInput(['placeholder'=>'Action 方法'])?>
                    <?= $form->field($model, 'sort')->label('排序 - (三位数)')->textInput(['placeholder'=>'排序','type'=>'number','maxlength'=>3])?>
                    <?= $form->field($model,'state')->dropDownList([0=>'隐藏',1=>'显示'])?>
                <?php endif ?>
                <!--添加 三级菜单 表单-->
                <?php if($type == 3):?>
                    <?= $form->field($model, 'id')->label(false)->hiddenInput()?>
                    <?= $form->field($model,'father_id3')->label('请选择父级菜单')->dropDownList(ArrayHelper::map(Menus::menus(2), 'id', 'name'))?>
                    <?= $form->field($model, 'name')->textInput(['placeholder'=>'菜单名'])?>
                    <?= $form->field($model, 'controller')->textInput(['placeholder'=>'Controller 控制器'])?>
                    <?= $form->field($model, 'action')->textInput(['placeholder'=>'Action 方法'])?>
                    <?= $form->field($model, 'sort')->label('排序 - (三位数)')->textInput(['placeholder'=>'排序','type'=>'number','maxlength'=>3])?>
                    <?= $form->field($model,'state')->dropDownList([0=>'隐藏',1=>'显示'])?>
                <?php endif ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <?= Html::submitButton('保存',['class'=>'btn btn-primary form-submit'])?>
            </div>
            <?php ActiveForm::end()?>
        </div>
    </div>
