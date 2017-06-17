@extends('modules._layout.admin')
@section('content')
    <div ms-controller="show">
        <div class="row">
            <div class="nav-tabs-custom">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>自定义任务编辑</strong></div>
                    <div class="panel-body">
                        <div class="bs-example bs-example-images">
                            <form class="create form">
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
                                    <input type="text" name="desc" class="form-control" placeholder="请输入封面描述，长度在100字以内" value="{{ $task->desc }}">
                                </div>
                                <div class="form-group">
                                    <label>封面图片地址<small>（ 必须是微信素材库的图片 ）</small></label>
                                    <input type="text" name="img_url" class="form-control"  placeholder="请输入封面图片地址" value="{{ $task->img_url }}">
                                </div>
                                <div class="form-group">
                                    <label>页面内容编辑</label>
                                    <script id="editor" type="text/plain" style="width:100%;height:500px;">
                                        {!! $task->editorValue !!}
                                    </script>

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
    <script type="text/javascript" src="{{ URL::asset('assets/ueditor/ueditor.config.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/ueditor/ueditor.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/task/custom_edit.js') }}"></script>
@endsection