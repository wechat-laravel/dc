var show = avalon.define({
    $id          : "show",
    title        : '',    //标题
    share        : '',    //分享来源
    label        : '',    //标签
    mobile       : '',    //联系电话
    chat_url     : '',    //在线咨询连接
    one_t        : '',
    one_d        : '',
    one_d_url    : '',
    two_t        : '',
    two_d        : '',
    two_d_url    : '',
    three_t      : '',
    three_d      : '',
    three_d_url  : '',

    preview      : function () {
        show.title       = $('input[name=title]').val();
        show.share       = $('input[name=share]').val();
        show.label       = $('input[name=label]').val();
        show.mobile      = $('input[name=mobile]').val();
        show.chat_url    = $('input[name=chat_url]').val();
        show.one_t       = $('input[name=one_t]').val();
        show.one_d       = $('input[name=one_d]').val();
        show.one_d_url   = $('input[name=one_d_url]').val();
        show.two_t       = $('input[name=two_t]').val();
        show.two_d       = $('input[name=two_d]').val();
        show.two_d_url   = $('input[name=two_d_url]').val();
        show.three_t     = $('input[name=three_t]').val();
        show.three_d     = $('input[name=three_d]').val();
        show.three_d_url = $('input[name=three_d_url]').val();
    },

    onQrcode    : function () {
        $('.bs-qrcode-modal-sm').modal('show');
    },

    onMobile    : function () {
        var mobile  = '13666666666';

        if(show.mobile !== ''){
            mobile = show.mobile;
        }

        if(confirm('确定拨打电话：'+mobile+'吗?')){
            console.log(1);
        }
    }

});

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
                        message: '模板名称不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 100,
                        message: '字符长度为1~100位'
                    }

                }
            },
            share: {
                validators: {
                    notEmpty: {
                        message: '分享来源不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 20,
                        message: '字符长度为1~20位'
                    }

                }
            },
            title: {
                validators: {
                    notEmpty: {
                        message: '主体名称不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 20,
                        message: '字符长度为1~20位'
                    }

                }
            },
            label: {
                validators: {
                    notEmpty: {
                        message: '主体标签不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 20,
                        message: '字符长度为1~20位'
                    }

                }
            },
            mobile: {
                validators: {
                    notEmpty: {
                        message: '联系电话不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 30,
                        message: '长度不能超过30位'
                    }

                }
            },
            chat_url: {
                validators: {
                    notEmpty: {
                        message: '在线咨询连接不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 300,
                        message: '长度不能超过300位'
                    }

                }
            },
            one_t: {
                validators: {
                    notEmpty: {
                        message: '标题一不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 20,
                        message: '长度不能超过20位'
                    }

                }
            },
            one_d: {
                validators: {
                    notEmpty: {
                        message: '标题一内容不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 100,
                        message: '长度不能超过100位'
                    }

                }
            },
            one_d_url: {
                validators: {
                    stringLength :{
                        max    : 300,
                        message: '长度不能超过300位'
                    }

                }
            },
            two_t: {
                validators: {
                    notEmpty: {
                        message: '标题二不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 20,
                        message: '长度不能超过20位'
                    }

                }
            },
            two_d: {
                validators: {
                    notEmpty: {
                        message: '标题二内容不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 100,
                        message: '长度不能超过100位'
                    }

                }
            },
            two_d_url: {
                validators: {
                    stringLength :{
                        max    : 300,
                        message: '长度不能超过300位'
                    }

                }
            },
            three_t: {
                validators: {
                    notEmpty: {
                        message: '标题三不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 20,
                        message: '长度不能超过20位'
                    }

                }
            },
            three_d: {
                validators: {
                    notEmpty: {
                        message: '标题三内容不能为空'
                    },
                    stringLength :{
                        min    : 1,
                        max    : 100,
                        message: '长度不能超过100位'
                    }

                }
            },
            three_d_url: {
                validators: {
                    stringLength :{
                        max    : 300,
                        message: '长度不能超过300位'
                    }

                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        var data = new FormData($('.create.form')[0]);

        $.ajax({
            url: '/admin/service/ad_column',
            type: 'POST',
            data: data,
            dataType: 'JSON',
            cache: false,
            processData: false,
            contentType: false
        }).done(function(ret){
            console.log(ret);
            if (!ret.success){
                $('#error-show').html(tml);
                $('#errinfo').text(ret.msg);
            }else{
                location.href = '/admin/service/ad_column';
            }
        });

        $('form').bootstrapValidator('disableSubmitButtons', false);

    });
});


