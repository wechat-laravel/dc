var show = avalon.define({
    $id      : "show",
    money    : 0,
    onMoney  : function (e) {
        show.money = parseInt(e);
    },
    onQuery  : function () {
        setTimeout(function () {
            console.log(1);
        }, 1000);
    }
});


$(function () {
    $('.form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            money: {
                validators: {
                    notEmpty: {
                        message: '充值金额不能为空'
                    },
                    integer: {
                        message: '充值金额必须为整数'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {
        e.preventDefault();
        var money = parseInt($('input[name=money]').val());

        $.ajax({
            url:'/admin/user/qrcode?money='+money
        }).done(function (ret) {
            if(ret.success){
                $('#qr').attr('src',ret.src);
                $('#myModal').modal('show');

            }else{
                $('#infos').text(ret.msg);
                $('.bs-result-modal-sm').modal('show');
            }
        });
        $('form').bootstrapValidator('disableSubmitButtons', false);
    });
});