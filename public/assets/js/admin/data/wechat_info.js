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
    infos      : [],
    task_id    : $('input[name=task_id]').val(),
    people_id  : $('input[name=people_id]').val(),
    openid     : $('input[name=openid]').val(),
    data       : [],
    rdata      : [],
    url        : "",              //储存当前url
    rurl       : "",              //储存当前url
    pages      : [],              //储存要展示的页数
    rpages     : [],              //储存要展示的页数
    curr       : 0,               //当前的页码
    rcurr      : 0,               //当前的页码
    last       : 0,               //最后一页的页码
    rlast      : 0,               //最后一页的页码
    total      : 0,               //所有的条数
    rtotal     : 0,               //所有的条数
    visible    : false,           //默认不显示（没有数据的提示）
    rvisible   : false,           //默认不显示（没有数据的提示）

    //用户来源
    onInfo  : function () {
        $.ajax({
            url:'/admin/data/wechat_info/'+show.task_id+'?people_id='+show.people_id+'&openid='+show.openid,
            success:function (ret) {
                show.infos = ret.data;
            }
        })
    },
    onLoads  : function () {

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
    //当前Tab
    onCurrentTab: function () {
        show.url = '/admin/data/wechat_more/'+show.openid+'?screen=1&page=1';
        show.getData();
    },
    toPage: function (e){
        var url  = show.url.substr(0, show.url.lastIndexOf('=') + 1);
        show.url = url + e;
        show.getData();
    },
    rgetData : function () {
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
    rtoPage: function (e){
        var url  = show.rurl.substr(0, show.rurl.lastIndexOf('=') + 1);
        show.rurl = url + e;
        show.rgetData();
    },
    //当前Tab
    onReward: function () {
        show.rurl = '/admin/service/red_reward/'+show.openid+'?screen=1&page=1';
        show.rgetData();
    }
});

show.onInfo();
show.onCurrentTab();
show.onReward();

var tml = "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>";
$(function () {
    $('.create.form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            send_name: {
                validators: {
                    notEmpty: {
                        message: '红包发送者名称不能为空'
                    },
                    stringLength :{
                        max    : 10,
                        message: '长度请保持在10位以内'
                    }
                }
            },
            wishing: {
                validators: {
                    notEmpty: {
                        message: '红包祝福语不能为空'
                    },
                    stringLength :{
                        max    : 10,
                        message: '长度请保持在10位以内'
                    }
                }
            },
            act_name: {
                validators: {
                    notEmpty: {
                        message: '红包活动名称不能为空'
                    },
                    stringLength :{
                        max    : 10,
                        message: '长度请保持在10位以内'
                    }
                }
            },
            money :{
                validators: {
                    notEmpty: {
                        message: '发送金额不能为空'
                    },
                    between :{
                        min    : 1,
                        max    : 200,
                        message: '红包金额的范围为1-200'
                    }
                }
            },
            remark: {
                validators: {
                    stringLength :{
                        max    : 10,
                        message: '长度请保持在200位以内'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        var data = new FormData($('#create')[0]);
        $.ajax({
            url: '/admin/service/red_reward',
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
                $('.modal.fade.red.reward').modal('hide');
                show.onReward();
            }
            $('form').bootstrapValidator('disableSubmitButtons', false);
        });
    });
});