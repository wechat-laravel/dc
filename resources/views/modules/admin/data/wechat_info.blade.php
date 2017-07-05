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
        <input type="hidden" name="openid" value="{{ $openid }}">
        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div class="page-header">
                            <h4>用户详情</h4>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">用户信息</div>
                            <div class="panel-body">
                                稍安勿躁，稍后开放 红包奖励与备注功能！
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="page-header">
                            <h4>用户来源</h4>
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
                    <div class="tab-content">
                        <div class="page-header">
                            <h4>用户足迹</h4>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">访问过的任务记录</div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table no-margin text-center table-hover">
                                        <thead>
                                        <tr>
                                            <th>任务编号</th>
                                            <th>任务标题</th>
                                            <th>所属层级</th>
                                            <th>下级人数</th>
                                            <th>阅读次数</th>
                                            <th>最后阅读时间</th>
                                            <th>来源</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr ms-for="el in @data" data-for-rendered='@onLoads'>
                                            <td>@{{ el.tasks_id }}</td>
                                            <td>@{{ el.task ? el.task.title : '【该任务已被删除】'}}</td>
                                            <td>@{{ el.level_name }}</td>
                                            <td>@{{ el.people_num }} </td>
                                            <td>@{{ el.read_num }} </td>
                                            <td>@{{ el.read_at }} </td>
                                            <td>
                                                <a :visible="el.task" class="btn btn-default btn-sm" ms-attr="{href:'/admin/data/wechat_info/'+el.tasks_id+'?people_id='+el.id+'&openid='+el.openid}">详情</a>
                                                <a :visible="!el.task" class="btn btn-default btn-sm" >无</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="jumbotron text-center" :visible="visible" >
                            <h4><i class="glyphicon glyphicon-exclamation-sign" style="margin-right: 20px;"></i>抱歉，暂没有数据</h4>
                        </div>
                        <nav aria-label="Page navigation" style="text-align: center">
                            <ul class="pagination">
                                <li :visible="@curr > 1">
                                    <a :click="@toPage(curr-1)" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li :for="el in @pages" :class="{active : @el===@curr}">
                                    <a :click="@toPage(el)" href="#">@{{ el }}</a>
                                </li>
                                <li :visible="@curr < @last">
                                    <a :click="@toPage(curr+1)" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">共@{{ total }}条数据</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/data/wechat_info.js') }}"></script>
@endsection
