<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">密码修改</a></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="main-box">
        <header class="main-box-header clearfix">
            <h2>密码修改</h2>
        </header>
        <div class="main-box-body clearfix">

            <?php if(isset($error) || isset($success)): ?>
                <?php isset($error) ? $class = 'alert-warning' : null ?>
                <?php isset($success) ? $class = 'alert-success' : null ?>
                <div class="alert alert-block <?= $class ?>">
                    <?= isset($error) ? $error : $success ?>
                </div>
            <?php endif  ?>

            <form id="w0" class="form-center data-form" action="<?= \yii\helpers\Url::to('/admin/password/update')?>" method="post">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>">
                <div class="form-group field-configure-start_time">
                    <label class="control-label">原密码</label>
                    <input type="text" class="form-control" name="password" value="" placeholder="原密码">
                </div>
                <div class="form-group field-configure-end_time">
                    <label class="control-label">新密码</label>
                    <input type="password" class="form-control" name="newPassword" value="" placeholder="新密码">
                </div>
                <div class="form-group field-configure-end_time">
                    <label class="control-label"">确认密码</label>
                    <input type="password" class="form-control" name="confirmPassword" value="" placeholder="确认密码">
                </div>
                <div class="btn-group pull-right" style="margin-right: 15px">
                    <button type="submit" class="btn btn-success right">修改</button>
                </div>
            </form>

        </div>
    </div>
</div>