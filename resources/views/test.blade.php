<!DOCTYPE html>
<html>
<head>
    <title>{{ $task->title }}</title>
    <meta name="DC"content="{{ $task->desc }}">
    <meta name="description"content="{{ $task->desc }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap.min.css') }}">
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
        }
        div,p,a,span,img{
            border: 1px solid red;
        }
        img{
            max-width: 100%;
        }
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

@if($task->mark === 'h5')
    @if(preg_match('/mp.weixin.qq.com/', $task->page_url))
        <div class="row" style="margin-bottom: 60px;">
            <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <h3>{{ $task->title }}</h3>
                <h5 style="color: #8c8c8c;">{{ $task->created_at }}</h5>
                <div style="margin-top: 10px;">
                    {!! $task->editorValue !!}
                </div>
            </div>
        </div>
    @else
        <iframe style="margin-bottom: 20px;" src="{{ $task->page_url }}" frameborder="0" width="100%" height="100%"></iframe>
    @endif
@else
    <div class="row" style="margin-bottom: 60px;">
        <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
            {!! $task->editorValue !!}
        </div>
    </div>
@endif

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
    $("img").each(function(){$(this).attr("data-src").indexOf("mmbiz.qpic.cn")>-1&&$(this).attr("src","http://wewen.io/image?src="+$(this).attr("data-src"))});
</script>

</html>
