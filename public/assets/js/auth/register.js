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
                message: '用户名验证失败',
                validators: {
                    notEmpty: {
                        message: '用户名不能为空'
                    },
                    stringLength :{
                        min    : 6,
                        max    : 32,
                        message: '长度范围请控制在6-32位'
                    }
                    
                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        alert("验证通过");
    });
});
// var vm = avalon.define({
//
//     $id     : 'register',
//     debug   : false
//
//
// });