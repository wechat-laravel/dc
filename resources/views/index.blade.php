<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>脉达传播 - 一问信息科技（上海）有限公司</title>
<link href="{{ URL::asset('index/css/index.css') }}" rel="stylesheet"/>
<meta name="keywords" content="脉达传播,红包裂变"/>
<meta name="description" content="脉达传播是基于微信的二度人脉挖掘传播，红包裂变，广告效果跟踪统计的一款传播营销利器。"/>
<link rel="shortcut icon" href="{{ URL::asset('index/images/favicon.ico') }}" mce_href="images/favicon.ico" type="image/x-icon">
<script src="{{ URL::asset('index/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ URL::asset('index/js/index.js') }}"></script>
<script>
if (checkIsPc()) {
    document.write('<meta name="viewport" content="width=device-width">');
} else {
    document.write('<meta name="viewport" content="width=1080px">');
    document.write('<link href="{{ URL::asset('index/css/media.css') }}" rel="stylesheet"/>');
}
</script>
</head>
<body>
<div class="container">
    <header id="header"> <i class="logo"></i>
        <ul>
            <li id="home-li"><a href="#home" class="a-home" onclick="anchorClick(this)">首页</a></li>
            <li id="point-li"><a href="#point" class="a-point" onclick="anchorClick(this)">行业痛点</a></li>
            <li id="func-li"><a href="#func" class="a-func" onclick="anchorClick(this)">功能特点</a></li>
            <li id="contact-us-li"><a href="#contact-us" class="a-contact-us" onclick="anchorClick(this)">联系我们</a></li>
            <li><a href="/auth/login" target="_blank">登录</a></li>
        </ul>
    </header>
    <div class="fix-place" id="home"></div>
    <section>
        <div class="page page1"> <img class="bg" src="{{ URL::asset('index/images/banner1.png') }}"/>
            <div class="download-c"> <a href="/auth/register" target="_blank"><img src="{{ URL::asset('index/images/download.png') }}"/></a> </div>
        </div>
        <div class="page page2">
            <div id="point"></div>
            <div class="title fade-down-before">直击行业痛点</div>
            <ul class="point-list">
                <li class="turn-before"> <img src="{{ URL::asset('index/images/suxiaogoutong.png') }}" class="chat"/>
                    <div>
                        <div class="t1">人脉传播</div>
                        <div class="cc">迅速挖掘二度人脉</div>
                    </div>
                </li>
                <li class="turn-before"> <img src="{{ URL::asset('index/images/zhinenggaoxiao.png') }}" class="effect"/>
                    <div>
                        <div class="t1">红包裂变</div>
                        <div class="cc">迅速分享裂变曝光</div>
                    </div>
                </li>
                <li class="turn-before"> <img src="{{ URL::asset('index/images/shujuanquan.png') }}" class="safe"/>
                    <div>
                        <div class="t1">层级关系</div>
                        <div class="cc">记录上下级明细</div>
                    </div>
                </li>
                <li class="turn-before"> <img src="{{ URL::asset('index/images/youxiaojianguan.png') }}" class="supervise"/>
                    <div>
                        <div class="t1">有效监管</div>
                        <div class="cc">考核员工裂变情况</div>
                    </div>
                </li>
                <li class="turn-before"> <img src="{{ URL::asset('index/images/jishizhangwo.png') }}" class="control"/>
                    <div>
                        <div class="t1">及时掌控</div>
                        <div class="cc">广告投放效果及时监控，KOL效果统计</div>
                    </div>
                </li>
                <li class="turn-before"> <img src="{{ URL::asset('index/images/fangbiankuaijie.png') }}" class="faster"/>
                    <div>
                        <div class="t1">方便快捷</div>
                        <div class="cc">H5，外链或者订阅号文章一键导入</div>
                    </div>
                </li>
            </ul>
            <div class="clear-both"></div>
        </div>
        <div class="page page3">
            <div id="func"></div>
            <label class="tip fade-down-before">产品功能特点</label>
            <div class="page3-inner page-info-c chanpintedian"> <img src="{{ URL::asset('index/images/chanpintedian.png') }}" class="i-left"/>
                <div class="page-info page-info-right">
                    <label class="title">脉达传播</label>
                    <div class="content"> 基于微信的二度人脉挖掘传播，红包裂变，广告效果跟踪统计的一款传播营销利器。 </div>
                </div>
            </div>
        </div>
        <div class="page page4 page-info-c shujufenxi">
            <div class="page-info page-info-left">
                <label class="title">红包裂变</label>
                <div class="content"> 通过红包裂变，挖掘自有人脉的潜在用户流量，用红包驱动，迅速带动二度人脉。 </div>
            </div>
            <img src="{{ URL::asset('index/images/shujufenxi.png') }}" class="i-right"/> </div>
        <div class="page page5 page-info-c iqiren"> <img src="{{ URL::asset('index/images/jiqiren.png') }}" class="i-left"/>
            <div class="page-info page-info-right">
                <label class="title">核心优势</label>
                <div class="content"> 能够清晰的查看到访客来源层级关系，用户分享去向，可以自定义红包规则，产生裂变，挖掘客户信息，提高影响力曝光量。</div>
            </div>
        </div>
        <div class="page page6 page-info-c juben">
            <div class="page-info page-info-left">
                <label class="title">批量群发好友通讯录</label>
                <div class="content"> 扫一扫二维码就可以批量群发好友通讯录，达到批量传播的功能 </div>
            </div>
            <img src="{{ URL::asset('index/images/juben.png') }}" class="i-right"/> </div>
        <div class="page page7 page-info-c zuzhijiegou"> <img src="{{ URL::asset('index/images/zuzhijiegou.png') }}" class="i-left"/>
            <div class="page-info page-info-right">
                <label class="title">场景运用</label>
                <div class="content"> 店面放置二维码客户转发得红包，线下发传单，加入二维码引导客户扫描转发得红包，新媒体员工传播绩效考核，微商传播，带动种子用户转发传播（红包裂变驱动）</div>
            </div>
        </div>
        <div class="page page8 page-info-c haoyou">
            <div class="page-info page-info-left">
                <label class="title">服务对象</label>
                <div class="content"> 1，新媒体运营 活动推广，微营销检测。</br>2，广告主实时查询推广效果，让广告投放更直观。</br>3，实体店老板：活动文章红包嵌入促进高效转发，挖掘客户二度人脉潜在客户</br> 4，销售人员：考核销售人员传播效益，挖掘二度潜在用户，在线报名</div>
            </div>
            <img src="{{ URL::asset('index/images/haoyou.png') }}" class="i-right"/> </div>
        <div class="page page9 page-info-c shujuanquan"> <img src="{{ URL::asset('index/images/shujuanquan2.png') }}" class="i-left"/>
            <div class="page-info page-info-right">
                <label class="title">人脉圈关系图</label>
                <div class="content"> 可以清晰查看人脉圈的好友传播途径，层级，另外还可以定向给某个表现优异的人直接发布红包 </div>
            </div>
        </div>
        <div class="page page10 page-info-c yingyong">
            <div class="page-info page-info-left">
                <label class="title">活动效果汇总</label>
                <div class="content">可以清晰的查看汇总数据，时段报表数据，传播层级以及传播途径。 </div>
            </div>
            <img src="{{ URL::asset('index/images/yingyong.png') }}" class="i-right"/> </div>
        <div class="page page11 page-info-c">
            <label class="title">合作客户</label>
            <img src="{{ URL::asset('index/images/hezuokehu.png?v=1') }}" class="hezuokehu"/>
            <div class="info">等<span>372</span>家企业与红包裂变客服建立合作关系</div>
        </div>
        <div class="page page12">
            <div id="contact-us"></div>
            <div class="tip">注册限时免费使用5天</div>
            <div class="download-c"> <a href="/auth/register" target="_blank"><img src="{{ URL::asset('index/images/download.png') }}" class=""/></a> </div>
        </div>
    </section>
    <footer>
        <div class="vt">版权所有 一问信息科技（上海）有限公司  电话：021-36213161</div>
        <div class="vc">沪ICP备16010254号-7</div>
    </footer>
    <div id="right-susp">
        <div class="susp-ser0"> <a target="_blank" href="tencent://message/?uin=765898961&amp;Site=sc.chinaz.com&amp;Menu=yes"></a> </div>
        <div class="susp-ser1"> <a target="_blank" href="tencent://message/?uin=2905582908&amp;Site=sc.chinaz.com&amp;Menu=yes"></a> </div>
        <div class="susp-ser2"> <a target="_blank" href="tencent://message/?uin=765898961&amp;Site=sc.chinaz.com&amp;Menu=yes"></a> </div>
        <div class="susp-ser2"> <a target="_blank" href="tencent://message/?uin=2905582908&amp;Site=sc.chinaz.com&amp;Menu=yes"></a> </div>
        <div class="susp-gotop" style="display: block;"> <a href="#home" onclick="anchorClick($('.a-home')[0])"></a> </div>
    </div>
</div>
</body>
</html>