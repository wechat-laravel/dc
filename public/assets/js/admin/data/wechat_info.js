var show = avalon.define({
    $id        : "show",
    infos      : [],
    task_id    : $('input[name=task_id]').val(),
    people_id  : $('input[name=people_id]').val(),

    //用户来源
    onInfo  : function () {
        $.ajax({
            url:'/admin/data/wechat_info/'+show.task_id+'?people_id='+show.people_id,
            success:function (ret) {
                show.infos = ret.data;
            }
        })
    }
});

show.onInfo();