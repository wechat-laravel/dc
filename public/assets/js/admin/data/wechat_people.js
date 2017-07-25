//分页
function Pages(current,last){
    //times 循环的次数
    var times = last;
    //起始的页码数
    var star  = 1;
    var pages = [];
    if(times > 10){
        times = 10;
        if(current - 5 > 0){
            star = current - 4;
            if((star + times) >last){
                star = last-9;
            }
        }
    }
    for (var i =1;i<=times;i++){
        pages.push(star);
        star+=1;
    }
    return pages;
}

var wgt  = echarts.init(document.getElementById('wgt'));
wgt.hideLoading();
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
            },
            dataView : {show: true, readOnly: true},
            restore : {show: true}
        }
    },
    animationDuration: 3000,
    animationEasingUpdate: 'quinticInOut',
    series: [{
        type: 'graph',
        layout: 'force',
        force: {
            repulsion: 5
        },
        data: [{
            "name": "原点",
            "symbolSize": 5,
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
    layer    : 1,
    layers   : [],
    url      : "",
    pages    : [],              //储存要展示的页数
    last     : 0,               //最后一页的页码
    total    : 0,               //所有的条数
    visible  : false,           //默认不显示（没有数据的提示）
    curr     : 0,               //当前的页码
    task_id  : $('input[name=task_id]').val(),
    maoliduo : false,

    onPUF : function(res){
        // 使用刚指定的配置项和数据显示图表。
        if (res === 'wang'){
            show.maoliduo = false;
            show.shows = 'wang';
            wgt.setOption(wgt_data);
        }else if(res === 'peoples'){
            show.shows = 'peoples';
            show.onPeople();
        }else if(res === 'layers'){
            show.shows = 'layers';
            show.onLayer(show.layer);
        }else{
            show.shows = 'forwards';
            show.onForward();
        }
    },
    onData : function(){
        show.maoliduo = false;
        $.ajax({
            url:'/admin/data/wechat_people/'+show.task_id,
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
    //统一的数据请求
    getData : function(e){
        show.maoliduo = true;
        $.ajax({
            url: show.url,
            method: 'GET',
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(data){
            show.pages   = Pages(data.current_page, data.last_page);
            show.curr    = data.current_page;
            show.last    = data.last_page;
            show.total   = data.total;
            if (e ==='peoples'){
                show.peoples  = data.data;
            }else  if( e === 'forwards'){
                show.forwards = data.data;
            }else{
                show.layers   = data.data;
            }

            if (data.data.length === 0) {
                show.visible = true;
            } else {
                show.visible = false;
            }
        });
    },
    //表格数据
    onPeople: function(){
        show.url = '/admin/data/wechat_peoples/'+show.task_id+'?screen=1&page=1';
        show.getData('peoples');
    },
    //转发客户
    onForward : function () {
        show.url = '/admin/data/wechat_forward/'+show.task_id+'?screen=1&page=1';
        show.getData('forwards');
    },
    //层级影响力
    onLayer : function (e) {
        show.layer = e;
        show.url   = '/admin/data/wechat_layer/'+show.task_id+'?layer='+e+'&page=1';
        show.getData('layers');
    },
    toPage: function (e,name){
        var url  = show.url.substr(0, show.url.lastIndexOf('=') + 1);
        show.url = url + e;
        show.getData(name);
    }
});

show.onData();

$(document).on('click','#people i',function () {

    if($(this).attr('class') === 'glyphicon glyphicon-triangle-right'){

        $(this).attr('class','glyphicon glyphicon-triangle-bottom');

        var id = $(this).parent().attr('id');

        var ci = $(this).parent().parent();

        $.ajax({

            url:'/admin/data/wechat_down/'+show.task_id+'?id='+id,

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