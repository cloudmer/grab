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

?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?= $model->id ? '更新' : '添加' ?></h4>
        </div>
        <?php $form = ActiveForm::begin([
            'options'=>['class'=>'form-center menu-form'],
            'action'=>$model->id ? '/admin/contain/update' : '/admin/contain/save',
            'method'=>'post'])?>
        <div class="modal-body">
            <?= $form->field($model, 'id')->label(false)->hiddenInput()?>
            <?= $form->field($model,'cp_type')->dropDownList([0=>'所有彩种',1=>'重庆时时彩',2=>'天津时时彩',3=>'新疆时时彩',4=>'台湾五分彩'])?>
            <?= $form->field($model, 'contents')->textInput(['placeholder'=>'包含号码','type'=>'number','maxlength'=>5])?>
            <?= $form->field($model, 'number')->textInput(['placeholder'=>'报警期数','type'=>'number','maxlength'=>5])?>
            <?= $form->field($model, 'start')->label('开始时间 - 开始与结束都为0则全天报警')->textInput(['placeholder'=>'报警开始时间'])?>
            <?= $form->field($model, 'end')->label('结束时间 - 开始与结束都为0则全天报警')->textInput(['placeholder'=>'报警结束时间'])?>
            <?= $form->field($model,'valve')->dropDownList([0=>'关闭',1=>'开启'])?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <?= Html::submitButton('保存',['class'=>'btn btn-primary form-submit'])?>
        </div>
        <?php ActiveForm::end()?>
    </div>
</div>
