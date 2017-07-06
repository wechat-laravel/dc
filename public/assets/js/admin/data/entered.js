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
    $id      : "show",
    data     : [],
    url      : "",              //储存当前url
    pages    : [],              //储存要展示的页数
    curr     : 0,               //当前的页码
    last     : 0,               //最后一页的页码
    total    : 0,               //所有的条数
    visible  : false,           //默认不显示（没有数据的提示）
    user     : [],
    is_user  : false,
    task_id  : $('input[name=task_id]').val(),
    openid   : '',

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
        show.url = '/admin/data/entered/'+show.task_id+'?screen=1&page=1';
        show.getData();
    },
    toPage: function (e){
        var url  = show.url.substr(0, show.url.lastIndexOf('=') + 1);
        show.url = url + e;
        show.getData();
    },
    onRemark:function(e){
        show.openid = e;
        $.ajax({
            url: '/admin/user/remark/'+e,
            method: 'GET',
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(ret){
            if(ret.success){
                show.is_user = true;
                show.user = ret.data;
            }else{
                show.is_user = false;
                show.user  = [];
            }
            $('.modal.fade.user.remark').modal('show');
        });
    }

});

show.onCurrentTab();

var rtml = "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='rerrinfo'>123</p></div>";

$(function () {
    // 备注
    $('.remark.form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: '姓名必填！'
                    },
                    stringLength :{
                        max    : 20,
                        message: '长度请保持在20位以内'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        var data = new FormData($('#remark')[0]);
        $.ajax({
            url: '/admin/user/remark',
            type: 'POST',
            data: data,
            dataType: 'JSON',
            cache: false,
            processData: false,
            contentType: false
        }).done(function(ret){
            if (!ret.success){
                $('#rerror-show').html(rtml);
                $('#rerrinfo').text(ret.msg);
            }else{
                window.location.reload();
            }
            $('form').bootstrapValidator('disableSubmitButtons', false);
        });
    });
});