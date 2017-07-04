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
    data       : [],
    rdata      : [],
    url        : "",              //储存当前url
    rurl       : "",              //红包信息的url
    pages      : [],              //储存要展示的页数
    rpages     : [],              //储存要展示的红包信息页数
    curr       : 0,               //当前的页码
    rcurr      : 0,               //红包当前的页码
    last       : 0,               //最后一页的页码
    rlast      : 0,               //红包的
    total      : 0,               //所有的条数
    rtotal     : 0,               //红包的
    visible    : false,           //默认不显示（没有数据的提示）
    rvisible   : false,           //红包的
    task_id    : 0,

    onLoads  : function () {
        $('.desc').popover();
        $('.qrcode').popover();
    },
    getData : function(){
        $.ajax({
            url: show.url,
            method: 'GET',
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(data){
            show.pages   = Pages(data.current_page, data.last_page);
            show.curr    = data.current_page;
            show.last    = data.last_page;
            show.data    = data.data;
            show.total   = data.total;
            if (data.data.length === 0) {
                show.visible = true;
            } else {
                show.visible = false;
            }
        });
    },
    getDatas : function(){
        $.ajax({
            url: show.rurl,
            method: 'GET',
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(data){
            show.rpages   = Pages(data.current_page, data.last_page);
            show.rcurr    = data.current_page;
            show.rlast    = data.last_page;
            show.rdata    = data.data;
            show.rtotal   = data.total;
            if (data.data.length === 0) {
                show.rvisible = true;
            } else {
                show.rvisible = false;
            }
        });
    },
    //当前Tab
    onCurrentTab: function () {
        show.url = '/admin/task?screen=1&page=1';
        show.getData();
    },
    toPage: function (e){
        var url  = show.url.substr(0, show.url.lastIndexOf('=') + 1);
        show.url = url + e;
        show.getData();
    },
    toPages: function (e){
        var url  = show.rurl.substr(0, show.rurl.lastIndexOf('=') + 1);
        show.rurl = url + e;
        show.getDatas();
    },
    onConfirm:function (e) {
        show.task_id = e;
        $('.bs-delete-modal-sm').modal('show');
    },
    onDelete:function () {
        $('.bs-delete-modal-sm').modal('hide');
        $.ajax({
            url:'/admin/task/'+show.task_id,
            method: 'DELETE',
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function (ret) {
            if(ret.success){
                $('#infos').text(ret.msg);
                show.getData();
            }else{
                $('#infos').text(ret.msg);
            }
        });
        $('.bs-result-modal-sm').modal('show');
    },
    onRed :function (id) {
        show.rurl = '/admin/service/red_log?tasks_id='+id+'&page=1';
        show.getDatas();
        $('.red.bag').modal('show');
    }
    

});
show.onCurrentTab();

$(function () {
    $('.qrcode').popover();
    $('.desc').popover();
});