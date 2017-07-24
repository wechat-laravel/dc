@extends('modules._layout.admin')
@section('title')
    使用指南
@endsection
@section('menu')
    使用指南
@endsection
@section('content')
    <div ms-controller="show">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li role="presentation" :class="{active: curr === 'update'}" :click="@onCurr('update')"><a href="#">更新日志</a></li>
                    <li role="presentation" :class="{active: curr === 'course'}" :click="@onCurr('course')"><a href="#">使用教程</a></li>
                    {{--<li role="presentation" :class="{active: curr === 'help'}" :click="@onCurr('help')"><a href="#">问题帮助</a></li>--}}
                </ul>
            </div>
            {{--更新日志--}}
            <div class="panel-body" :visible="curr === 'update'">
                {{--每次更新日志记录的开始--}}
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">更新时间：2017-7-24 v2.0.0</h3>
                    </div>
                    <div class="box-body">
                        <p>权限调整：</p>
                        <ul>
                            <li>所有用户调整为游客身份，可免费体验站内所有功能，为期5天 </li>
                            <li>体验时间过期后，所有功能将停止使用，如有需要转成会员，请联系我们的管理员。</li>
                        </ul>
                    </div>
                </div>
                {{--每次更新日志记录的开始--}}
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">更新时间：2017-7-21 v1.1.9</h3>
                    </div>
                    <div class="box-body">
                        <p>新增功能：</p>
                        <ul>
                            <li>个人中心 > 账户充值 :添加 在线微信支付功能、线下打款方式 </li>
                                <li>使用在线支付接口，收取4%的手续费</li>
                            <li>线下打款免手续费</li>
                        </ul>
                    </div>
                </div>
                {{--每次更新日志记录的开始--}}
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">更新时间：2017-7-17 v1.1.8</h3>
                    </div>
                    <div class="box-body">
                        <p>新增说明：</p>
                        <ul>
                            <li>系统帮助 :添加新的使用教程 </li>
                            <li>对系统不太熟悉的。可以看下我们的使用教程！如果还有不理解的地方，请联系我们的管理员</li>
                        </ul>
                    </div>
                </div>
                {{--每次更新日志记录的开始--}}
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">更新时间：2017-7-13 v1.1.7</h3>
                    </div>
                    <div class="box-body">
                        <p>新增功能：</p>
                        <ul>
                            <li>服务插件 > 广告栏设置 :添加新的广告模板（自定义模板）</li>
                            <li>自定义模板适用范围不包括H5页面，抓取的微信文章页面、自定义的文章页面都可正常使用</li>
                        </ul>
                        <p>改动功能：</p>
                        <ul>
                            <li>任务管理 > 文章创建 ：添加封面图片地址URL 改为 本地上传图片（该图片用于微信转发分享所显示的图标）</li>
                            <li>原所有创建的任务文章的封面图标 请重新编辑上传，避免转发封面图片不显示</li>
                        </ul>
                    </div>
                </div>

            </div>

            {{--使用教程--}}
            <div class="panel-body" :visible="curr === 'course'">
                {{--导航--}}
                <div class="col-md-2">
                    <div class="list-group">
                        <a href="#qianyan" class="list-group-item active">前言</a>
                        <a href="#account" class="list-group-item">账户相关</a>
                        <a href="#task" class="list-group-item">任务管理</a>
                        <a href="#datas" class="list-group-item">数据分析</a>
                        <a href="#red" class="list-group-item">红包任务</a>
                        <a href="#ad" class="list-group-item">广告栏</a>
                    </div>
                </div>
                {{--内容--}}
                <div class="col-md-10">
                    {{--前言--}}
                    <div id="qianyan">
                        <div class="page-header">
                            <h3>脉达传播 <small>前言</small></h3>
                        </div>
                        <div class="page">
                            <p><strong>该系统由 <a href="http://wewen.io/">上海一问信息科技有限公司</a> 开发</strong></p>
                            <p><strong>管理员 QQ：765898961</strong></p>
                            {{--<p><strong>技术员 QQ：136466380</strong></p>--}}
                        </div>
                    </div>

                    <br>

                    <div id="account">
                        <div class="page-header">
                            <h3>账户相关 <small>个人中心</small></h3>
                        </div>
                        <div class="page">
                            <p>
                                <strong><h4>用户登陆注册</h4></strong>
                            </p>
                            <p>
                                首页地址：http://www.maidamaida.com/
                            </p>
                            <p>
                                登录地址：http://www.maidamaida.com/auth/login
                            </p>
                            <div class="row">
                                <div class="col-md-4" >
                                    <div class="thumbnail">
                                        <img src="{{ URL::asset('/upload/images/index.png') }}">
                                        <div class="caption">
                                            <p>首页直接点击脉达传播字体可跳转到登录页面</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="thumbnail">
                                        <img src="{{ URL::asset('/upload/images/login.png') }}" >
                                        <div class="caption">
                                            <p>登录页面验证码可以直接点击更换</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="thumbnail">
                                        <img src="{{ URL::asset('/upload/images/account.png') }}" >
                                        <div class="caption">
                                            <p>后台：个人中心，完善个人资料，以便于我们与您更好的沟通与服务，安全设置中可以修改您的密码</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="thumbnail">
                                        <img src="{{ URL::asset('/upload/images/money.png') }}" >
                                        <div class="caption">
                                            <p>账户资产包括账户资产的详情明细，账户余额需要联系管理员来充值，可用于红包任务充值或个人红包奖励。红包任务的红包领取不从账户余额中扣除，而是在红包任务中设置的金额中扣除</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div id="task">
                        <div class="page-header">
                            <h3>任务管理</h3>
                        </div>
                        <div class="page">
                            <p>
                                <strong><h4>任务创建</h4></strong>
                            </p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/task.png') }}" >
                                    <div class="caption">
                                        <p>任务创建的时候有两种创建的方式，一种直接使用制作好的H5的页面地址或微信文章地址，另一种就是在线编辑。除H5页面公众号名称与链接不显示，其他正常显示，样式跟微信文章的标题样式一致</p>
                                    </div>
                                </div>
                            </div>
                            <p>
                                <strong><h4>任务列表</h4></strong>
                            </p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/task_list.png') }}" >
                                    <div class="caption">
                                        <p>任务创建后，可在任务列表里查看，点击预览的查看按钮，能看到一个二维码，先扫一扫打开确认一下自己创建的内容页是否有问题，没有问题的话，就可以点击微信右上角分享的功能键，选择一种分享方式传播出去了！</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div id="datas">
                        <div class="page-header">
                            <h3>数据分析 </h3>
                        </div>
                        <div class="page">
                            <p><strong>数据图</strong></p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/data_1.png') }}" >
                                    <div class="caption">
                                        <p>数据图页面_1：PV,UV,分享数，停留时长都可看到。可以看到一周内的走势图与每小时的实时走势图</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/data_2.png') }}" >
                                    <div class="caption">
                                        <p>可以详细的看到每一层级（最多统计到第十层）的数据情况，还有停留时长的占比与访问时间段的占比图</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/data_3.png') }}" >
                                    <div class="caption">
                                        <p>我们还可以统计到用户访问的来源（比如单人对话，朋友圈，微信群等），还有用户分享的去向（微信好友，QQ好友，朋友圈，微信群，QQ空间），我们可以以此来进行自定义设置我们的红包奖励任务</p>
                                    </div>
                                </div>
                            </div>
                            <p><strong>关系图</strong></p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/guan_1.png') }}" >
                                    <div class="caption">
                                        <p>该脉络图可以清晰的展示出任务传播扩散的情况。可以直观的看到哪个用户传播的影响力最大，最密集。网状图可以放大或缩小查看，鼠标放在某一个点上，末尾的数字表示下级的人数</p>
                                    </div>
                                </div>
                            </div>
                            <p><strong>表格数据</strong></p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/guan_2.png') }}" >
                                    <div class="caption">
                                        <p>表格数据默认首先显示的是第一层级的用户，如果有下级的话，可以一直点下去。很直观的展示了用户的下级的有几层，有几个下级用户。展示还有用户最后阅读的时间，性别和地址</p>
                                    </div>
                                </div>
                            </div>
                            <p><strong>层级影响力</strong></p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/guan_3.png') }}" >
                                    <div class="caption">
                                        <p>在这里，你可以直接查看某一个层级的用户，包括他们的性别和地区所在等信息。<b>点击路径详情按钮可以具体查看一个用户的来源路径图与数据信息</b></p>
                                    </div>
                                </div>
                            </div>
                            <p><strong>转发用户</strong></p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/guan_4.png') }}" >
                                    <div class="caption">
                                        <p>只有进行转发过的用户才会出现在这个表格里，可以清楚的看到一个用户转发的次数以及转发的去向。同时也可以看到他的直系下级（他的下级，只一层）的阅读人数与次数，<b>点击路径详情按钮可以具体查看一个用户的来源路径图与数据信息</b></p>
                                    </div>
                                </div>
                            </div>
                            <p><strong>路径-详情</strong><small>（ 用户详情 ）</small></p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/guan_5.png') }}" >
                                    <div class="caption">
                                        <p>
                                            上面的【层级影响力】 与【转发用户】两个表格数据 都有一个路径【详情】的按钮，点击按钮我们可以看到用户的具体信息，奖励记录，来源路径，与用户足迹（该用户是否访问过自己创建的多个任务都可以看到）。
                                            <b>并且我们可以在这里对用户进行备注，我们还可以根据这个用户的层级影响力与转发情况来进行红包奖励，可以直接发送给用户，该发送的奖励金额是直接从账户余额中扣除，而不是从红包任务设置的金额中扣除</b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div id="red">
                        <div class="page-header">
                            <h3>红包任务 <small>服务插件 - 红包工具</small></h3>
                        </div>
                        <div class="page">
                            <p><strong>红包创建</strong></p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/red_2.png') }}" >
                                    <div class="caption">
                                        <p>
                                            红包任务创建，创建的文章任务只能对应一个红包任务，可以设置红包任务的起始结束的时间。
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <p><strong>红包列表</strong></p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/red_1.png') }}" >
                                    <div class="caption">
                                        <p>
                                            可以看到红包任务的总金额与余额，设置的任务规则，可以充值，红包任务余额可以转出（转出到余额里）。可以查看红包领取的情况。 修改红包任务的配置信息、能直接暂停红包任务的发放。
                                            <b>因为微信官方的规则设定，个别用户可能有红包领取失败的情况，领取信息有问题的可以直接联系管理员反映</b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div id="ad">
                        <div class="page-header">
                            <h3>广告栏</h3>
                        </div>
                        <div class="page">
                            <p><strong>留言广告模板</strong></p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/ad_1.png') }}" >
                                    <div class="caption">
                                        <p>
                                            <b>若使用该广告功能，文章创建的时候必须要开启广告栏才可生效！</b>
                                            开启后，默认使用的是系统定制的广告栏。可在服务插件- 广告栏设置中创建一个留言模板。可以自定义标题 链接与图标。
                                            <b>该广告模板因为使用的是留言的形式，所有用户留言的数据我们都会保存下来，可以在设置该广告栏的任务里查看（任务数据页面 左上角的报名信息链接可以看到）</b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <p><strong>自定义广告模板</strong></p>
                            <div class="col-md-12">
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('/upload/images/ad_3.png') }}" >
                                    <div class="caption">
                                        <p>
                                            <b>若使用该广告功能，文章创建的时候必须要开启广告栏才可生效！</b>
                                            广告栏设置中 点击【自定义广告模板】进行创建，可以直接预览编辑的效果
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{--问题帮助--}}
            <div class="panel-body" :visible="curr === 'help'">

                {{--每次更新日志记录的开始--}}
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">更新时间：2017-7-13 v1.1.7</h3>
                    </div>
                </div>
                {{--每次更新日志记录的结束--}}

            </div>
        </div>
    </div>

@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/help/index.js') }}"></script>
@endsection