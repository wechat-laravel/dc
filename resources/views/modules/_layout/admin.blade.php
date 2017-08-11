<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>脉达传播</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ URL::asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/adminlte/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/adminlte/_all-skins.min.css') }}">
</head>
<style>
    [ms-controller]{visibility: hidden;}
    .skin-green-light .treeview-menu>li.active>a {
        background-color: #D2D6DE;
    }
</style>
<body class="hold-transition skin-green-light sidebar-mini" class="ms-controller" ms-controller="admin">

{{--系统通知框--start--}}
@if( Auth::user()->identity === 'visitor')
<div class="modal fade overdue" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document" style="margin-top: 200px;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #337AB7">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color: white"><b>系统通知：</b></h4>
            </div>
            <div class="modal-body">
                <p class="text-center">
                    尊敬的游客，您好！
                </p>
                <ul>
                    <li>
                        由于网站正式上线，我们对网站的所有用户根据身份作出调整限制。
                    </li>
                    <li>
                        网站注册的用户默认为游客身份，游客可免费体验网站所有功能5天的时间
                    </li>
                    <li>
                        超出5天后，所有功能将无法继续使用，体验到期时间可在右上角点击头像查看
                    </li>
                    <li>
                        如需继续使用，请联系管理员QQ：765898961 开通会员身份。
                    </li>
                    <li>
                        变更为会员身份后，将不再出现此通知（本通知为每隔半小时通知一次）
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
{{--系统通知框--end--}}

<div class="wrapper">
    <header class="main-header" id="top">
        <a href="#" class="logo">
            <span class="logo-mini"><img style="width: 30px;height: 30px;" src="{{ URL::asset('maidaM.png') }}" alt=""></span>
            <span class="logo-lg"><img style="height: 95px;width: 150px;margin-top: -20px;" src="{{ URL::asset('green.png') }}" alt=""></span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar ? Auth::user()->avatar : URL::asset('assets/images/user.jpg') }}" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{ Auth::user()->email  }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{ Auth::user()->avatar ? Auth::user()->avatar : URL::asset('assets/images/user.jpg') }}" class="img-circle" alt="User Image">
                                <p>
                                    身份：{{ Auth::user()->identity_name }}
                                    @if( Auth::user()->identity !== 'admin' )
                                        <small>过期时间：{{ date('Y-m-d m:i:s',Auth::user()->overdue_at) }}</small>
                                    @endif
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="/admin/user/profile" class="btn btn-default btn-flat">资料修改</a>
                                </div>
                                <div class="pull-right">
                                    <a href="/auth/logout" class="btn btn-default btn-flat">退出</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ Auth::user()->avatar ? Auth::user()->avatar : URL::asset('assets/images/user.jpg') }}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->identity_name }}身份 </p>
                    <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="header">菜单列表</li>
                @if( Auth::user()->identity === 'admin')
                <li class="treeview active">
                    <a href="#">
                        <i class="glyphicon glyphicon-home"></i>
                        <span>管理中心</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li :class="{active: @three==='supper_user' }">
                            <a href="/admin/supper/user"><i class="fa fa-group"></i>用户管理</a>
                        </li>
                        <li :class="{active: @three==='supper_recharge' }">
                            <a href="/admin/supper/recharge"><i class="fa fa-credit-card"></i>账户充值</a>
                        </li>
                        <li :class="{active: @three==='supper_record' }">
                            <a href="/admin/supper/record"><i class="fa fa-calendar"></i>充值记录</a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->identity === 'visitor' && Auth::user()->overdue_at < time())
                @else
                <li class="treeview active">
                    <a href="#">
                        <i class="fa fa-user"></i>
                        <span>个人中心</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li :class="{active: @three==='user_profile' }">
                            <a href="/admin/user/profile"><i class="fa fa-black-tie"></i>个人资料</a>
                        </li>
                        <li :class="{active: @three==='user_assets' }">
                            <a href="/admin/user/assets"><i class="fa fa-database"></i>账户资产</a>
                        </li>
                        <li :class="{active: @three==='user_recharge' }">
                            <a href="/admin/user/recharge"><i class="fa fa-jpy"></i>账户充值</a>
                        </li>
                        <li :class="{active: @three==='user_account' }">
                            <a href="/admin/user/account"><i class="fa fa-lock"></i>安全设置</a>
                        </li>
                    </ul>
                </li>
                <li class="treeview active" >
                    <a href="#">
                        <i class="fa fa-dashboard"></i>
                        <span>任务管理</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li :class="{active: @two==='task'&& @three==='' }">
                            <a href="/admin/task"><i class="fa fa-calendar"></i>任务列表</a>
                        </li>
                        <li :class="{active: @three==='task_create' }">
                            <a href="/admin/task/create"><i class="fa fa-tags"></i>文章创建</a>
                        </li>
                    </ul>
                </li>
                <li class="treeview active">
                    <a href="#">
                        <i class="fa fa-folder"></i> <span>服务插件</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li :class="{active: @three==='service_red_bag'}"><a href="/admin/service/red_bag"><i class="fa fa-hacker-news"></i>红包工具</a></li>
                        <li :class="{active: @three==='service_ad_column'}"><a href="/admin/service/ad_column"><i class="fa fa-file-audio-o"></i>广告栏设置</a></li>
                        <li :class="{active: @three==='service_mass'}"><a href="/admin/service/mass"><i class="fa fa-send-o"></i>群发助手</a></li>
                    </ul>
                </li>
                @endif
                <li class="treeview">
                    <a href="/admin/service/help">
                        <i class="fa fa-question-circle"></i> <span>系统帮助</span>
                    </a>
                </li>
            </ul>
        </section>
    </aside>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                管理中心
                <small>@yield('title')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i>后台管理</a></li>
                <li class="active" >@yield('menu')</li>
            </ol>
        </section>
        <section class="content">
            @yield('content')
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>版本</b> 2.1.2
        </div>
        <strong>Copyright &copy; 一问科技 </strong> 版权所有
    </footer>
    <nav class="navbar navbar-fixed-bottom" style="width: 55px;height: 120px;padding: 0;">
        <a class="btn-warning btn-lg mobile" role="button" data-toggle="popover"  data-placement="left" data-content="021-36213161" title="联系电话"  style="margin-bottom: 50px;padding: 9px 10px 3px;position: fixed;right: 12px;bottom: 90px;" ><i class="fa fa-phone" style="font-size: 22px;"></i></a>
        <a href="tencent://message/?uin=765898961&Site=sc.chinaz.com&Menu=yes" class="btn-primary btn-lg" style="margin-bottom: 50px;padding: 7px 10px;position: fixed;right: 12px;bottom: 50px;" ><i class="fa fa-qq"></i></a>
        <a href="#top" class="btn-success btn-lg" style="margin-bottom: 50px;padding: 7px 10px;position: fixed;right: 12px;bottom: 10px;" ><i class="fa fa-arrow-up"></i></a>
    </nav>
</div>
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/adminlte/app.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/avalon.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/_layout/admin.js') }}"></script>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?06e07bf90fd55acbde35acbf954c0506";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</body>
</html>
@section('afterScript')
@show
