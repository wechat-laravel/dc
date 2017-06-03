var wgt  = echarts.init(document.getElementById('wgt'));

var wgt_data = {
    height : 500,
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
        name: '原点',
        type: 'graph',
        layout: 'force',

        force: {
            repulsion: 300
        },
        data: [{
            "name": "原点",
            "symbolSize": 30,
            "value": 27
        }, {
            "name": "a",
            "value": 15,
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
            "target": "a"
        }, {
            "source": "原点",
            "target": "b"
        }, {
            "source": "原点",
            "target": "c"
        }, {
            "source": "a",
            "target": "aa"
        }, {
            "source": "a",
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

wgt.setOption(wgt_data);
