@extends('modules._layout.admin')
@section('title')
    安全设置
@endsection
@section('menu')
    个人中心
@endsection
@section('content')
    <div ms-controller="show">
        <div class="row">
            <div class="nav-tabs-custom">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>修改账户密码</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div id="error-show"></div>
                        </div>
                        <form class="form">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label>原密码</label>
                                <input type="password" name="password" class="form-control"  placeholder="请输入旧密码" >
                            </div>
                            <div class="form-group">
                                <label>新密码</label>
                                <input type="password" name="new_password" class="form-control" placeholder="请输入新的密码" >
                            </div>
                            <div class="form-group">
                                <label>确认密码</label>
                                <input type="password" name="confirm" class="form-control"  placeholder="请输入确认密码" >
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="captcha" class="form-control" placeholder="请输入验证码">
                                    <span class="input-group-btn">
                                        <a class="btn btn-primary" :click="@onVcode" :class="[@start !== 60 ? 'disabled': '']">获取邮箱验证码<span id="times"></span></a>
                                    </span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">提交修改</button>
                        </form>
                        </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/user/account.js') }}"></script>
@endsection