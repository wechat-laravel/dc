$(function () {
    $('form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            password: {
                validators: {
                    notEmpty: {
                        message: '原密码不能为空'
                    },
                    stringLength :{
                        min    : 6,
                        max    : 32,
                        message: '密码长度为6~32位'
                    }
                }
            },
            new_password: {
                validators: {
                    notEmpty: {
                        message: '新密码不能为空'
                    },
                    stringLength :{
                        min    : 6,
                        max    : 32,
                        message: '密码长度为6~32位'
                    }
                }
            },
            confirm: {
                validators: {
                    notEmpty: {
                        message: '确认密码不能为空'
                    },
                    stringLength :{
                        min    : 6,
                        max    : 32,
                        message: '密码长度为6~32位'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        var data = $('.form').serialize();
        $.ajax({
            url: '/admin/user/account',
            type: 'POST',
            data: data,
            datatype: 'text'
        }).done(function(ret){
            if (!ret.success){
                $('#error-show').html(show.tml);
                $('#errinfo').text(ret.msg);
            }else{
                window.location = '/auth/login';
            }
            $('form').bootstrapValidator('disableSubmitButtons', false);
        });
    });
});
var show = avalon.define({
    $id          : 'show',
    start        : 60,
    tml          : "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>",
    //获取邮箱验证码
    onVcode      : function () {
        if (show.start === 60){
            $.ajax({
                url     : '/admin/user/send',
                success : function (ret) {
                    if(!ret.success){
                        $('#error-show').html(show.tml);
                        $('#errinfo').text(ret.msg);
                    }else{
                        show.start = 59;
                        setTimeout(function () {
                            show.onVcode()
                        }, 1000);
                    }
                }
            })
        }else if (show.start > 0 && show.start !==60) {
            $('#times').text('(' + show.start + 's)');
            setTimeout(function () {
                show.onVcode()
            }, 1000);
            show.start = show.start - 1;
        } else {
            $('#times').text('');
            show.start = 60;
            return false;
        }
    }
});