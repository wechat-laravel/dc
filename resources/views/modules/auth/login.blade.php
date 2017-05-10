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
                    {{--<h1 class="text-center">asd</h1>--}}
                    <div class="jumbotron">
                        <form>
                            <div class="form-group has-success has-feedback">
                                <input type="text" class="form-control" id="inputSuccess2" aria-describedby="inputSuccess2Status">
                                <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                            </div>
                            <div class="form-group ">
                                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Remember Me
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            <button type="submit" class="btn btn-success btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </body>
    <script type="text/javascript" src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
</html>