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
    calculable : true,
    xAxis:  {
        type: 'time',
        splitLine: {
            show: false
        }
    },
    yAxis: {
        type: 'value'
    },
    dataZoom: [
        {
            type: 'slider',
            start: 0,
            end: 100
        }
    ],
    series: [
        {
            name:'PV',
            type:'line',
            data:[
                {name:'2016/12/18 00:00', value:['2016/12/18 00:00', 11]},
                {name:'2016/12/18 01:00', value:['2016/12/18 01:00', 22]},
                {name:'2016/12/18 02:00', value:['2016/12/18 02:00', 35]},
                {name:'2016/12/18 03:00', value:['2016/12/18 03:00', 41]},
                {name:'2016/12/18 04:00', value:['2016/12/18 04:00', 60]},
                {name:'2016/12/18 05:00', value:['2016/12/18 05:00', 75]},
                {name:'2016/12/18 06:00', value:['2016/12/18 06:00', 80]},
                {name:'2016/12/18 07:00', value:['2016/12/18 07:00', 60]},
                {name:'2016/12/18 08:00', value:['2016/12/18 08:00', 50]},
                {name:'2016/12/18 09:00', value:['2016/12/18 09:00', 35]},
                {name:'2016/12/18 10:00', value:['2016/12/18 10:00', 30]},
                {name:'2016/12/18 11:00', value:['2016/12/18 11:00', 25]},
                {name:'2016/12/18 12:00', value:['2016/12/18 12:00', 51]},
                {name:'2016/12/18 13:00', value:['2016/12/18 13:00', 66]},
                {name:'2016/12/18 14:00', value:['2016/12/18 14:00', 77]},
                {name:'2016/12/18 15:00', value:['2016/12/18 15:00', 81]},
                {name:'2016/12/18 16:00', value:['2016/12/18 16:00', 73]},
                {name:'2016/12/18 17:00', value:['2016/12/18 17:00', 66]},
                {name:'2016/12/18 18:00', value:['2016/12/18 18:00', 52]},
                {name:'2016/12/18 19:00', value:['2016/12/18 19:00', 37]},
                {name:'2016/12/18 20:00', value:['2016/12/18 20:00', 31]},
                {name:'2016/12/18 21:00', value:['2016/12/18 21:00', 30]},
                {name:'2016/12/18 22:00', value:['2016/12/18 22:00', 20]},
                {name:'2016/12/18 23:00', value:['2016/12/18 23:00', 11]}
            ],
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
            data:[
                {name:'2016/12/18 00:00', value:['2016/12/18 00:00', 2]},
                {name:'2016/12/18 01:00', value:['2016/12/18 01:00', 8]},
                {name:'2016/12/18 02:00', value:['2016/12/18 02:00', 16]},
                {name:'2016/12/18 03:00', value:['2016/12/18 03:00', 20]},
                {name:'2016/12/18 04:00', value:['2016/12/18 04:00', 25]},
                {name:'2016/12/18 05:00', value:['2016/12/18 05:00', 28]},
                {name:'2016/12/18 06:00', value:['2016/12/18 06:00', 29]},
                {name:'2016/12/18 07:00', value:['2016/12/18 07:00', 30]},
                {name:'2016/12/18 08:00', value:['2016/12/18 08:00', 38]},
                {name:'2016/12/18 09:00', value:['2016/12/18 09:00', 40]},
                {name:'2016/12/18 10:00', value:['2016/12/18 10:00', 42]},
                {name:'2016/12/18 11:00', value:['2016/12/18 11:00', 46]},
                {name:'2016/12/18 12:00', value:['2016/12/18 12:00', 57]},
                {name:'2016/12/18 13:00', value:['2016/12/18 13:00', 60]},
                {name:'2016/12/18 14:00', value:['2016/12/18 14:00', 62]},
                {name:'2016/12/18 15:00', value:['2016/12/18 15:00', 67]},
                {name:'2016/12/18 16:00', value:['2016/12/18 16:00', 75]},
                {name:'2016/12/18 17:00', value:['2016/12/18 17:00', 78]},
                {name:'2016/12/18 18:00', value:['2016/12/18 18:00', 81]},
                {name:'2016/12/18 19:00', value:['2016/12/18 19:00', 88]},
                {name:'2016/12/18 20:00', value:['2016/12/18 20:00', 90]},
                {name:'2016/12/18 21:00', value:['2016/12/18 21:00', 95]},
                {name:'2016/12/18 22:00', value:['2016/12/18 22:00', 90]},
                {name:'2016/12/18 23:00', value:['2016/12/18 23:00', 92]}
            ],
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
            data:[
                {name:'2016/12/18 00:00', value:['2016/12/18 00:00', 0]},
                {name:'2016/12/18 01:00', value:['2016/12/18 01:00', 2]},
                {name:'2016/12/18 02:00', value:['2016/12/18 02:00', 9]},
                {name:'2016/12/18 03:00', value:['2016/12/18 03:00', 11]},
                {name:'2016/12/18 04:00', value:['2016/12/18 04:00', 16]},
                {name:'2016/12/18 05:00', value:['2016/12/18 05:00', 21]},
                {name:'2016/12/18 06:00', value:['2016/12/18 06:00', 22]},
                {name:'2016/12/18 07:00', value:['2016/12/18 07:00', 24]},
                {name:'2016/12/18 08:00', value:['2016/12/18 08:00', 27]},
                {name:'2016/12/18 09:00', value:['2016/12/18 09:00', 29]},
                {name:'2016/12/18 10:00', value:['2016/12/18 10:00', 33]},
                {name:'2016/12/18 11:00', value:['2016/12/18 11:00', 35]},
                {name:'2016/12/18 12:00', value:['2016/12/18 12:00', 36]},
                {name:'2016/12/18 13:00', value:['2016/12/18 13:00', 39]},
                {name:'2016/12/18 14:00', value:['2016/12/18 14:00', 45]},
                {name:'2016/12/18 15:00', value:['2016/12/18 15:00', 49]},
                {name:'2016/12/18 16:00', value:['2016/12/18 16:00', 51]},
                {name:'2016/12/18 17:00', value:['2016/12/18 17:00', 56]},
                {name:'2016/12/18 18:00', value:['2016/12/18 18:00', 61]},
                {name:'2016/12/18 19:00', value:['2016/12/18 19:00', 62]},
                {name:'2016/12/18 20:00', value:['2016/12/18 20:00', 67]},
                {name:'2016/12/18 21:00', value:['2016/12/18 21:00', 70]},
                {name:'2016/12/18 22:00', value:['2016/12/18 22:00', 73]},
                {name:'2016/12/18 23:00', value:['2016/12/18 23:00', 79]}
            ],
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
        data: ['第一层','第二层','第三层','第四层','第五层','第六层','第七层']
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
        data: ['0-5s','6-10s','11-20s','21-40s','41-80s','80-160s','160s以上']
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
            data:[15, 27, 24, 31, 18, 12, 71],
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
            data:[15, 27, 24, 31, 18, 12, 66, 15, 27, 24, 31, 18, 12, 66,15, 27, 24, 31, 18, 12, 66, 15, 27, 30 ],
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
        data: ['单人对话','朋友圈','微信群','公众号文章','其他']
    },
    series : [
        {
            name: '访问来源',
            type: 'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:1548, name:'单人对话'},
                {value:310, name:'朋友圈'},
                {value:234, name:'微信群'},
                {value:135, name:'公众号文章'},
                {value:122, name:'其他'}
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
            name: '访问来源',
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

cbcj.setOption(cbcj_data);
tlsc.setOption(tlsc_data);
fwsj.setOption(fwsj_data);
wxly.setOption(wxly_data);
fxqx.setOption(fxqx_data);

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
        $.ajax({
            url:'/admin/data/wechat',
            success:function (res) {
                show.top = res.top;
                puf_day.series[0].data = res.top.pv_everyday;
                puf_day.series[1].data = res.top.uv_everyday;
                puf_day.series[2].data = res.top.share_everyday;
                puf_day.xAxis.data     = res.top.days;
                puf.setOption(puf_day);
            }
        });
    }
});

show.onData();