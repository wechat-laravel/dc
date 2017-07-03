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
                        message: '邮箱地址不能为空'
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
            },
            confirm: {
                validators: {
                    notEmpty: {
                        message: '请输入确认密码'
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
        var data = $('.register.form').serialize();
        $.ajax({
            url: '/auth/forget',
            type: 'POST',
            data: data,
            datatype: 'text'
        }).done(function(ret){
            if (!ret.success){
                $('#error-show').html(vm.tml);
                $('#errinfo').text(ret.msg);
            }else{
                window.location = '/auth/login';
            }
            $('form').bootstrapValidator('disableSubmitButtons', false);
        });
    });
});
var vm = avalon.define({
    $id          : 'register',
    start        : 60,
    tml          : "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>",
    //获取邮箱验证码
    onVcode      : function () {
        var email =  $("input[name=email]").val();
        if (email){
            if (vm.start === 60){
                $.ajax({
                    url     : '/auth/send?email='+email+'&method=forget',
                    success : function (ret) {
                        if(!ret.success){
                            $('#error-show').html(vm.tml);
                            $('#errinfo').text(ret.msg);
                        }else{
                            vm.start = 59;
                            setTimeout(function () {
                                vm.onVcode()
                            }, 1000);
                        }
                    }
                })
            }else if (vm.start > 0 && vm.start !==60) {
                $('#times').text('(' + vm.start + 's)');
                setTimeout(function () {
                    vm.onVcode()
                }, 1000);
                vm.start = vm.start - 1;
            } else {
                $('#times').text('');
                vm.start = 60;
                return false;
            }
        }else{
            $('#error-show').html(vm.tml);
            $('#errinfo').text('请输入邮箱账号！');
        }
    }
});