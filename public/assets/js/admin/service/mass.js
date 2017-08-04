$('.lazy').each(function(){$(this).attr('data-original',$(this).attr('src'))&&$(this).removeAttr("src")});

var show = avalon.define({
    $id        : "show",
    qrcode     : false,         //获取登录二维码返回的状态
    qrmsg      : '·······',
    login      : false,         //登录状态
    logout     : false,         //退出按钮是否显示
    cc         : 1,             //定时器ID
    ss         : 1,
    allList    : [],            //所有的用户列表
    nowId      : '',
    method     : 'all',         //群发的对象
    city       : [],            //城市
    oks        : 0,             //符合条件的数目
    result     : false,         //发送返回的结果
    checkData  : [],            //选择框选中的值
    checkAlls  : [],            //选择框所有的值
    allchecked : false,
    tml        : "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>",
    load       : false,
    onLoads    : function () {
        show.load = false;
    },
    checkAll: function () {
        if(show.allchecked){
            show.checkData = show.checkAlls;
        }else{
            show.checkData = [];
        }
    },
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
                    show.load   = true;
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
                for (var i=0;i<ret.data.length;i++){
                    show.checkAlls.push(ret.data[i].UserName.toString());
                }
                show.load    = false;
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
    },
    onSend   : function () {
        $('.send').modal('hide');
        show.ss = window.setInterval(show.toSend,2000);
    },
    toSend   : function () {
        $.ajax({
            url:'/admin/service/mass/send'
        }).done(function (ret) {
            if(!ret.success){
                show.result = false;
                window.clearInterval(show.ss);
            }else{
                show.result = ret.data;
            }
        });
    },
    //勾选后发送
    checkTo  : function () {
        if (show.checkData.length === 0){
            alert('请勾选下列要发送的对象！');
        }else{
            var data = { 'username' : show.checkData };
            $.ajax({
                url: '/admin/service/mass/condition',
                type: 'POST',
                data: data,
                // data: JSON.stringify(show.checkData),
                datatype: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function(ret){
                if(ret.success){
                    show.oks = ret.data;
                    if (show.oks === '0'){
                        alert('没有找到勾选的选项！');
                    }else{
                        $('.setmessage').modal('show');
                    }
                }else{
                    alert(ret.msg);
                }
            });
        }
    }
});
show.onQrcode();
show.cc = window.setInterval(show.onStatus,2000);
show.ss = window.setInterval(show.toSend,2000);
show.$watch("checkData.length",function () {
   if (show.checkData.length === show.checkAlls.length){
       show.allchecked = true;
   }else{
       show.allchecked = false;
   }
});


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
            if(ret.success){
                show.oks = ret.data;
                $('.setmessage').modal('hide');
                $('.send').modal('show');
            }else{
                $('#error-show').html(show.tml);
                $('#errinfo').text(ret.msg);
            }
        });

        $('form').bootstrapValidator('disableSubmitButtons', false);

    });
});
