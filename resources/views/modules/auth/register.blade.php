<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>Dc.le71.cn</title>
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/auth/login.css') }}" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4" id="logindev">
            <h3 class="text-center">
                <img class="logo-img" width="45px" height="45px;" src="{{ URL::asset('assets/images/z_logoo.png') }}">
                <span>乐其意</span>
            </h3>
            <form>
                <div class="form-group ">
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="请输入注册邮箱">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="请输入注册密码">
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="请输入验证码">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">获取验证码</button>
                    </span>
                </div>
                <div class="clearfix"></div>
                <div style="margin-top: 10px;">
                    <button type="submit" class="btn btn-success btn-block">注册</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
<script type="text/javascript" src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
</html>