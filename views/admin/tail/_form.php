<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->registerJsFile('/js/fileUpload/ajaxfileupload.js');
$script = <<< JS
$(document).ready(function(){
  
})
JS;
$this->registerJs($script);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">
                            尾号玩法
                        </a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
            <header class="main-box-header clearfix">
                <h2>时时彩 报警时间段 [例: 09时 23时  24小时制度 24=0 最好不要设置 24 或者 0]</h2>
            </header>
            <div class="main-box-body clearfix">

                <?php $form = ActiveForm::begin([
                    'options'=>['class'=>'form-center data-form','enctype'=>'multipart/form-data'],
                    'action'=>Url::to('/admin/tail/submit'),
                    'method'=>'post'])?>
                <div class="modal-body">

                    <?= $form->field($model, 'id')->label('')->hiddenInput(['value'=>$model->id])?>
                    <?= $form->field($model, 'time')->label('')->hiddenInput(['value'=>$model->time])?>
                    <?= $form->field($model, 'zero')->label('0对应下期开奖号码')->textInput(['placeholder'=>'0对应下期开奖号码'])?>
                    <?= $form->field($model, 'one')->label('1对应下期开奖号码')->textInput(['placeholder'=>'1对应下期开奖号码'])?>
                    <?= $form->field($model, 'two')->label('2对应下期开奖号码')->textInput(['placeholder'=>'2对应下期开奖号码'])?>
                    <?= $form->field($model, 'three')->label('3对应下期开奖号码')->textInput(['placeholder'=>'3对应下期开奖号码'])?>
                    <?= $form->field($model, 'four')->label('4对应下期开奖号码')->textInput(['placeholder'=>'4对应下期开奖号码'])?>
                    <?= $form->field($model, 'five')->label('5对应下期开奖号码')->textInput(['placeholder'=>'5对应下期开奖号码'])?>
                    <?= $form->field($model, 'six')->label('6对应下期开奖号码')->textInput(['placeholder'=>'6对应下期开奖号码'])?>
                    <?= $form->field($model, 'seven')->label('7对应下期开奖号码')->textInput(['placeholder'=>'7对应下期开奖号码'])?>
                    <?= $form->field($model, 'eight')->label('8对应下期开奖号码')->textInput(['placeholder'=>'8对应下期开奖号码'])?>
                    <?= $form->field($model, 'nine')->label('9对应下期开奖号码')->textInput(['placeholder'=>'9对应下期开奖号码'])?>
                    <?= $form->field($model, 'continuity')->label('连续出现报警期数')->textInput(['placeholder'=>'报警开始时间'])?>
                    <?= $form->field($model, 'discontinuous')->label('未连续出现报警期数')->textInput(['placeholder'=>'报警开始时间'])?>
                    <?= $form->field($model, 'start')->label('开始时间 - 开始与结束都为0则全天报警')->textInput(['placeholder'=>'报警开始时间'])?>
                    <?= $form->field($model, 'end')->label('结束时间 - 开始与结束都为0则全天报警')->textInput(['placeholder'=>'报警开始时间'])?>
                    <?= $form->field($model, 'status')->label('是否开启报警')->dropDownList([1=>'开启',0=>'关闭'])?>

                </div>

                <div class="btn-group pull-right" style="margin-right: 15px">
                    <?= Html::resetButton('重置',['class'=>'btn btn-success'])?>
                    <?= Html::submitButton('保存',['class'=>'btn btn-success right'])?>
                </div>
                <?php ActiveForm::end()?>


            </div>
        </div>
    </div>
</div>
