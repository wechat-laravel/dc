@extends('modules._layout.admin')
@section('title')
    <a href="/admin/data/wechat/{{ $task->id }}" style="margin-right: 10px;">数据图</a><a href="/admin/data/wechat_people/{{ $task->id }}" style="margin-right: 10px;">关系图</a><a href="/admin/data/entered/{{ $task->id }}">报名信息</a> 用户来源图
@endsection
@section('menu')
    任务管理 > {{ $task->title }}
@endsection
@section('content')
    <div ms-controller="show">
        <input type="hidden" name="task_id" value="{{ $task->id }}">
        <input type="hidden" name="people_id" value="{{ $people_id }}">
        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div class="page-header">
                            <h4>客户详情</h4>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">来源路径</div>
                            <div class="panel-body">
                                <div class="bs-example bs-example-images text-center" data-example-id="image-shapes" ms-for="el in @infos">
                                    <i class="glyphicon glyphicon-arrow-right" style="float: left;margin-top: 15px;"></i>
                                    <a href="#" style="float: left;margin-right: 10px;"><img ms-attr="{src: @el.avatar}" class="img-circle"  style="width: 40px;height: 40px;"/>
                                        <br>
                                        @{{ el.name }}
                                        <br>
                                        @{{ el.created_at.date | truncate(16,'') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/data/wechat_info.js') }}"></script>
@endsection
