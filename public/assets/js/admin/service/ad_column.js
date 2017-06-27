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

var tml = "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>";
var edi = "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='ediinfo'>123</p></div>";
$(function () {
    $('.create.form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '模板名字不能为空'
                    },
                    stringLength :{
                        max    : 100,
                        message: '长度请保持在20位以内'
                    }
                }
            },
            title: {
                validators: {
                    notEmpty: {
                        message: '广告标题不能为空'
                    },
                    stringLength :{
                        max    : 10,
                        message: '长度请保持在20位以内'
                    }
                }
            },
            url: {
                validators: {
                    notEmpty: {
                        message: '广告跳转链接不能为空'
                    },
                    stringLength :{
                        max    : 200,
                        message: '字符数不能超过200'
                    }

                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        var data = new FormData($('#create')[0]);
        $.ajax({
            url: '/admin/service/ad_column?mark=create',
            type: 'POST',
            data: data,
            dataType: 'JSON',
            cache: false,
            processData: false,
            contentType: false
        }).done(function(ret){
            if (!ret.success){
                $('#error-show').html(tml);
                $('#errinfo').text(ret.msg);
            }else{
                window.location.reload();
            }
            $('form').bootstrapValidator('disableSubmitButtons', false);
        });
    });

    $('.edit.form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '模板名字不能为空'
                    },
                    stringLength :{
                        max    : 100,
                        message: '长度请保持在20位以内'
                    }
                }
            },
            title: {
                validators: {
                    notEmpty: {
                        message: '广告标题不能为空'
                    },
                    stringLength :{
                        max    : 10,
                        message: '长度请保持在20位以内'
                    }
                }
            },
            url: {
                validators: {
                    notEmpty: {
                        message: '广告跳转链接不能为空'
                    },
                    stringLength :{
                        max    : 200,
                        message: '字符数不能超过200'
                    }

                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        var data = new FormData($('#edit')[0]);
        $.ajax({
            url: '/admin/service/ad_column?mark=edit',
            type: 'POST',
            data: data,
            dataType: 'JSON',
            cache: false,
            processData: false,
            contentType: false
        }).done(function(ret){
            if (!ret.success){
                $('#error-edit').html(edi);
                $('#ediinfo').text(ret.msg);
            }else{
                window.location.reload();
            }
            $('form').bootstrapValidator('disableSubmitButtons', false);
        });
    });
});

var show = avalon.define({
    $id      : "show",
    data     : [],
    url      : "",              //储存当前url
    pages    : [],              //储存要展示的页数
    curr     : 0,               //当前的页码
    last     : 0,               //最后一页的页码
    total    : 0,               //所有的条数
    visible  : false,           //默认不显示（没有数据的提示）
    ad       : [],

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
        show.url = '/admin/service/ad_column?screen=1&page=1';
        show.getData();
    },
    toPage: function (e){
        var url  = show.url.substr(0, show.url.lastIndexOf('=') + 1);
        show.url = url + e;
        show.getData();
    },
    onEdit : function (e) {
        $.ajax({
            url:'/admin/service/ad_column/'+e+'/edit',
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function (ret) {
                if(ret.success){
                    show.ad = ret.ad;
                    $("#editModal").modal('show');
                }else{
                    alert(ret.msg);
                }
            }
        });
    }

});


show.onCurrentTab();