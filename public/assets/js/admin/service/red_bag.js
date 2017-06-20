$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var red_bag = avalon.define({
    $id:'red_bag',
    url:'/admin/service/red_bag?',
    red_bag_data:[],
    pageBase:[],
    article:[],
    current_page:1,
    //获取我的监控的文章
    getData:function(){
        $.ajax({
            url:red_bag.url+'getType=article',
            success:function(data){
                red_bag.article = data;
            }
        })
    },
    //获取我添加的红包配置
    getRedBag:function(){
        $.ajax({
            url:red_bag.url+'&getType=redBag',
            success:function(data){
                red_bag.red_bag_data = data.data;
                red_bag.current_page = data.current_page;
                red_bag.pageBase = [];
                for(var i =1; i <= data.last_page; i++){
                    red_bag.pageBase.push(i);
                }
            }
        })
    },
    //跳转到某一页
    toPage:function(page){
        if(page <= red_bag.pageBase.length && page >0){
            red_bag.url = red_bag.url+'&getType=redBag&page='+page;
            red_bag.getRedBag();
        }
    },
    //红包类型 固定金额
    guding:function(){
        $("#suiji").attr('style','display:none');
        $("#guding").attr('style','display:block');
    },
    //红包类型 随机金额
    suiji:function(){
        $("#suiji").attr('style','display:block');
        $("#guding").attr('style','display:none');
    },
    //停止一个配置
    stop:function(id){
        if(confirm('确定要停止吗？')){
            $.ajax({
                url:'/admin/service/red_bag/'+id,
                type:'DELETE',
                success:function(data){
                    if(data.success){
                        alert('操作成功');
                        red_bag.getRedBag();
                    }
                }
            })
        }
    },
    //开启一个配置
    start:function(id){
        $.ajax({
            url:'/admin/service/red_bag/'+id,
            type:'PUT',
            success:function(data){
                if(data.success){
                    alert('操作成功');
                    red_bag.getRedBag();
                }
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
        event: {
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
                greaterThan: {
                    value:0,
                    inclusive: false,
                    message: '红包金额不能小于0'
                }
            }
        },
        money: {
            validators: {
                notEmpty: {
                    message: '输入单个红包金额'
                },
                lessThan: {
                    value: 200,
                    inclusive: true,
                    message: '单个红包金额最大不能大于200'
                },
                greaterThan: {
                    value:0,
                    inclusive: false,
                    message: '红包金额不能小于0'
                }
            }
        },
        money_suiji_begin: {
            validators: {
                notEmpty: {
                    message: '输入单个红包金额'
                },
                lessThan: {
                    value: 200,
                    inclusive: true,
                    message: '随机金额最大不能大于200'
                },
                greaterThan: {
                    value:0,
                    inclusive: false,
                    message: '红包金额不能小于0'
                }
            }
        },
        money_suiji_end: {
            validators: {
                notEmpty: {
                    message: '输入单个红包金额'
                },
                lessThan: {
                    value: 200,
                    inclusive: true,
                    message: '随机红包金额最大不能大于200'
                },
                greaterThan: {
                    value:0,
                    inclusive: false,
                    message: '红包金额不能小于0'
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
        action_form: {
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

    //检测随机红包金额 结束金额不能小于开始金额
    var taxonomy = $('input[name="taxonomy"]:checked').val();
    if(taxonomy == 2){
        if($('[name="money_suiji_end"]').val() <= $('[name="money_suiji_begin"]').val()){
            alert('随机红包金额输入有误！');
            return false;
        }else if($('[name="money_suiji_end"]').val() > $('[name="amount"]').val()){
            alert('随机红包不能大于总金额！');
            return false;
        }


    }

    //把奖励行为放在一个数据中 1，转发给好友/群 2，转发给朋友圈
    var action = [];
    $('input[name="action_form"]:checked').each(function(){
        action.push($(this).val());
    });

    var data = $('#addConfig').serialize();
    $.ajax({
        url: '/admin/service/red_bag?action='+action,
        type: 'POST',
        data: data,
        datatype: 'text',
        success:function(data){
            if(data.success){
                $("#myModal").modal('hide');
                $("#addConfig").bootstrapValidator('resetForm')[0].reset();
                alert('添加成功!');
                red_bag.getRedBag();
            }else{
                alert(data.msg);
                $('[type="submit"]').attr('disabled',false);
                return false;
            }
        }
    });
});

var myDate = new Date();
var startDate =  new Date().getMonth()+1 +'/' + myDate.getDate()  + '/' + myDate.getFullYear();
var endDate =  new Date().getMonth()+1 +'/' + new Date().getDate()  + '/' + myDate.getFullYear();

$('[name="begin_at"]').daterangepicker({
    "timePicker": true,
    "timePicker24Hour": true,
    "timePickerSeconds": true,
    "autoApply": true,
    "locale": {
        "direction": "ltr",
        "format": "MM/DD/YYYY HH:mm",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
            "日","一","二","三","四","五","六"
        ],
        "monthNames": [
            "1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"
        ],
        "firstDay": 1
    },
    "startDate": startDate,
    "endDate": endDate
}, function(start, end, label) {
    console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
});