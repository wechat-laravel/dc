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
                <div class="panel-heading"><strong>资料设置</strong></div>
                <div class="panel-body">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <form class="create form">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label>封面标题</label>
                                <input type="text" name="title" class="form-control"  placeholder="请输入标题，长度在50字以内">
                            </div>
                            <div class="form-group">
                                <label>封面描述</label>
                                <input type="text" name="desc" class="form-control" placeholder="请输入封面描述，长度在100字以内">
                            </div>
                            <div class="form-group">
                                <label>封面图片地址></label>
                                <input type="text" name="img_url" class="form-control"  placeholder="请输入封面图片地址">
                            </div>
                            <div class="form-group">
                                <label>H5页面地址</label>
                                <input type="text" name="page_url" class="form-control"  placeholder="请输入做好的H5页面地址">
                            </div>
                            {{--<button type="submit" class="btn btn-default">提交</button>--}}
                        </form>
                        <br>
                        <div class="row">
                            <div id="error-show"></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <h3>头像修改 <small>温馨提示: 请预览后再提交!</small></h3>
                        <div :visible="ava_err"  class="alert alert-danger ava_err" role="alert">请预览生成图片后,再点击上传!</div>
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
                                <input type="button" id="btnCrop"  class="Btnsty_peyton" value="预览">
                                <input type="button" id="btnZoomIn" class="Btnsty_peyton" value="+"  >
                                <input type="button" id="btnZoomOut" class="Btnsty_peyton" value="-" >
                            </div>
                            <div class="cropped"></div>
                        </div>
                        <br>
                        <div class="field">
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
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/user/profile.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/cropbox.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/sctp.js') }}"></script>
@endsection