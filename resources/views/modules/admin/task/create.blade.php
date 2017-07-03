@extends('modules._layout.admin')
@section('title')
    封装链接
@endsection
@section('menu')
    任务管理
@endsection
@section('content')
    <div ms-controller="show">
        <div class="row">
            <div class="nav-tabs-custom">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>H5任务创建</strong></div>
                    <div class="panel-body">
                        <div class="bs-example bs-example-images">
                            <form class="create form">
                                <div class="form-group">
                                    <div id="error-show"></div>
                                </div>
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
                                    <label>封面图片地址</label>
                                    <input type="text" name="img_url" class="form-control"  placeholder="请输入封面图片地址">
                                </div>
                                <div class="form-group">
                                    <label>公众号名称 <small>（ 选填 ）</small></label>
                                    <input type="text" name="wechat_name" class="form-control"  placeholder="可填写公众号名称，长度在50字以内">
                                </div>
                                <div class="form-group">
                                    <label>公众号历史文章链接 <small>（ 选填 ）</small></label>
                                    <input type="text" name="wechat_url"  class="form-control"  placeholder="可填写公众号历史文章url，方便用户关注">
                                </div>
                                <div class="form-group">
                                    <label>任务创建方式</label>
                                    <select class="form-control" name="method" id="method" ms-on-change="@onMethod">
                                        <option value="0">请选择一种方式</option>
                                        <option value="h5">引用链接</option>
                                        <option value="custom">自定义编辑</option>
                                    </select>
                                </div>
                                {{--h5--}}
                                <div class="form-group" :visible="method === 'h5'">
                                    <label>H5页面地址 或 微信文章链接地址</label>
                                    <input type="text" name="page_url" class="form-control"  placeholder="请输入做好的H5页面地址或微信文章地址">
                                </div>
                                {{--自定义编辑--}}
                                <div class="form-group" :visible="method === 'custom'">
                                    <label>页面内容编辑</label>
                                    <script id="editor" type="text/plain" style="width:100%;height:500px;"></script>
                                </div>

                                <div class="form-group">
                                    <label>广告栏是否开启</label>
                                    <br>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_ad"  value="0" checked="checked" :click="@isAd(0)"> 不开启
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_ad"  value="1" :click="@isAd(1)"> 开启
                                    </label>
                                </div>
                                <div class="form-group" :visible="ad_column">
                                    <label>请选择广告栏模板 <small>（ 模板在 服务->广告栏设置 中创建于管理 ）</small></label>
                                    <select class="form-control" name="ad_column_id" id="select">
                                        <option value="0">系统默认模板</option>
                                        @if($ads)
                                            @foreach($ads as $ad)
                                                <option value="{{ $ad->id }}">{{ $ad->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-default">提交</button>
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
    <script type="text/javascript" src="{{ URL::asset('assets/ueditor/ueditor.config.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/ueditor/ueditor.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/task/create.js') }}"></script>
@endsection