var show = avalon.define({
    $id      : "show",
    data     : [],

    onLoads  : function () {
        $('.qrcode').popover();
    },

    onData : function(){
        $.ajax({
            url:'/admin/task',
            success:function (ret) {
                if (ret.data){
                    show.data = ret.data;
                }
            }
        });
    }

});

show.onData();
$(function () {
    $('.qrcode').popover();
});