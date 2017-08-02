$('.lazy').each(function(){$(this).attr('data-original',$(this).attr('src'))&&$(this).removeAttr("src")});

var show = avalon.define({
    $id        : "show",
    qrcode     : false,         //获取登录二维码返回的状态
    qrmsg      : '·······',
    login      : false,         //登录状态
    logout     : false,         //退出按钮是否显示
    cc         : 1,             //定时器ID
    allList    : [],            //所有的用户列表
    nowId      : '',
    method     : 'all',         //群发的对象
    city       : [],            //城市
    oks        : 0,             //符合条件的数目
    tml        : "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>",
    onQrcode   : function () {
        $.ajax({
            url:'/admin/service/mass?qrcode=1'
        }).done(function (ret) {
            if(ret.success){
                show.qrcode = true;
                show.qrmsg  = '尚未登录';
                $('#qr').attr('src','data:image/png;base64,'+ret.msg);
            }else{
                show.qrcode = false;
                show.qrmsg  = ret.msg;
            }
        });
    },
    //查询登录状态
    onStatus   : function (){
        $.ajax({
            url:'/admin/service/mass?status=1'
        }).done(function (ret) {
            if(ret.success){
                //表示已登录了，就停止定时器
                if(ret.msg === 'true'){
                    show.qrmsg  = '已登录';
                    show.login  = true;
                    window.clearInterval(show.cc);
                    show.onAllList();
                }
            }
        });
    },
    //获取全部好友列表
    onAllList   : function (){
        $.ajax({
            url:'/admin/service/mass?all_list=1'
        }).done(function (ret) {
            if(ret.success){
                show.nowId   = ret.id;
                show.allList = ret.data;
            }
        });
    },
    //退出登录
    onLogout    : function(){
        $.ajax({
            url:'/admin/service/mass?logout=1'
        }).done(function(ret){
            if(ret.success){
                window.location.reload();
            }
        });
    },
    onTask      : function(){
        $('.setcondition').modal('show');
    },
    onMethod  : function () {
        show.method = $('#method').val();
    },
    onCity    : function () {
        var id = $('#prov').val();

        if(id){
            $.ajax({
                url:'/admin/service/mass?prov_id='+id
            }).done(function (ret) {
                if(ret.success){
                    show.city = ret.city;
                }
            })
        }

    }


});
show.onQrcode();
show.cc = window.setInterval(show.onStatus,2000);


$(function () {
    $('.condition.form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        var data = $('.condition.form').serialize();
        $.ajax({
            url: '/admin/service/mass/condition',
            type: 'POST',
            data: data,
            datatype: 'text'
        }).done(function(ret){
            if(ret.success){
                show.oks = ret.data;
                $('.setcondition').modal('hide');
                $('.setmessage').modal('show');
            }else{
                $('#error-show').html(show.tml);
                $('#errinfo').text(ret.msg);
            }
        });

        $('form').bootstrapValidator('disableSubmitButtons', false);

    });


    $('.message.form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        var data = new FormData($('.message.form')[0]);
        $.ajax({
            url: '/admin/service/mass/message',
            type: 'POST',
            data: data,
            dataType: 'JSON',
            cache: false,
            processData: false,
            contentType: false
        }).done(function(ret){
            console.log(ret);
            // if (!ret.success){
            //     $('#error-show').html(tml);
            //     $('#errinfo').text(ret.msg);
            // }else{
            //     location.href = '/admin/task';
            // }
        });

        $('form').bootstrapValidator('disableSubmitButtons', false);

    });
});
