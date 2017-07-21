var show = avalon.define({
    $id      : "show",
    money    : 0,
    order    : '',
    cc       : 1,
    onMoney  : function (e) {
        show.money = parseInt(e);
    },
    onQuery  : function () {
        $.ajax({
            url:'/admin/user/query?order='+show.order
        }).done(function (ret) {
            if(ret.success){
                // $('#myModal').modal('hide');
                // window.clearInterval(show.cc);
                // alert('支付成功！刷新当前页面可查看余额！');
                location.href = '/admin/user/assets';
            }else{
                console.log(ret.msg);
            }
        });
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
            },
            remark: {
                validators: {
                    stringLength :{
                        max    : 200,
                        message: '长度请保持在200以内！'
                    }

                }
            }
        }
    }).on('success.form.bv', function (e) {
        e.preventDefault();
        var money  = parseInt($('input[name=money]').val());
        var remark = $('input[name=remark]').val();
        var url    = '';
        if(remark){
            url = '/admin/user/qrcode?money='+money+'&remark='+remark;
        }else{
            url = '/admin/user/qrcode?money='+money;
        }

        $.ajax({
            url:url
        }).done(function (ret) {
            if(ret.success){
                $('#qr').attr('src','data:image/png;base64,'+ret.src);
                show.order = ret.order;
                $('#myModal').modal('show');
                show.cc = window.setInterval(show.onQuery,2000);
            }else{
                $('#infos').text(ret.msg);
                $('.bs-result-modal-sm').modal('show');
            }
        });
        $('form').bootstrapValidator('disableSubmitButtons', false);
    });
});