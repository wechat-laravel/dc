$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var red_bag = avalon.define({
    $id:'red_bag',
    url:'/admin/service/red_bag',
    data:[],
    getData:function(){
        $.ajax({
            url:red_bag.url,
            success:function(data){
                red_bag.data=data.data;
            }
        })
    }
});

$('form').bootstrapValidator({
    message: 'This value is not valid',
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {
        title: {
            validators: {
                notEmpty: {
                    message: '活动名称不能为空'
                }
            }
        },
        article_id: {
            validators: {
                notEmpty: {
                    message: '选择营销内容'
                }
            }
        },
        amount: {
            validators: {
                notEmpty: {
                    message: '总金额不能为空'
                },
                regexp: {
                    regexp: /^[0-9]+$/,
                    message: '总金额只能为数字'
                }
            }
        },
        money: {
            validators: {
                notEmpty: {
                    message: '输入单个红包金额'
                },
                regexp: {
                    regexp: /^[0-9]+[-][0-9]+$|^[0-9]{3}$/,
                    message: '红包金额格式不正确'
                }
            }
        },
        begin_at: {
            validators: {
                notEmpty: {
                    message: '红包发放时间不能为空'
                }
            }
        },
        action: {
            validators: {
                notEmpty: {
                    message: '请选择奖励行为'
                }
            }
        },
        send_name: {
            validators: {
                notEmpty: {
                    message: '红包发送者名称不能为空'
                }
            }
        },
        wishing: {
            validators: {
                notEmpty: {
                    message: '祝福语不能为空'
                }
            }
        },
        act_name: {
            validators: {
                notEmpty: {
                    message: '活动名称不能为空'
                }
            }
        }
    }
}).on('success.form.bv', function(e) {
    e.preventDefault();

    var data = $('#addConfig').serialize();
    $.ajax({
        url: '/admin/service/red_bag',
        type: 'POST',
        data: data,
        datatype: 'text',
        success:function(data){
            console.log(data);
            /*if(data.success){
                alert(data.msg);
            }*/
        }
    });
});

$('[name="begin_at"]').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});