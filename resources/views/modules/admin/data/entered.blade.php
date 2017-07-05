@extends('modules._layout.admin')
@section('title')
   <a href="/admin/data/wechat/{{ $task_id }}" style="margin-right: 10px;">数据图</a><a href="/admin/data/wechat_people/{{ $task_id }}">关系图</a> 报名信息
@endsection
@section('menu')
    留言查看 > {{ $title }}
@endsection
@section('content')
    <div ms-controller="show">
        <input type="hidden" name="task_id" value="{{ $task_id }}">
        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div style="width: 100%;">
                            <div class="page-header">
                                <h4>用户留言表</h4>
                            </div>
                            <div class="panel panel-default">
                                <div class="table-responsive">
                                    <table class="table no-margin text-center table-hover">
                                        <thead>
                                        <tr>
                                            <th>头像</th>
                                            <th>微信名</th>
                                            <th>姓名</th>
                                            <th>性别</th>
                                            <th>地址</th>
                                            <th>电话</th>
                                            <th>留言备注</th>
                                            <th>留言时间</th>
                                            <th>来源</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr ms-for="el in @data" data-for-rendered='@onLoads'>
                                                <td><img ms-attr="{src: @el.user.avatar}" class="img-circle" width="30px;" height="30px;"/></td>
                                                <td>@{{ el.user.name }}</td>
                                                <td>@{{ el.name }}</td>
                                                <td>@{{ el.sex_name }}</td>
                                                <td>@{{ el.user.province }}-@{{ el.user.city }} </td>
                                                <td>@{{ el.mobile }} </td>
                                                <td>@{{ el.remark }} </td>
                                                <td>@{{ el.created_at*1000 | date("yyyy-MM-dd HH:mm")}} </td>
                                                <td><a class="btn btn-default btn-sm" ms-attr="{href:'/admin/data/wechat_info/{{ $task_id }}?people_id='+el.people.id+'&openid='+el.openid}">详情</a></td>
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
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/data/entered.js') }}"></script>
@endsection