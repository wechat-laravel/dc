var tml = "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>";
$(function () {
    $('form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            user_email: {
                validators: {
                    notEmpty: {
                        message: '邮箱账号不能为空'
                    },
                    emailAddress :{
                        message: '邮箱格式不正确'
                    }
                }
            },
            money: {
                validators: {
                    notEmpty: {
                        message: '充值数额不能为空'
                    }
                }
            },
            confirm: {
                validators: {
                    notEmpty: {
                        message: '确认数额不能为空'
                    }
                }
            },
            remark: {
                validators: {
                    stringLength :{
                        max    : 100,
                        message: '长度请保持在100位以内'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        var data = $('.form').serialize();
        $.ajax({
            url: '/admin/supper/recharge',
            type: 'POST',
            data: data,
            datatype: 'text'
        }).done(function(ret){
            if (!ret.success){
                $('#error-show').html(tml);
                $('#errinfo').text(ret.msg);
            }else{
                window.location = '/admin/supper/record';
            }
            $('form').bootstrapValidator('disableSubmitButtons', false);
        });
    });
});
