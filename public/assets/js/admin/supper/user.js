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
    user_id  : 0,
    email    : '',
    mark     : '',
    load     : true,
    vipshow  : false,
    onLoads  : function () {
        show.load = false;
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
    onSelect : function(){
        var  ic = $('#identity').val();
        if (ic === 'vip'){
            show.vipshow = true;
        }
    },
    //当前Tab
    onCurrentTab: function () {
        show.url = '/admin/supper/user?screen=1&page=1';
        show.getData();
    },
    toPage: function (e){
        var url  = show.url.substr(0, show.url.lastIndexOf('=') + 1);
        show.url = url + e;
        show.getData();
    },
    editMark:function (id,email,mark) {
        show.id = id;
        show.email = email;
        show.mark = mark;
        if (mark === 'vip'){
            show.vipshow = true;
        }else{
            show.vipshow = false;
        }
        $(".bs-example-modal-sm").modal('show');
    }

});


show.onCurrentTab();
var tml = "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>";
$('.edit.form').bootstrapValidator({
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    }
}).on('success.form.bv', function(e) {
    e.preventDefault();
    var data = $('.edit.form').serialize();
    $.ajax({
        url: '/admin/supper/user',
        type: 'POST',
        data: data,
        dataType: 'JSON'
    }).done(function(ret){
        if (!ret.success){
            $('#error-show').html(tml);
            $('#errinfo').text(ret.msg);
        }else{
            $(".bs-example-modal-sm").modal('hide');
            $('.edit.form')[0].reset();
            window.location.reload();
        }
        $('form').bootstrapValidator('disableSubmitButtons', false);
    });
});
//搜索
$('.search').bootstrapValidator({
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    }
}).on('success.form.bv', function(e) {
    e.preventDefault();
    var url = '/admin/supper/user?screen=1';
    if($("#user_mark").val()){
        url = url + '&identity=' + $("#user_mark").val();
    }
    if($("input[name='user_email']").val()){
        url = url + '&email=' + $("input[name='user_email']").val();
    }
    url = url + '&page=1';
    show.url = url;
    show.getData();
    $('form').bootstrapValidator('disableSubmitButtons', false);
});