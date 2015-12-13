<?php
$csrf = Yii::$app->request->getCsrfToken();
$script = <<< JS
$(document).ready(function(){
    $(".shishicai").click(function(){
    })
});
JS;
$this->registerJs($script);
?>

<div class="container">
    <div class="contents">
        <?= $this->render('_list',['model'=>$model])?>
    </div>
</div>
