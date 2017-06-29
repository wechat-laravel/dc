@extends('modules._layout.admin')
@section('title')
    个人资料
@endsection
@section('menu')
    个人中心
@endsection
@section('content')
    <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <div ms-controller="show">
        <div class="row">
            <div class="nav-tabs-custom">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>资料头像设置</strong></div>
                <div class="panel-body">
                    <div class="col-md-6 col-sm-12 col-xs-12">
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
                    <div class="col-md-6 col-sm-12 col-xs-12" style="margin-top: 20px;">
                        <div :visible="ava_err"  class="alert alert-danger ava_err" role="alert">操作步骤：点击上传图像，选取图片,预览后，再点击上传!</div>
                        <div class="caijian">
                            <div class="imageBox">
                                <div class="thumbBox"></div>
                                <div class="spinner" style="display: none">Loading...</div>
                            </div>
                            <div class="action">
                                <div class="new-contentarea tc"> <a href="javascript:void(0)" class="upload-img">
                                        <label for="upload-file">上传图像</label>
                                    </a>
                                    <input type="file" class="" name="img" id="upload-file" />
                                </div>
                                <input type="button" id="btnCrop"  class="Btnsty_peyton" value="预览" :click="@yulan">
                                <input type="button" id="btnZoomIn" class="Btnsty_peyton" value="+"  >
                                <input type="button" id="btnZoomOut" class="Btnsty_peyton" value="-" >
                            </div>
                            <div class="cropped"></div>
                        </div>
                        <br>
                        <div class="field" :visible="upload">
                            <div class="btn btn-success btn-lg btn-block" :click="@onAvatar">上传</div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/user/profile.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/cropbox.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/sctp.js') }}"></script>
@endsection