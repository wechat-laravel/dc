<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>乐其意-DC</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/adminlte/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/adminlte/_all-skins.min.css') }}">
</head>
<style>
    .ms-controller {
        visibility: hidden
    }
</style>
<body class="hold-transition skin-green-light sidebar-mini" class="ms-controller" ms-controller="admin">
<div class="wrapper">
    <header class="main-header">
        <a href="#" class="logo">
            <span class="logo-mini"><b>一</b>问</span>
            <span class="logo-lg"><b>一问信息科技</b></span>
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
                            <span class="hidden-xs">{{ Auth::user()->name ? Auth::user()->name : Auth::user()->identity_name  }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{ Auth::user()->avatar ? Auth::user()->avatar : URL::asset('assets/images/user.jpg') }}" class="img-circle" alt="User Image">
                                <p>
                                    {{ Auth::user()->name ? Auth::user()->name : Auth::user()->identity_name  }}
                                    <small>身份：{{ Auth::user()->identity_name }}</small>
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
                    <p>{{ Auth::user()->name ? Auth::user()->name : Auth::user()->identity_name  }}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
                </div>
            </div>
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                </div>
            </form>
            <ul class="sidebar-menu">
                <li class="header">菜单列表</li>
                @if( Auth::user()->identity === 'admin')
                <li class="treeview" :class="{active: @two==='supper'}">
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
                            <a href="#"><i class="fa fa-credit-card"></i>账户充值</a>
                        </li>
                        <li :class="{active: @three==='supper_record' }">
                            <a href="#"><i class="fa fa-calendar"></i>充值记录</a>
                        </li>
                    </ul>
                </li>
                @endif
                <li class="treeview" :class="{active: @two==='user'}">
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
                        <li :class="{active: @three==='user_account' }">
                            <a href="/admin/user/account"><i class="fa fa-lock"></i>安全设置</a>
                        </li>
                    </ul>
                </li>
                <li class="treeview" :class="{active: @two==='task'|| @two==='custom' }">
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
                            <a href="/admin/task/create"><i class="fa fa-tags"></i>封装链接</a>
                        </li>
                        <li :class="{active: @two==='custom' && @three==='' }">
                            <a href="/admin/custom"><i class="fa fa-edit"></i>图文原创</a>
                        </li>
                    </ul>
                </li>
                <li class="treeview" :class="{active: @two==='service'}">
                    <a href="#">
                        <i class="fa fa-folder"></i> <span>服务</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li :class="{active: @three==='service_red_bag'}"><a href="/admin/service/red_bag"><i class="fa fa-circle-o"></i>红包工具</a></li>
                        <li :class="{active: @three==='service_ad_column'}"><a href="/admin/service/ad_column"><i class="fa fa-circle-o"></i>广告栏设置</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="/admin/service/help">
                        <i class="fa fa-align-justify"></i> <span>使用指南</span>
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
                <li class="active">@yield('menu')</li>
            </ol>
        </section>
        <section class="content">
            @yield('content')
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>版本</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2017/5/11 <a href="http://hackqy.com">Hackqy</a>.</strong> 版权所有保留
    </footer>
</div>

<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/adminlte/app.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/avalon.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/_layout/admin.js') }}"></script>
</body>
</html>
@section('afterScript')
@show
