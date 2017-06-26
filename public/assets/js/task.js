var tml = "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>";
$(function () {
    $('form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '名字不能为空'
                    },
                    stringLength :{
                        min    : 2,
                        max    : 20,
                        message: '长度请保持在20位以内'
                    }
                }
            },
            mobile: {
                validators: {
                    notEmpty: {
                        message: '手机号码不能为空'
                    }
                }
            },
            remark: {
                validators: {
                    stringLength :{
                        max    : 200,
                        message: '字符数不能超过200'
                    }

                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        var data = $('.form').serialize();
        $.ajax({
            url: '/wechat/entered',
            type: 'POST',
            data: data,
            datatype: 'text'
        }).done(function(ret){
            if (!ret.success){
                $('#error-show').html(tml);
                $('#errinfo').text(ret.msg);
            }else{
                $("#myModal").modal('hide');
            }
            $('form').bootstrapValidator('disableSubmitButtons', false);
        });
    });
});