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

                <?php if(isset($msg1)) : ?>
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
                <h2>重庆时时彩 - [数据包1]  报警时间段 [例: 09时 23时  24小时制度 24=0 最好不要设置 24 或者 0]</h2>
            </header>
            <div class="main-box-body clearfix">

                <?php if(isset($msg2)) : ?>
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

<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
            <header class="main-box-header clearfix">
                <h2>重庆时时彩  - [数据包2]  报警时间段 [例: 09时 23时  24小时制度 24=0 最好不要设置 24 或者 0]</h2>
            </header>
            <div class="main-box-body clearfix">

                <?php if(isset($msg22)) : ?>
                    <div class="alert alert-block alert-success">
                        <?= $msg22 ?>
                    </div>
                <?php endif  ?>

                <?php $form = ActiveForm::begin([
                    'options'=>['class'=>'form-center data-form'],
                    'action'=>'',
                    'method'=>'post'])?>
                <div class="modal-body">
                    <?= $form->field($modelCq2, 'start_time')->label('开始时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelCq2, 'end_time')->label('结束时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelCq2, 'regret_number')->label('未中奖报警期数')->textInput()?>
                    <?= $form->field($modelCq2, 'forever')->label('是否开启每一期中奖提示(包含中奖与未中奖通知)')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelCq2, 'state')->label('是否开启报警')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelCq2, 'type')->label('')->hiddenInput()?>
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
                <h2>天津时时彩  - [数据包1]   报警时间段 [例: 09时 23时  24小时制度 24=0 最好不要设置 24 或者 0]</h2>
            </header>
            <div class="main-box-body clearfix">

                <?php if(isset($msg3)) : ?>
                    <div class="alert alert-block alert-success">
                        <?= $msg3 ?>
                    </div>
                <?php endif  ?>

                <?php $form = ActiveForm::begin([
                    'options'=>['class'=>'form-center data-form'],
                    'action'=>'',
                    'method'=>'post'])?>
                <div class="modal-body">
                    <?= $form->field($modelTj, 'start_time')->label('开始时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelTj, 'end_time')->label('结束时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelTj, 'regret_number')->label('未中奖报警期数')->textInput()?>
                    <?= $form->field($modelTj, 'forever')->label('是否开启每一期中奖提示(包含中奖与未中奖通知)')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelTj, 'state')->label('是否开启报警')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelTj, 'type')->label('')->hiddenInput()?>
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
                <h2>天津时时彩  - [数据包2]   报警时间段 [例: 09时 23时  24小时制度 24=0 最好不要设置 24 或者 0]</h2>
            </header>
            <div class="main-box-body clearfix">

                <?php if(isset($msg33)) : ?>
                    <div class="alert alert-block alert-success">
                        <?= $msg33 ?>
                    </div>
                <?php endif  ?>

                <?php $form = ActiveForm::begin([
                    'options'=>['class'=>'form-center data-form'],
                    'action'=>'',
                    'method'=>'post'])?>
                <div class="modal-body">
                    <?= $form->field($modelTj2, 'start_time')->label('开始时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelTj2, 'end_time')->label('结束时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelTj2, 'regret_number')->label('未中奖报警期数')->textInput()?>
                    <?= $form->field($modelTj2, 'forever')->label('是否开启每一期中奖提示(包含中奖与未中奖通知)')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelTj2, 'state')->label('是否开启报警')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelTj2, 'type')->label('')->hiddenInput()?>
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
                <h2>新疆时时彩  - [数据包1]    报警时间段 [例: 09时 23时  24小时制度 24=0 最好不要设置 24 或者 0]</h2>
            </header>
            <div class="main-box-body clearfix">

                <?php if(isset($msg4)) : ?>
                    <div class="alert alert-block alert-success">
                        <?= $msg4 ?>
                    </div>
                <?php endif  ?>

                <?php $form = ActiveForm::begin([
                    'options'=>['class'=>'form-center data-form'],
                    'action'=>'',
                    'method'=>'post'])?>
                <div class="modal-body">
                    <?= $form->field($modelXj, 'start_time')->label('开始时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelXj, 'end_time')->label('结束时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelXj, 'regret_number')->label('未中奖报警期数')->textInput()?>
                    <?= $form->field($modelXj, 'forever')->label('是否开启每一期中奖提示(包含中奖与未中奖通知)')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelXj, 'state')->label('是否开启报警')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelXj, 'type')->label('')->hiddenInput()?>
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
                <h2>新疆时时彩  - [数据包2]    报警时间段 [例: 09时 23时  24小时制度 24=0 最好不要设置 24 或者 0]</h2>
            </header>
            <div class="main-box-body clearfix">

                <?php if(isset($msg44)) : ?>
                    <div class="alert alert-block alert-success">
                        <?= $msg44 ?>
                    </div>
                <?php endif  ?>

                <?php $form = ActiveForm::begin([
                    'options'=>['class'=>'form-center data-form'],
                    'action'=>'',
                    'method'=>'post'])?>
                <div class="modal-body">
                    <?= $form->field($modelXj2, 'start_time')->label('开始时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelXj2, 'end_time')->label('结束时间')->textInput(['placeholder'=>'邮箱地址'])?>
                    <?= $form->field($modelXj2, 'regret_number')->label('未中奖报警期数')->textInput()?>
                    <?= $form->field($modelXj2, 'forever')->label('是否开启每一期中奖提示(包含中奖与未中奖通知)')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelXj2, 'state')->label('是否开启报警')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($modelXj2, 'type')->label('')->hiddenInput()?>
                </div>
                <div class="btn-group pull-right" style="margin-right: 15px">
                    <?= Html::submitButton('保存',['class'=>'btn btn-success right'])?>
                </div>
                <?php ActiveForm::end()?>
            </div>
        </div>
    </div>
</div>