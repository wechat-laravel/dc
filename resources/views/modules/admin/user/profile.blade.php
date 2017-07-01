@extends('modules._layout.admin')
@section('title')
    个人资料
@endsection
@section('menu')
    个人中心
@endsection
@section('content')
    <div ms-controller="show">
        <div class="row">
            <div class="nav-tabs-custom">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>资料设置</strong></div>
                    <div class="panel-body">
                        <form class="create form">
                            <div class="form-group">
                                <div id="error-show"></div>
                            </div>
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label>用户名</label>
                                <input type="text" name="name" class="form-control"  placeholder="用户名长度在20字以内" value="{{ Auth::user()->name }}">
                            </div>
                            <div class="form-group">
                                <label>邮箱账号</label>
                                <input type="text" name="email" class="form-control" readonly="readonly" value="{{ Auth::user()->email }}">
                            </div>
                            <div class="form-group">
                                <label>QQ号</label>
                                <input type="text" name="qq" class="form-control"  placeholder="请输入QQ号" value="{{ Auth::user()->qq }}">
                            </div>
                            <div class="form-group">
                                <label>微信号</label>
                                <input type="text" name="wechat_id" class="form-control"  placeholder="请输入微信号" value="{{ Auth::user()->wechat_id }}">
                            </div>
                            <div class="form-group">
                                <label>手机号</label>
                                <input type="text" name="mobile" class="form-control"  placeholder="请输入手机号" value="{{ Auth::user()->mobile }}">
                            </div>
                            <button type="submit" class="btn btn-success btn-block">提交修改</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/user/profile.js') }}"></script>
@endsection