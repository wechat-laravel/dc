$('#captcha').attr('src','/captcha?'+Math.random().toString(36).substr(2));

function onCaptcha(){

    $('#captcha').attr('src','/captcha?'+Math.random().toString(36).substr(2));

}
var tml = "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>";
$(function () {

    $('form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: '邮箱账号不能为空'
                    },
                    emailAddress :{
                        message: '邮箱格式不正确'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: '密码不能为空'
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

        $.ajax({
            url     : '/captcha/check?captcha='+$('input[name=captcha]').val(),
            success : function (ret) {
                if (ret.success){
                    var data = $('.login.form').serialize();
                    $.ajax({
                        url: '/auth/login',
                        type: 'POST',
                        data: data,
                        datatype: 'text'
                    }).done(function(ret){
                        if (!ret.success){
                            $('#captcha').attr('src','/captcha?'+Math.random().toString(36).substr(2));
                            $('#error-show').html(tml);
                            $('#errinfo').text(ret.msg);
                        }else{
                            window.location = '/';
                        }
                    });
                }else{
                    $('#captcha').attr('src','/captcha?'+Math.random().toString(36).substr(2));
                    $('#error-show').html(tml);
                    $('#errinfo').text('验证码错误！');
                }
                $('form').bootstrapValidator('disableSubmitButtons', false);
            }
        });
    });
});