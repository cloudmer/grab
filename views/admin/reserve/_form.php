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
            'action'=>$model->id ? '/admin/reserve/update' : '/admin/reserve/save',
            'method'=>'post'])?>
        <div class="modal-body">
            <?= $form->field($model, 'id')->label(false)->hiddenInput()?>
            <?= $form->field($model,'cp_type')->dropDownList([1=>'重庆时时彩',2=>'天津时时彩',3=>'新疆时时彩'])?>
            <?= $form->field($model,'type')->dropDownList([1=>'所有',2=>'前三',3=>'中三',4=>'后三'])?>
            <?= $form->field($model,'code_type')->dropDownList([1=>'组6',2=>'组3'])?>

            <?= $form->field($model, 'number')->textInput(['placeholder'=>'报警期数','type'=>'number','maxlength'=>1])?>
            <?= $form->field($model, 'qishu')->textInput(['placeholder'=>'报警期数','type'=>'number','maxlength'=>3])?>
            <?= $form->field($model,'status')->dropDownList([0=>'关闭',1=>'开启'])?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <?= Html::submitButton('保存',['class'=>'btn btn-primary form-submit'])?>
        </div>
        <?php ActiveForm::end()?>
    </div>
</div>
