<?php
use yii\helpers\Url;


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
                    <li><a href="#">数据管理</a></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-12">
        <div class="form-group field-menus-name required has-">
            <label class="control-label" for="menus-name">数据包别名</label>
            <input type="text" id="alias"value="<?= $model->alias ?>" class="form-control" name="alias" placeholder="数据包别名">
        </div>
    </div>

    <div class="col-lg-12">
        <div class="main-box clearfix">
            <div class="main-box-body clearfix">
                <div class="table-responsive">
                    <table id="table-example-fixed" class="table table-hover">
                        <thead>
                        <tr>
                            <th>数据包A - 号组</th>
                            <th>号(1)</th>
                            <th>号(2)</th>
                            <th>号(3)</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        if(!$model->package_a){
                            return false;
                        }
                        $index = 0;
                        $content = str_replace("\r\n", ' ', $model->package_a); //把换行符 替换成空格
                        $contentArr = explode(' ',$content);
                        $contentArr = array_filter($contentArr);
                        $contentArr = array_chunk($contentArr,3);
                        ?>
                        <?php foreach($contentArr as $val): ?>
                            <tr>
                                <td><span class="label label-success">组-<?= $index = $index+1?></span></td>
                                <?php foreach($val as $v): ?>
                                    <td><?= $v ?></td>
                                <?php endforeach ?>
                            </tr>
                        <?php endforeach ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12">
        <div class="main-box clearfix">
            <div class="main-box-body clearfix">
                <div class="table-responsive">
                    <table id="table-example-fixed" class="table table-hover">
                        <thead>
                        <tr>
                            <th>数据包B - 号组</th>
                            <th>号(1)</th>
                            <th>号(2)</th>
                            <th>号(3)</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        if(!$model->package_b){
                            return false;
                        }
                        $index = 0;
                        $content = str_replace("\r\n", ' ', $model->package_b); //把换行符 替换成空格
                        $contentArr = explode(' ',$content);
                        $contentArr = array_filter($contentArr);
                        $contentArr = array_chunk($contentArr,3);
                        ?>
                        <?php foreach($contentArr as $val): ?>
                            <tr>
                                <td><span class="label label-success">组-<?= $index = $index+1?></span></td>
                                <?php foreach($val as $v): ?>
                                    <td><?= $v ?></td>
                                <?php endforeach ?>
                            </tr>
                        <?php endforeach ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>