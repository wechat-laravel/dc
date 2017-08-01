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
        $('.ontask').modal('show');
    },
    onMethod  : function () {
        show.method = $('#method').val();
    }


});
show.onQrcode();
show.cc = window.setInterval(show.onStatus,2000);
