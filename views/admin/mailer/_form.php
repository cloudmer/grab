<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;



$csrf = Yii::$app->request->getCsrfToken();
$script = <<< JS
$(document).ready(function(){
    $(".form-submit").click(function(e){
        e.preventDefault();
        var form = $(".data-form");
        var yiiResult = form.yiiActiveForm('submitForm');
        if(yiiResult){
            $.post(form.attr('action'),form.serialize(),function(data){
                $('#myModal').modal('hide');
                if(data.state == true){
                    if(data.type=='add'){
                        toastr.success('添加成功');
                        $("."+data.class).append(data.html);
                        return false;
                    }
                    if(data.type=='update'){
                        toastr.success('更新成功');
                        var id = data.data.id;
                        $("#mailer-"+id).replaceWith(data.html);
                        return false;
                    }
                }else{
                    toastr.error('添加失败');
                }
            },'json');
        }
    })
});
JS;
$this->registerJs($script);

?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?= $model->id ? '更新' : '添加' ?></h4>
        </div>
        <?php $form = ActiveForm::begin([
            'options'=>['class'=>'form-center data-form'],
            'action'=>$model->id ? '/admin/mailer/update' : '/admin/mailer/save',
            'method'=>'post'])?>
        <div class="modal-body">
            <?= $form->field($model, 'id')->label(false)->hiddenInput()?>
            <?= $form->field($model, 'type')->label('')->hiddenInput()?>
            <?= $form->field($model, 'email_address')->label('邮箱地址')->textInput(['placeholder'=>'邮箱地址'])?>
            <?php if($type==0) : ?>
                <?= $form->field($model, 'password')->label('密码')->textInput(['placeholder'=>'密码'])?>
            <?php endif ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <?= Html::submitButton('保存',['class'=>'btn btn-primary form-submit'])?>
        </div>
        <?php ActiveForm::end()?>
    </div>
</div>