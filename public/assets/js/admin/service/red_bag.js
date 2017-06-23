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
    province:[],
    city:[],
    article_id:0,
    redId:0,
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
        $(".suiji").attr('style','display:none');
        $(".guding").attr('style','display:block');
    },
    //红包类型 随机金额
    suiji:function(){
        $(".suiji").attr('style','display:block');
        $(".guding").attr('style','display:none');
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
    },
    tasks:function(id){
        red_log.tasks_id = id;
        red_log.getData();
    },
    //获取省份列表
    area:function(id){
        if(id == 1){
            $(".area").attr('style','display:block');
            $.ajax({
                type:'get',
                url:'/admin/service/red_bag?&getType=province',
                success:function(data){
                    red_bag.province = data.data;
                }
            });
            $.ajax({
                type:'get',
                url:'/admin/service/red_bag?&getType=city&prov_id=1',
                success:function(data){
                    red_bag.city = data.data;
                }
            });
        }
        else{
            $(".area").attr('style','display:none');
        }
    },
    //充值
    chongzhi:function(data_id,id){
        red_bag.article_id = data_id;
        red_bag.redId = id;
    },
    //提交充值
    chongzhiCommit:function(){
        if($('[name="total"]').val() <= 0){
            alert('充值金额不能小于0');
            return false;
        }else{
            $.ajax({
                url:'/admin/service/red_bag?&getType=chongzhiCommit&id='+red_bag.redId+'&total='+$('[name="total"]').val(),
                success:function(data){
                    if(data.success){
                        alert(data.msg);
                        red_bag.getRedBag();
                        $("#chongzhiModal").modal('hide');
                        $('[name="total"]').val('');
                    }else{
                        alert(data.msg);
                    }
                }
            });
        }
    },
    //修改配置
    editConfig:function(indexId,id){
        red_bag.article_id = indexId;
        red_bag.redId = id;
    }
});

var red_log = avalon.define({
    $id:'red_log',
    url:'/admin/service/red_log?',
    data:[],
    pageBase:[],
    current_page:1,
    tasks_id:'1',
    //获取红包领取日志
    getData:function(){
        $.ajax({
            url:red_log.url+'&tasks_id='+red_log.tasks_id,
            success:function(data){
                red_log.data = data.data;
                red_log.current_page = data.current_page;
                red_log.pageBase = [];
                for(var i =1; i <= data.last_page; i++){
                    red_log.pageBase.push(i);
                }
            }
        })
    },
    //跳转到某一页
    toPage:function(page){
        if(page <= red_log.pageBase.length && page >0){
            red_log.url = red_log.url+'&page='+page;
            red_log.getData();
        }
    }
});

//添加配置的表单验证
$('#addConfig').bootstrapValidator({
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
                },
                stringLength: {
                    min: 1,
                    max: 30,
                    message: '红包发送者名称最大为30个字符'
                }
            }
        },
        wishing: {
            validators: {
                notEmpty: {
                    message: '祝福语不能为空'
                },
                stringLength: {
                    min: 1,
                    max: 128,
                    message: '红包祝福语最大为128个字符'
                }
            }
        },
        act_name: {
            validators: {
                notEmpty: {
                    message: '活动名称不能为空'
                },
                stringLength: {
                    min: 1,
                    max: 30,
                    message: '活动名称为1-30个字符'
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
var endend = new Date().getDate()+7;
var startDate =  new Date().getMonth()+1 +'/' + myDate.getDate()  + '/' + myDate.getFullYear();
var endDate =  new Date().getMonth()+1 +'/' + endend  + '/' + myDate.getFullYear();

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


//通过省份获取城市
$(".province").on("change",function(){
    $.ajax({
     type:'get',
     url:'/admin/service/red_bag?&getType=city&prov_id='+$("option:selected",this).val(),
     success:function(data){
        red_bag.city = data.data;
     }
     });
});

//修改配置的表单验证
$('#editConfigModal').bootstrapValidator({
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
        edit_money_suiji_begin: {
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
        edit_money_suiji_end: {
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
        edit_action_form: {
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
                },
                stringLength: {
                    min: 1,
                    max: 30,
                    message: '红包发送者名称最大为30个字符'
                }
            }
        },
        wishing: {
            validators: {
                notEmpty: {
                    message: '祝福语不能为空'
                },
                stringLength: {
                    min: 1,
                    max: 128,
                    message: '红包祝福语最大为128个字符'
                }
            }
        },
        act_name: {
            validators: {
                notEmpty: {
                    message: '活动名称不能为空'
                },
                stringLength: {
                    min: 1,
                    max: 30,
                    message: '活动名称为1-30个字符'
                }
            }
        }
    }
}).on('success.form.bv', function(e) {
    e.preventDefault();

    //检测随机红包金额 结束金额不能小于开始金额
    var taxonomy = $('input[name="edit_taxonomy"]:checked').val();
    if(taxonomy == 2){
        if($('[name="edit_money_suiji_end"]').val() <= $('[name="edit_money_suiji_begin"]').val()){
            alert('随机红包金额输入有误！');
            return false;
        }else if($('[name="edit_money_suiji_end"]').val() > red_bag.red_bag_data[red_bag.article_id].amount){
            alert('随机红包不能大于余额！');
            return false;
        }
    }

    //把奖励行为放在一个数据中 1，转发给好友/群 2，转发给朋友圈
    var action = [];
    $('input[name="edit_action_form"]:checked').each(function(){
        action.push($(this).val());
    });

    var data = $('#editConfig').serialize();
    $.ajax({
        url: '/admin/service/red_bag/editConfig?action='+action+'&id='+red_bag.redId,
        type: 'PUT',
        data: data,
        datatype: 'text',
        success:function(data){
            if(data.success){
                $("#editConfigModal").modal('hide');
                //$("#editConfig").bootstrapValidator('resetForm')[0].reset();
                alert('修改成功!');
                red_bag.getRedBag();
            }else{
                alert(data.msg);
                $('[type="submit"]').attr('disabled',false);
                return false;
            }
        }
    });
});