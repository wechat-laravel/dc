@extends('modules._layout.admin')
@section('title')
    封装链接任务编辑
@endsection
@section('menu')
    任务管理
@endsection
@section('content')
    <div ms-controller="show">
        <div class="row">
            <div class="nav-tabs-custom">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>封装链接任务编辑</strong></div>
                    <div class="panel-body">
                        <div class="bs-example bs-example-images">
                            <form class="edit form">
                                <div class="form-group">
                                    <div id="error-show"></div>
                                </div>
                                <input type="hidden" name="cc" value="{{ $task->is_ad }}">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label>任务编号</label>
                                    <input type="text" name="id" class="form-control"  placeholder="请输入标题，长度在50字以内" value="{{ $task->id }}" readonly="readonly">
                                </div>
                                <div class="form-group">
                                    <label>封面标题</label>
                                    <input type="text" name="title" class="form-control"  placeholder="请输入标题，长度在50字以内" value="{{ $task->title }}">
                                </div>
                                <div class="form-group">
                                    <label>封面描述</label>
                                    <input type="text" name="desc" class="form-control"  placeholder="请输入封面描述，长度在100字以内" value="{{ $task->desc }}">
                                </div>
                                <div class="form-group">
                                    <label>封面图片地址 <small>（ 重新上传，将替换原图 ）</small></label>
                                    <input type="file" name="img_url"  class="projectfile"  multiple="multiple">
                                    <p class="help-block">请上传大小在2M以内的图片</p>
                                </div>
                                <div class="form-group">
                                    <label>H5页面地址 或 微信文章链接地址 （ 注：不能使用微信门店，或微商城的链接 ）</label>
                                    <input type="text" name="page_url" class="form-control"  placeholder="请输入做好的H5页面地址或微信文章的链接地址" value="{{ $task->page_url }}">
                                </div>
                                <div class="form-group">
                                    <label>公众号名称 <small>（ 选填 ）</small></label>
                                    <input type="text" name="wechat_name" class="form-control"  placeholder="可填写公众号名称，长度在50字以内" value="{{ $task->wechat_name }}">
                                </div>
                                <div class="form-group">
                                    <label>公众号历史文章链接 <small>（ 选填 ）</small></label>
                                    <input type="text" name="wechat_url"  class="form-control"  placeholder="可填写公众号历史文章url，方便用户关注" value="{{ $task->wechat_url }}">
                                </div>
                                <div class="form-group">
                                    <label>广告栏是否开启</label>
                                    <br>
                                    @if($task->is_ad === 1)
                                        <label class="radio-inline">
                                            <input type="radio" name="is_ad"  value="0"  :click="@isAd(0)"> 不开启
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_ad"  value="1"  checked="checked" :click="@isAd(1)"> 开启
                                        </label>
                                    @else
                                        <label class="radio-inline">
                                            <input type="radio" name="is_ad"  value="0"  checked="checked" :click="@isAd(0)"> 不开启
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_ad"  value="1" :click="@isAd(1)"> 开启
                                        </label>
                                    @endif
                                </div>
                                <div class="form-group" :visible="ad_column">
                                    <label>请选择广告栏模板 <small>（ 模板在 服务->广告栏设置 中创建于管理 ）</small></label>
                                    <select class="form-control" name="ad_column_id" id="select">
                                        <option value="0">系统默认模板</option>
                                        @if($ads)
                                            @foreach($ads as $ad)
                                                @if($ad->id === $task->ad_column_id)
                                                    <option value="{{ $ad->id }}" selected="selected">{{ $ad->name }}</option>
                                                @else
                                                    <option value="{{ $ad->id }}" >{{ $ad->name }}</option>
                                                @endif
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
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/task/edit.js') }}"></script>
@endsection