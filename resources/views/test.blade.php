<!DOCTYPE html>
<html>
<head>
    <title>{{ $task->title }}</title>
    <meta name="DC"content="{{ $task->desc }}">
    <meta name="description"content="{{ $task->desc }}">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="x-rim-auto-match" content="none">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">
    <meta name="viewport" id="viewport" content="width=320, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="{{ URL::asset('assets/js/jquery.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ URL::asset('assets/js/bootstrap.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ URL::asset('assets/js/ceshi.js') }}" type="text/javascript" charset="utf-8"></script>
    {{--<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>--}}
    <style>
        body{
            height: 100%;
            width: 100%;
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
            top: 0;
            padding: 0;
            margin: 0;
            font-family: -apple-system-font,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei",sans-serif;
            overflow-x:hidden;
            /*background-color: #F4F5F5;*/
        }
        *{
	    max-width:100%;
        }
        .row{
            margin-right:0px;
            margin-left:0px;
        }
        .video_iframe{
            max-width: 100%;
        }
        img{
            max-width: 100%;
        }
        .row p a{ width:100%;overflow:hidden; text-overflow: ellipsis; display:block;}
    </style>
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">请留下您的联系方式</h4>
            </div>
            <form class="form">
                {!! csrf_field() !!}
                <input type="hidden" name="tasks_id" value="{{ $task->id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label>您的名字<small>（ 20字以内 ）</small></label>
                        <input type="text" name="name" class="form-control"  placeholder="请输入您的名字">
                    </div>
                    <div class="form-group">
                        <label>您的性别</label>
                        <label class="radio-inline">
                            <input type="radio" name="sex" value="1" checked="checked"> 男
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="sex" value="2"> 女
                        </label>
                    </div>
                    <div class="form-group">
                        <label>您的手机号</label>
                        <input type="text" name="mobile" class="form-control" placeholder="请输入您的手机号">
                    </div>
                    <div class="form-group">
                        <label>留言备注 <small>（ 200字以内 ）</small></label>
                        <textarea class="form-control" rows="3" name="remark"></textarea>
                    </div>
                    <div class="row">
                        <div id="error-show"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </form>

        </div>
    </div>
</div>
{{--微信二维码--}}
<div class="modal fade bs-qrcode-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="width: 250px;height: 300px;margin: 50% auto;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">长按图片加好友</h5>
            </div>
            <img class="img-rounded center-block" src="{{ URL::asset('ceshi.jpg') }}" style="width: 200px;height: 200px;">
        </div>
    </div>
</div>

{{--商务通链接二维码--}}
<div class="modal fade bs-zixun-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="width: 250px;height: 300px;margin: 50% auto;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">长按图片在线咨询</h5>
            </div>
            <img class="img-rounded center-block" src="{{ URL::asset('ceshi.jpg') }}" style="width: 200px;height: 200px;">
        </div>
    </div>
</div>

@if($task->mark === 'h5')
    @if(preg_match('/mp.weixin.qq.com/', $task->page_url))
        <div class="row" style="margin-bottom: 60px;">
            <div class="col-xs-12  col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <h3>{{ $task->title }}</h3>
                <h5 style="color: #8c8c8c;">{{ substr($task->created_at,0,10) }} <a href="{{ $task->wechat_url }}">{{ $task->wechat_name }}</a></h5>
                <div style="margin-top: 10px;">
                    {!! $task->editorValue !!}
                </div>
            </div>
        </div>
    @else
        <iframe style="margin-bottom: 20px;" src="{{ $task->page_url }}" frameborder="0" width="100%" height="100%"></iframe>
    @endif
@else
    <div class="row" style="margin-bottom: 50px;">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
            <h3>{{ $task->title }}</h3>
            <h5 style="color: #8c8c8c;">{{ substr($task->created_at,0,10) }} <a href="{{ $task->wechat_url }}">{{ $task->wechat_name }}</a></h5>
            {!! $task->editorValue !!}
        </div>
    </div>
@endif
<div style="width: 100%;background-color:#F4F5F5;padding-top: 2px;">
    <h5 class="text-center"><b>本文由<span style="color: red"> ( 分享来源 ) </span>分享推荐</b></h5>
    <div class="row" style="padding:10px 10px 0px 10px;">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4" style="background-color:#FFFFFF;padding-top: 10px;">
            <img src="{{ URL::asset('wewen.png') }}" class="img-circle center-block" style="width: 40px;height: 40px;">
            <h5 class="text-center"><b>（ 标题 ）</b>  <a class="btn btn-default btn-xs">（ 标签 ）</a></h5>
            <div class="center-block" style="margin-top: 25px;">
                <div class="text-center col-sm-4 col-xs-4" style="float: left;">
                    <a href="tel:13764567708"  class="btn" style="background-color: orange;width: 40px;height: 40px; border-radius: 20px;">
                        <i class="fa fa-phone" style="color:white;background-color:orange;font-size: 25px;line-height: 28px;margin-left: -5px;"></i>
                    </a>
                    <br>
                    打Ta电话
                </div>
                <div class="text-center col-sm-4 col-xs-4" style="float: left;">
                    <a onclick="onQrcode()" class="btn" style="background-color: #4CB25C;width: 40px;height: 40px; border-radius: 20px;">
                        <i class="fa fa-wechat" style="color:white;background-color:#4CB25C;font-size: 20px;line-height: 28px;margin-left: -7px;"></i>
                    </a>
                    <br>
                    微信二维码
                </div>
                <div class="text-center col-sm-4 col-xs-4" style="float: left;">
                    <a onclick="onZixun()" class="btn" style="background-color: #337AB7;width: 40px;height: 40px; border-radius: 20px;">
                        <i class="fa fa-commenting-o" style="color:white;background-color:#337AB7;font-size: 20px;line-height: 27px;margin-left: -5px;"></i>
                    </a>
                    <br>
                    在线咨询
                </div>
            </div>
            <div style="clear: both"></div>
            <div style="margin-top: 20px;color: #938C8C">
                <p class="text-justify" >
                    <ul class="list-unstyled">
                        <li>标题一 ：<span><b>标题一具体内容</b></span></li>
                        <li>标题二 ：<span>标题二具体内容</span></li>
                        <li>标题三 ：<span>标题三具体内容</span></li>
                    </ul>
                </p>
            </div>
        </div>
    </div>
</div>
{{--@if($task->is_ad)--}}
    {{--@if($task->ad_column_id === 0)--}}
        {{--<nav class="navbar navbar-default navbar-fixed-bottom">--}}
            {{--<div class="container">--}}
                {{--<div class="navbar-header" style="width: 100%">--}}
                    {{--<a class="navbar-brand" href="https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzA4MjM1ODY1MA==&scene=124#wechat_redirect" style="height: 10px;">--}}
                        {{--<img class="logo-img" style="width: 25px;height: 25px;float: left;margin-right: 5px;margin-bottom: 5px;" src="{{ URL::asset('assets/images/z_logo.png') }}">--}}
                        {{--上海一问科技--}}
                    {{--</a>--}}
                    {{--<button type="button" class="navbar-btn btn btn-success btn-sm"  style="float: right" data-toggle="modal" data-target="#myModal">--}}
                        {{--报名--}}
                    {{--</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</nav>--}}
    {{--@else--}}
        {{--<nav class="navbar navbar-default navbar-fixed-bottom">--}}
            {{--<div class="container">--}}
                {{--<div class="navbar-header" style="width: 100%">--}}
                    {{--<a class="navbar-brand" href="{{ $task->ad->url }}" style="height: 10px;">--}}
                        {{--<img class="logo-img" style="width: 25px;height: 25px;float: left;margin-right: 5px;margin-bottom: 5px;" src="{{ $task->ad->litimg }}">--}}
                        {{--{{ $task->ad->name }}--}}
                    {{--</a>--}}
                    {{--<button type="button" class="navbar-btn btn btn-success btn-sm"  style="float: right" data-toggle="modal" data-target="#myModal">--}}
                        {{--报名--}}
                    {{--</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</nav>--}}
    {{--@endif--}}
{{--@endif--}}

</body>
<script type="text/javascript" charset="UTF-8">
    function onMobile(){
        alert('电话');
    }
    function onQrcode(){
        $('.bs-qrcode-modal-sm').modal('show');
    }
    function onZixun(){
        $('.bs-zixun-modal-sm').modal('show');
    }
</script>

</html>
