// 基于准备好的dom，初始化echarts实例
var puf  = echarts.init(document.getElementById('puf'));
var cbcj = echarts.init(document.getElementById('cbcj'));
var tlsc = echarts.init(document.getElementById('tlsc'));
var fwsj = echarts.init(document.getElementById('fwsj'));
var wxly = echarts.init(document.getElementById('wxly'));
var fxqx = echarts.init(document.getElementById('fxqx'));

// 走势图
var puf_hour = {
    tooltip: {
        trigger: 'axis'
    },
    color:['#0073B7','#00A65A','#F39C12'],
    legend: {
        left:'left',
        data:['PV','UV','分享']
    },
    toolbox: {
        show: true,
        feature: {
            saveAsImage: {}
        }
    },
    dataZoom: [
        {
            type: 'slider',
            start: 0,
            end: 100
        }
    ],
    calculable : true,
    xAxis:  {
        type: 'category',
        boundaryGap: false,
        data: ['5.18','5.19','5.20','5.21','5.22','5.23','5.24']
    },
    yAxis: {
        type: 'value',
        axisLabel: {
            formatter: '{value}'
        }
    },
    series: [
        {
            name:'PV',
            type:'line',
            data:[10, 39, 50, 30, 21,20,8],
            smooth:true,
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            }
        },
        {
            name:'UV',
            type:'line',
            smooth:true,
            data:[15, 27, 24, 31, 18, 12, 66],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            }
        },
        {
            name:'分享',
            type:'line',
            smooth:true,
            data:[31, 45, 40, 28, 11, 8, 13],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            }
        }
    ]
};

var puf_day = {
    tooltip: {
        trigger: 'axis'
    },
    color:['#0073B7','#00A65A','#F39C12'],
    legend: {
        left:'left',
        data:['PV','UV','分享']
    },
    toolbox: {
        show: true,
        feature: {
            saveAsImage: {}
        }
    },
    dataZoom: [
        {
            type: 'slider',
            start: 0,
            end: 100
        }
    ],
    calculable : true,
    xAxis:  {
        type: 'category',
        boundaryGap: false,
        data: ['5.18','5.19','5.20','5.21','5.22','5.23','5.24']
    },
    yAxis: {
        type: 'value',
        axisLabel: {
            formatter: '{value}'
        }
    },
    series: [
        {
            name:'PV',
            type:'line',
            data:[10, 39, 50, 30, 21,20,8],
            smooth:true,
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            }
        },
        {
            name:'UV',
            type:'line',
            smooth:true,
            data:[15, 27, 24, 31, 18, 12, 66],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            }
        },
        {
            name:'分享',
            type:'line',
            smooth:true,
            data:[31, 45, 40, 28, 11, 8, 13],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            }
        }
    ]
};

var cbcj_data = {
    tooltip: {
        trigger: 'axis',
        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
        }
    },
    color:['#0073B7','#00A65A','#DD4B39'],
    legend: {
        left:'left',
        data:['PV','UV','分享']
    },
    toolbox: {
        show: true,
        feature: {
            saveAsImage: {}
        }
    },
    dataZoom: [
        {
            type: 'slider',
            start: 0,
            end: 100
        }
    ],
    calculable : true,
    xAxis:  {
        type: 'category',
        data: ['第一层','第二层','第三层','第四层','第五层','第六层','第七层','第八层','第九层','第十层']
    },
    yAxis: {
        type: 'value'
    },
    series: [
        {
            name:'PV',
            type:'bar',
            data:[10, 39, 52, 30, 21,20,8],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            }
        },
        {
            name:'UV',
            type:'bar',
            data:[15, 27, 24, 31, 18, 12, 71],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            }
        },
        {
            name:'分享',
            type:'bar',
            data:[31, 54, 40, 28, 11, 8, 13],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            }
        }
    ]
};

var tlsc_data = {
    title: {
        text : '单位 %',
        textStyle :{
            fontSize : 11
        }
    },
    tooltip: {
        trigger: 'axis',
        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
        }
    },
    color:['#70739E','#97709D'],
    legend: {
        left:'15%',
        data:['本H5','Top100 H5']
    },
    toolbox: {
        show: true,
        feature: {
            saveAsImage: {}
        }
    },
    dataZoom: [
        {
            type: 'slider',
            start: 0,
            end: 100
        }
    ],
    calculable : true,
    xAxis:  {
        type: 'category',
        data: ['0-5s','6-10s','11-20s','21-40s','41-80s','80-160s','161-320s','321-640s','641-1280s','1280s以上']
    },
    yAxis: {
        type: 'value'
    },
    series: [
        {
            name:'本H5',
            type:'bar',
            data:[10, 39, 52, 30, 21,20,8],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            },
            markLine: {
                data: [
                    {type: 'average', name: '平均值'}
                ]
            }
        },
        {
            name:'Top100 H5',
            type:'bar',
            data:[8, 15, 27,19,11, 9,8,2,1,1],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            },
            markLine: {
                data: [
                    {type: 'average', name: '平均值'}
                ]
            }
        }
    ]
};

var fwsj_data = {
    title: {
        text : '单位 %',
        textStyle :{
            fontSize : 11
        }
    },
    tooltip: {
        trigger: 'axis'
    },
    color:['#282828','#D66360'],
    legend: {
        left:'15%',
        data:['本H5','Top100 H5']
    },
    toolbox: {
        show: true,
        feature: {
            saveAsImage: {}
        }
    },
    dataZoom: [
        {
            type: 'slider',
            start: 0,
            end: 100
        }
    ],
    calculable : true,
    xAxis:  {
        type: 'category',
        boundaryGap: false,
        data: ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23']
    },
    yAxis: {
        type: 'value',
        axisLabel: {
            formatter: '{value}'
        }
    },
    series: [
        {
            name:'本H5',
            type:'line',
            data:[10, 39, 50, 30, 21,20,8,10, 39, 50, 30, 21,20,8,10, 39, 50, 30, 21,20,8,10,39,50],
            smooth:true,
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            }
        },
        {
            name:'Top100 H5',
            type:'line',
            smooth:true,
            data:[6,3,2,1,0,1,4,7,6,10,12,14,11,13,8,11,6,20,15,13,10,18,16,11,9],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'}
                ]
            }
        }
    ]
};
var wxly_data = {
    color:['#34A853','#4285F4','#FBBC05','#EA4335','#2E3092'],
    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        orient: 'vertical',
        left: 'left',
        data: ['微信好友','QQ好友','朋友圈','微信群','QQ空间']
    },
    series : [
        {
            name: '访问来源',
            type: 'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:1548, name:'微信好友'},
                {value:310, name:'QQ好友'},
                {value:234, name:'朋友圈'},
                {value:135, name:'微信群'},
                {value:122, name:'QQ空间'}
            ],
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
};

var fxqx_data = {
    color:['#4AACC5','#4F81BC','#BF4F4D','#9BBB58','#81629D'],
    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        orient: 'vertical',
        left: 'left',
        data: ['微信好友','QQ好友','朋友圈','微信群','QQ空间']
    },
    series : [
        {
            name: '分享去向',
            type: 'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:1548, name:'微信好友'},
                {value:230, name:'QQ好友'},
                {value:800, name:'朋友圈'},
                {value:556, name:'微信群'},
                {value:122, name:'QQ空间'}
            ],
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
};

var show = avalon.define({
    $id   : "show",
    top   : [],
    onPUF : function(res){
        // 使用刚指定的配置项和数据显示图表。
        if (res === 'hour'){
            puf.setOption(puf_hour);
        }else{
            puf.setOption(puf_day);
        }
    },
    onData : function(){
        var task_id = $('input[name=task_id]').val();
        $.ajax({
            url:'/admin/data/wechat/'+task_id,
            success:function (res) {
                show.top = res.top;
                puf_hour.xAxis.data      = res.top.current.day;
                puf_hour.series[0].data  = res.top.current.pv;
                puf_hour.series[1].data  = res.top.current.uv;
                puf_hour.series[2].data  = res.top.current.share;
                puf_day.series[0].data   = res.top.pv_everyday;
                puf_day.series[1].data   = res.top.uv_everyday;
                puf_day.series[2].data   = res.top.share_everyday;
                puf_day.xAxis.data       = res.top.days;
                cbcj_data.series[0].data = res.top.level.pv;
                cbcj_data.series[1].data = res.top.level.uv;
                cbcj_data.series[2].data = res.top.level.share;
                fwsj_data.series[0].data = res.top.visit.this;
                tlsc_data.series[0].data = res.top.stay.this;
                wxly_data.series[0].data = res.top.browse;
                fxqx_data.series[0].data = res.top.action;
                puf.setOption(puf_hour);
                puf.setOption(puf_day);
                cbcj.setOption(cbcj_data);
                fwsj.setOption(fwsj_data);
                tlsc.setOption(tlsc_data);
                wxly.setOption(wxly_data);
                fxqx.setOption(fxqx_data);
            }
        });
    }
});

show.onData();