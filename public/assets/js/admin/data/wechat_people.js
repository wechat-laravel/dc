var wgt  = echarts.init(document.getElementById('wgt'));

var wgt_data = {
    backgroundColor: new echarts.graphic.RadialGradient(0.3, 0.3, 0.8, [{
        offset: 0,
        color: '#f7f8fa'
    }, {
        offset: 1,
        color: '#cdd0d5'
    }]),
    tooltip: {},
    legend: [{
        tooltip: {
            show: true
        },
        selectedMode: 'false',
        bottom: 20,
        // data: ['第一级','第二级','第三级']
        data: ['第一级','第二级','第三级','第四级','第五级','第六级','第七级','第八级','第九级','第十级']
    }],
    toolbox: {
        show: true,
        feature: {
            saveAsImage: {
                show: true
            }
        }
    },
    animationDuration: 3000,
    animationEasingUpdate: 'quinticInOut',
    series: [{
        type: 'graph',
        layout: 'force',
        force: {
            repulsion: 60
        },
        data: [{
            "name": "原点",
            "symbolSize": 30,
            "value": 27
        }, {
            "name": "小明",
            "value":1,
            "category": "第一级"
        }, {
            "name": "b",
            "category": "第一级",
            "value": 1
        }, {
            "name": "c",
            "category": "第一级",
            "value": 1
        }, {
            "name": "aa",
            "value": 60,
            "category": "第二级"
        }, {
            "name": "bb",
            "category": "第二级",
            "value": 1
        }, {
            "name": "cc",
            "category": "第二级",
            "value": 1
        }, {
            "name": "aaa",
            "value": 5,
            "category": "第三级"
        }, {
            "name": "bbb",
            "category": "第三级",
            "value": 1
        }],
        links: [
        {
            "source": "原点",
            "target": "小明"
        }, {
            "source": "原点",
            "target": "b"
        }, {
            "source": "原点",
            "target": "c"
        }, {
            "source": "小明",
            "target": "aa"
        }, {
            "source": "小明",
            "target": "bb"
        }, {
            "source": "c",
            "target": "cc"
        }, {
            "source": "aa",
            "target": "aaa"
        }, {
            "source": "bb",
            "target": "bbb"
        }],
        categories: [{
            'name': '第一级'
        }, {
            'name': '第二级'
        }, {
            'name': '第三级'
        }, {
            'name': '第四级'
        }, {
            'name': '第五级'
        }, {
            'name': '第六级'
        }, {
            'name': '第七级'
        }, {
            'name': '第八级'
        }, {
            'name': '第九级'
        }, {
            'name': '第十级'
        }],
        focusNodeAdjacency: true,
        roam: true,
        label: {
            normal: {

                show: true,
                position: 'top'

            }
        },
        lineStyle: {
            normal: {
                color: 'source',
                curveness: 0,
                type: "solid"
            }
        }
    }]
};



var show = avalon.define({
    $id      : "show",
    top      : [],
    shows    : 'wang',
    peoples  : [],
    forwards : [],
    onPUF : function(res){
        // 使用刚指定的配置项和数据显示图表。
        if (res === 'wang'){
            show.shows = 'wang';
            wgt.setOption(wgt_data);
        }else if(res === 'tab'){
            show.shows = 'tab';
            show.onPeople();
        }else{
            show.shows = 'zhuan';
            show.onForward();
        }
    },
    onData : function(){
        $.ajax({
            url:'/admin/data/wechat_people',
            success:function (res) {
                if(res.success){
                    wgt_data.legend[0].data = res.data.levels;
                    wgt_data.series[0].data = res.data.data;
                    wgt_data.series[0].links = res.data.links;
                    wgt_data.series[0].categories = res.data.cate;
                    wgt.setOption(wgt_data);
                }
            }
        });
    },
    onPeople: function(){
        $.ajax({
            url:'/admin/data/wechat_peoples',
            success:function (ret) {
                show.peoples = ret.data;
            }
        })
    },
    onForward : function () {
        $.ajax({
            url:'/admin/data/wechat_forward',
            success:function (ret) {
                show.forwards = ret.data;
            }
        })
    },
    onInfo:function (e) {
        console.log(e);
    }
});

show.onData();

$(document).on('click','#people i',function () {

    if($(this).attr('class') === 'glyphicon glyphicon-triangle-right'){

        $(this).attr('class','glyphicon glyphicon-triangle-bottom');

        var id = $(this).parent().attr('id');

        var ci = $(this).parent().parent();

        $.ajax({

            url:'/admin/data/wechat_down?id='+id,

            success : function (ret) {

                if(ret.success){

                    ci.after(ret.html);

                }else{

                    console.log(0);

                }
            }
        });
    }
});