<!doctype html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
        <meta name="author" content="">
        <link rel="icon" href="favicon.ico">

        <title>Dc.le71.cn</title>
        <!-- Bootstrap core CSS 3.3.7-->
        <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

        <link href="{{ URL::asset('assets/css/auth/login.css') }}" rel="stylesheet">

    </head>
    <body>
        {{--<header>--}}
            {{--<nav class="navbar navbar-default">--}}
                {{--<div class="container-fluid">--}}
                    {{--<div class="navbar-header">--}}
                        {{--<a class="navbar-brand" href="#">--}}
                            {{--<img alt="Brand" style="width: 30px;height: 30px;margin-top: -5px;" class="text-center" src="{{ URL::asset('assets/images/logo.png') }}">--}}
                        {{--</a>--}}
                        {{--<h1 class="navbar-text" style="font-size: 20px;">Dc.Le71.cn</h1>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</nav>--}}
        {{--</header>--}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4" id="logindev">
                    <h3 class="text-center">
                        <img class="logo-img" width="45px" height="45px;" src="{{ URL::asset('assets/images/z_logoo.png') }}">
                        <span>乐其意</span>
                    </h3>
                        <form>
                            {{--<div class="form-group has-success has-feedback">--}}
                                {{--<input type="text" class="form-control" id="inputSuccess2" aria-describedby="inputSuccess2Status">--}}
                                {{--<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>--}}
                                {{--<span id="inputSuccess2Status" class="sr-only">(success)</span>--}}
                            {{--</div>--}}
                            <div class="form-group ">
                                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="请输入邮箱账号">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="请输入账号密码">
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="请输入验证码">
                                <span class="input-group-addon" id="vcode" style="padding: 0 0"><img src="/captcha?captcha={{ time() }}" alt=""></span>
                            </div>
                            <div class="checkbox">
                                <label class="pull-left">
                                    <input type="checkbox"> 记住我
                                </label>
                                <label class="pull-right">
                                    <span><a href="">忘记密码了？</a></span>
                                </label>
                            </div>
                            <div class="clearfix"></div>
                            <div style="margin-top: 10px;">
                                <button type="submit" class="btn btn-primary btn-block">登录</button>
                                <button type="submit" class="btn btn-success btn-block">注册账号</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>

    </body>
    <script type="text/javascript" src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
</html>