var $cropper = {
    init:function(aspectRatio){
        init(aspectRatio='1/1');
    }
}

/*
* 初始化插件
* */
function init(aspectRatio){
    'use strict';

    var console = window.console || { log: function () {} };
    var $body = $('body');


    // Tooltip
    // $('[data-toggle="tooltip"]').tooltip();
    // $.fn.tooltip.noConflict();
    // $body.tooltip();

    (function () {
        var $image = $('.img-container > img');
        var $actions = $('.docs-actions');
        var $download = $('#download');
        var $dataX = $('#dataX');
        var $dataY = $('#dataY');
        var $dataHeight = $('#dataHeight');
        var $dataWidth = $('#dataWidth');
        var $dataRotate = $('#dataRotate');
        var $dataScaleX = $('#dataScaleX');
        var $dataScaleY = $('#dataScaleY');
        var options = {
            aspectRatio: aspectRatio,
            preview: '.img-preview',
            crop: function (e) {
                $dataX.val(Math.round(e.x));
                $dataY.val(Math.round(e.y));
                $dataHeight.val(Math.round(e.height));
                $dataWidth.val(Math.round(e.width));
                $dataRotate.val(e.rotate);
                $dataScaleX.val(e.scaleX);
                $dataScaleY.val(e.scaleY);
            }
        };
        /*
         * 是否是 image 类型文件
         * */
        var $isImageFile = function(file){
            if (file.type) {
                return /^image\/\w+$/.test(file.type);
            } else {
                var result = /\.(jpg|jpeg|png|gif)$/.test(file);
                if(!result){
                    toastr.error('图像类型只能为jpg,jpeg,png,gif','请选择图像文件');
                }
                return result;
            }
        }
        /*
         * 文件上传限制
         * */
        var $limitFileSize = function(self){
            if(self.val()==''){
                return false;
            }
            var fileSize = self[0].files[0].size;
            var limitSize = 1024*1024*5;
            if(fileSize>=limitSize){
                self.val('');
                toastr.error('图片大小超过上限为5MB',{"positionClass": "toast-top-full-width"});
                return false;
            }
            return true;
        }

        /*
        * 选择图片
        * */
        var $setImage = function(self,file){
            if(!$limitFileSize(self)){
                return false;
            }
            var previous = $image.attr('src');
            if(previous){
                //bootcss 静态框内选择图片
                blobURL = URL.createObjectURL(file);
                $image.one('built.cropper', function () {
                    URL.revokeObjectURL(blobURL); // Revoke when load complete
                }).cropper('reset').cropper('replace', blobURL);
                $image.cropper(options);
            }else{
                //bootcss 静态框关闭后被打开
                $('#cropper-modal').modal('show');
                $('#cropper-modal').on('shown.bs.modal', function (e) {
                    //bootcss 静态框 打开后 设置图像
                    blobURL = URL.createObjectURL(file);
                    $image.one('built.cropper', function () {
                        URL.revokeObjectURL(blobURL); // Revoke when load complete
                    }).cropper('reset').cropper('replace', blobURL);
                })

            }
        }

        $image.on({
            'build.cropper': function (e) {
                //console.log(e.type);
            },
            'built.cropper': function (e) {
                //console.log(e.type);
            },
            'cropstart.cropper': function (e) {
                //console.log(e.type, e.action);
            },
            'cropmove.cropper': function (e) {
                //console.log(e.type, e.action);
            },
            'cropend.cropper': function (e) {
                //console.log(e.type, e.action);
            },
            'crop.cropper': function (e) {
                //console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
            },
            'zoom.cropper': function (e) {
                //console.log(e.type, e.ratio);
            }
        }).cropper(options);

        // Methods
        $actions.on('click', '[data-method]', function () {
            var $this = $(this);
            var data = $this.data();
            var $target;
            var result;

            if ($this.prop('disabled') || $this.hasClass('disabled')) {
                return;
            }

            if ($image.data('cropper') && data.method) {
                data = $.extend({}, data); // Clone a new one

                if (typeof data.target !== 'undefined') {
                    $target = $(data.target);

                    if (typeof data.option === 'undefined') {
                        try {
                            data.option = JSON.parse($target.val());
                        } catch (e) {
                            //console.log(e.message);
                        }
                    }
                }

                result = $image.cropper(data.method, data.option, data.secondOption);

                if (data.flip === 'horizontal') {
                    $(this).data('option', -data.option);
                }

                if (data.flip === 'vertical') {
                    $(this).data('secondOption', -data.secondOption);
                }

                if (data.method === 'getCroppedCanvas' && result) {
                    $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

                    if (!$download.hasClass('disabled')) {
                        $download.attr('href', result.toDataURL());
                    }
                }

                if ($.isPlainObject(result) && $target) {
                    try {
                        $target.val(JSON.stringify(result));
                    } catch (e) {
                        //console.log(e.message);
                    }
                }

            }
        });


        // Import image
        var $inputImage = $('.inputImage');

        var URL = window.URL || window.webkitURL;
        var blobURL;

        if (URL) {
            $('body').on('change','.inputImage',function(){
                var d = new Date();
                var id = 'upload-'+d.getTime() ;
                $(this).attr('id',id);
                $(this).attr("name","file");
                $image.attr('source-id',id);

                var files = this.files;
                var file;

                if (!$image.data('cropper')) {
                    return;
                }
                if (files && files.length) {
                    file = files[0];
                    if($isImageFile(file)){
                        $setImage($(this),file);
                    }
                }
            })

            /*
            $inputImage.change(function () {
                alert('选择了图片')
                var d = new Date();
                var id = 'upload-'+d.getTime() ;
                $(this).attr('id',id);
                $(this).attr("name","file");
                $image.attr('source-id',id);

                var files = this.files;
                var file;

                if (!$image.data('cropper')) {
                    return;
                }
                if (files && files.length) {
                    file = files[0];
                    if($isImageFile(file)){
                        $setImage($(this),file);
                    }
                }
            });
            */
        } else {
            $inputImage.prop('disabled', true).parent().addClass('disabled');
        }


    }());
}

$(function(){
    $cropper.init();
    var $inputImage = $('.inputImage');
    var $image = $('.img-container > img');

    $('#cropper-modal').on('hidden.bs.modal', function (e) {
        //bootcss 静态框 关闭后销毁插件 重置图像
        $inputImage.val('');
        $image.cropper('destroy');
        $image.cropper('reset');
        $image.cropper("setAspectRatio", 1 / 1)
    })

    /*
     * image 上传操作
     * */
    $('#image-upload').on('click', function () {
        var $btn = $(this).button('loading');
        toastr.info('请稍后');
        // business logic...
        //$btn.button('reset');

        var csrf = $(this).attr('data-csrf');
        var cropperResult = $image.cropper('getData');
        var upData = JSON.stringify(cropperResult);
        var id = $image.attr('source-id');
        var url = $('#'+id).attr('data-url');
        url = url+'?crop-data='+upData;

        $.ajaxFileUpload({
            url: url,
            secureuri: true,
            fileElementId: id,
            dataType: 'json',
            data:{_csrf:csrf},
            success: function (data, status) {
                $btn.button('reset');
                $('#cropper-modal').modal('hide');

                if(data.code==1){
                    toastr.success(data.message);
                }else{
                    toastr.error(data.message,{"positionClass": "toast-top-full-width"});
                }
            }
        });
    })
})
