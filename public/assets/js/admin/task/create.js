var tml = "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>";
$(function () {
    $('form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            title: {
                validators: {
                    notEmpty: {
                        message: '封面标题不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 50,
                        message: '字符长度为1~50位'
                    }

                }
            },
            desc: {
                validators: {
                    notEmpty: {
                        message: '封面描述不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 100,
                        message: '字符长度为1~100位'
                    }

                }
            },
            img_url: {
                validators: {
                    notEmpty: {
                        message: '封面图片地址不能为空'
                    },
                    uri :{
                        message: '格式不正确'
                    }

                }
            },
            page_url: {
                validators: {
                    notEmpty: {
                        message: 'H5页面地址不能为空'
                    },
                    uri :{
                        message: '格式不正确'
                    }
                }
            }

        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();

        var data = $('.create.form').serialize();
        $.ajax({
            url: '/admin/task',
            type: 'POST',
            data: data,
            datatype: 'text'
        }).done(function(ret){
            if (!ret.success){
                $('#error-show').html(tml);
                $('#errinfo').text(ret.msg);
            }else{
                location.href = '/admin/task';
            }
        });

        $('form').bootstrapValidator('disableSubmitButtons', false);

    });
});