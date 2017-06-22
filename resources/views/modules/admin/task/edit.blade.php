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
                                    <label>封面图片地址<small>（ 必须是微信素材库的图片 ）</small></label>
                                    <input type="text" name="img_url" class="form-control"  placeholder="请输入封面图片地址" value="{{ $task->img_url }}">
                                </div>
                                <div class="form-group">
                                    <label>H5页面地址</label>
                                    <input type="text" name="page_url" class="form-control"  placeholder="请输入做好的H5页面地址" value="{{ $task->page_url }}">
                                </div>
                                <button type="submit" class="btn btn-default">提交</button>
                            </form>
                        </div>
                        <br>
                        <div class="row">
                            <div id="error-show"></div>
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