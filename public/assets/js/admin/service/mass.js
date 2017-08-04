$('.lazy').each(function(){$(this).attr('data-original',$(this).attr('src'))&&$(this).removeAttr("src")});
//分页
function Pages(current,last){
    //times 循环的次数
    var times = last;
    //起始的页码数
    var star  = 1;
    var pages = [];
    if(times > 10){
        times = 10;
        if(current - 5 > 0){
            star = current - 4;
            if((star + times) >last){
                star = last-9;
            }
        }
    }
    for (var i =1;i<=times;i++){
        pages.push(star);
        star+=1;
    }
    return pages;
}
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
    tml        : "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>",
    load       : false,
    pages      : [],
    curr       : 0,
    last       : 0,
    total      : 0,
    visible    : 'checked',
    url        : '/admin/service/mass?all_list=1&page=1',
    //检测是否已经被选中了
    cad        : function (e) {
        var num =jQuery.inArray(e,show.checkData);
        if (num >= 0){
            return true;
        }else{
            return false;
        }
    },
    //点击勾选的，检查是否已经选过了，如果选过就删除，没有就添加
    orCheck    : function(e){
        var num =jQuery.inArray(e,show.checkData);
        if (num >= 0){
            show.checkData.splice($.inArray(e,show.checkData),1);
        }else{
            show.checkData.push(e);
        }
    },
    onLoads    : function () {

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
    //获取好友列表
    onAllList   : function (){
        $.ajax({
            url:show.url
        }).done(function (ret) {
            if(ret.success){
                show.nowId   = ret.id;
                show.allList = ret.data;
                show.pages   = Pages(ret.current_page, ret.last_page);
                show.curr    = ret.current_page;
                show.last    = ret.last_page;
                show.data    = ret.data;
                show.total   = ret.total;

                if (ret.data.length === 0) {
                    show.visible = true;
                } else {
                    show.visible = false;
                }
                show.load    = false;
            }
        });
    },
    toPage: function (e){
        var url  = show.url.substr(0, show.url.lastIndexOf('=') + 1);
        show.url = url + e;
        show.onAllList();
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
    },
    //群发全部
    onAll   : function(){
        $.ajax({
            url: '/admin/service/mass/condition?all=1',
            type: 'POST',
            data: {'all':1},
            datatype: 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(ret){
            if(ret.success){
                show.oks = ret.data;
                if (show.oks === '0'){
                    alert('获取全部出错！');
                }else{
                    $('.setmessage').modal('show');
                }
            }else{
                alert(ret.msg);
            }
        });
    }
});
show.onQrcode();
show.cc = window.setInterval(show.onStatus,2000);
show.ss = window.setInterval(show.toSend,2000);


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
