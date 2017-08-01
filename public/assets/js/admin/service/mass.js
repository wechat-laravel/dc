var show = avalon.define({
    $id        : "show",
    qrcode     : false,         //获取登录二维码返回的状态
    qrmsg      : '·······',
    logout     : false,         //退出按钮是否显示
    cc         : 1,             //定时器ID
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
    onStatus   : function (){
        $.ajax({
            url:'/admin/service/mass?status=1'
        }).done(function (ret) {
            if(ret.success){
                //表示已登录了，就停止定时器
                if(ret.msg === 'true'){
                    show.qrmsg  = '已登录';
                    window.clearInterval(show.cc);
                }
            }
        });
    }

});
show.onQrcode();
show.cc = window.setInterval(show.onStatus,2000);
