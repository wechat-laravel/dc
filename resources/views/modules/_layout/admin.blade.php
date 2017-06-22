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
    .skin-blue .main-header .navbar {
        background-color: #00a65a;
    }
    .skin-blue .main-header .logo:hover{
        background-color: #00a65a;
    }
    .main-header li.user-header {
        background-color: #00a65a;
    }
    .skin-blue .main-header .logo {
        background-color: #00a65a;
        color: #fff;
        border-bottom: 0 solid transparent;
    }
    .skin-blue .main-header li.user-header {
        background-color: #00a65a;
    }
    .skin-blue .main-header .navbar .sidebar-toggle:hover {
        background-color: #008d4c;
    }
</style>
<body class="hold-transition skin-blue sidebar-mini" class="ms-controller" ms-controller="admin">
<div class="wrapper">
    <header class="main-header">
        <a href="#" class="logo">
            <span class="logo-mini"><b>一</b>问</span>
            <span class="logo-lg"><b>一问科技</b></span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ URL::asset('assets/images/user.jpg') }}" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{ Auth::user()->name ? Auth::user()->name : Auth::user()->identity_name  }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{ URL::asset('assets/images/user.jpg') }}" class="img-circle" alt="User Image">
                                <p>
                                    {{ Auth::user()->name ? Auth::user()->name : Auth::user()->identity_name  }}
                                    <small>创建时间：2017/5/12</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">账号设置</a>
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
                    <img src="{{ URL::asset('assets/images/user.jpg') }}" class="img-circle" alt="User Image">
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
                            <a href="/admin/task"><i class="fa fa-circle-o"></i>任务列表</a>
                        </li>
                        <li :class="{active: @three==='task_create' }">
                            <a href="/admin/task/create"><i class="fa fa-circle-o"></i>封装链接</a>
                        </li>
                        <li :class="{active: @two==='custom' && @three==='' }">
                            <a href="/admin/custom"><i class="fa fa-circle-o"></i>图文原创</a>
                        </li>
                    </ul>
                </li>
                {{--<li class="active treeview">--}}
                    {{--<a href="#">--}}
                        {{--<i class="fa fa-dashboard"></i>--}}
                        {{--<span>控制台</span>--}}
                        {{--<span class="pull-right-container">--}}
                          {{--<i class="fa fa-angle-left pull-right"></i>--}}
                        {{--</span>--}}
                    {{--</a>--}}
                    {{--<ul class="treeview-menu">--}}
                        {{--<li class="active"><a href="#"><i class="fa fa-circle-o"></i>微信传播</a></li>--}}
                        {{--<li><a href="/admin/data/wechat"><i class="fa fa-circle-o"></i>传播数据</a></li>--}}
                        {{--<li><a href="/admin/data/wechat_people"><i class="fa fa-circle-o"></i>传播关系</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <li class="treeview" :class="{active: @two==='service'}">
                    <a href="#">
                        <i class="fa fa-folder"></i> <span>服务</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li :class="{active: @three==='service_red_bag'}"><a href="/admin/service/red_bag"><i class="fa fa-circle-o"></i>红包工具</a></li>
                    </ul>
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
    <aside class="control-sidebar control-sidebar-dark">
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">最新动态</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-user bg-yellow"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                <p>New phone +1(800)555-1234</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                <p>nora@example.com</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-file-code-o bg-green"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                <p>Execution time 5 seconds</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <h3 class="control-sidebar-heading">任务进展</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="label label-danger pull-right">70%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Update Resume
                                <span class="label label-success pull-right">95%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Laravel Integration
                                <span class="label label-warning pull-right">50%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Back End Framework
                                <span class="label label-primary pull-right">68%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
        </div>
    </aside>
    <div class="control-sidebar-bg"></div>
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
