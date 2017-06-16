var show = avalon.define({
    $id      : "show",
    data     : [],

    onData : function(){
        $.ajax({
            url:'/admin/task',
            success:function (ret) {
                show.data = ret.data;
                console.log(show.data);
            }
        });
    }
});

show.onData();
