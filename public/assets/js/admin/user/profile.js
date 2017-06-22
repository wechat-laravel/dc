// 头像提交
// 提交
$('#avatar').click(function () {
    var src = $('#imgsrc')[0];
    if (src){
        $.ajax({
            url: '/admin/user/profile',
            type: 'POST',
            data: {
                'src' : src.src
            },
            headers: {
                'X-CSRF-TOKEN': $ ('meta[name="csrf-token"]').attr ('content')
            },
            dataType: 'text'
        }).done(function(ret){
            // window.location.reload();
            console.log(ret);
        });

    }else{
        $('#empty').show();
    }
});

var show = avalon.define({
    $id      : 'show',
    ava_err  : false,

    onAvatar:function () {
        var src = $('#imgsrc')[0];
        if (src){
            $.ajax({
                url: '/admin/user/profile',
                type: 'POST',
                data: {
                    'src' : src.src
                },
                headers: {
                    'X-CSRF-TOKEN': $ ('meta[name="csrf-token"]').attr ('content')
                },
                dataType: 'text'
            }).done(function(ret){
                window.location.reload();
            });
        }else{
            show.ava_err = true;
        }
    }

});