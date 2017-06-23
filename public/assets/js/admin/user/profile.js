var tml = "<div class='alert alert-danger alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><p id='errinfo'>123</p></div>";
$(function () {
    $('form').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();

        var data = $('.create.form').serialize();
        $.ajax({
            url: '/admin/user/profile',
            type: 'POST',
            data: data,
            datatype: 'text'
        }).done(function(ret){
            if (!ret.success){
                $('#error-show').html(tml);
                $('#errinfo').text(ret.msg);
            }else{
                window.location.reload();
            }
        });

        $('form').bootstrapValidator('disableSubmitButtons', false);

    });
});


var show = avalon.define({
    $id      : 'show',
    ava_err  : false,
    upload   : false,

    yulan    : function () {
        show.upload = true;
    },
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