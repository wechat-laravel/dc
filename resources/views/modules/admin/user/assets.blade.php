@extends('modules._layout.admin')
@section('title')
    账户资产
@endsection
@section('menu')
    个人中心
@endsection
@section('content')
        <div class="row" ms-controller="show">
            <div class="nav-tabs-custom">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>账户资产</strong></div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered no-margin text-center">
                                <thead>
                                <tr>
                                    <th>账户余额：</th>
                                    <th>{{ Auth::user()->balance }}</th>

                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>消费总额：</th>
                                        <th>{{ Auth::user()->consume }}</th>
                                    </tr>
                                    <tr>
                                        <th>共计：</th>
                                        <th>{{ Auth::user()->consume + Auth::user()->balance }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>资产记录</strong></div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered no-margin text-center">
                                <thead>
                                    <tr>
                                        <th>编号</th>
                                        <th>行为</th>
                                        <th>文章名称</th>
                                        <th>用户</th>
                                        <th>余额</th>
                                        <th>时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ms-for="el in @data" data-for-rendered='@onLoads'>
                                        <td>@{{ el.id }}</td>
                                        <td>@{{ el.mark_name }}</td>
                                        <td>@{{ el.task ? el.task.title : ''}}</td>
                                        <td><img :if="el.user" ms-attr="{src : el.user.avatar}" style="width: 20px;height: 20px;margin-right: 5px;">
                                           @{{ el.user ? el.user.name : ''}}
                                        </td>
                                        <td>@{{ el.mark === 'trun' || el.mark === 'recharge' ? ' + '+el.money  : ' - '+el.money }}</td>
                                        <td>@{{ el.created_at*1000 | date("yyyy-MM-dd HH:mm") }}</td>
                                    </tr>
                                </tbody>
                            </table>
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
        </div>
@endsection
@section('afterScript')
        <script type="text/javascript" src="{{ URL::asset('assets/js/admin/user/assets.js') }}"></script>
@endsection