<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">报警设置</a></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
            <header class="main-box-header clearfix">
                <h2>新时时彩 报警时间段 [例: 09时 23时  24小时制度 24=0 最好不要设置 24 或者 0]</h2>
            </header>
            <div class="main-box-body clearfix">

                <? if(isset($msg1)) : ?>
                    <div class="alert alert-block alert-success">
                        <?= $msg1 ?>
                    </div>
                <?php endif  ?>

                <?php $form = ActiveForm::begin([
                    'options'=>['class'=>'form-center data-form'],
                    'action'=>'',
                    'method'=>'post'])?>
                <div class="modal-body">
                    <?= $form->field($model, 'start_time')->label('开始时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($model, 'end_time')->label('结束时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($model, 'regret_number')->label('未中奖报警期数')->textInput()?>
                    <?= $form->field($model, 'forever')->label('是否开启每一期中奖提示(包含中奖与未中奖通知)')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($model, 'state')->label('是否开启报警')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($model, 'type')->label('')->hiddenInput()?>
                </div>
                <div class="btn-group pull-right" style="margin-right: 15px">
                    <?= Html::submitButton('保存',['class'=>'btn btn-success right'])?>
                </div>
                <?php ActiveForm::end()?>


            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
            <header class="main-box-header clearfix">
                <h2>旧时时彩 报警时间段 [例: 09时 23时  24小时制度 24=0 最好不要设置 24 或者 0]</h2>
            </header>
            <div class="main-box-body clearfix">

                <? if(isset($msg2)) : ?>
                    <div class="alert alert-block alert-success">
                        <?= $msg2 ?>
                    </div>
                <?php endif  ?>

                <?php $form = ActiveForm::begin([
                    'options'=>['class'=>'form-center data-form'],
                    'action'=>'',
                    'method'=>'post'])?>
                <div class="modal-body">
                    <?= $form->field($modelOld, 'start_time')->label('开始时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelOld, 'end_time')->label('结束时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelOld, 'regret_number')->label('未中奖报警期数')->textInput()?>
                    <?= $form->field($modelOld, 'forever')->label('是否开启每一期中奖提示(包含中奖与未中奖通知)')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelOld, 'state')->label('是否开启报警')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelOld, 'type')->label('')->hiddenInput()?>
                </div>
                <div class="btn-group pull-right" style="margin-right: 15px">
                    <?= Html::submitButton('保存',['class'=>'btn btn-success right'])?>
                </div>
                <?php ActiveForm::end()?>


            </div>
        </div>
    </div>
</div>