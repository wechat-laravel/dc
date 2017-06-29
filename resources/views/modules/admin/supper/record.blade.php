@extends('modules._layout.admin')
@section('title')
充值记录
@endsection
@section('menu')
管理中心
@endsection
@section('content')
<div ms-controller="show">
    <div class="row">
        <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div style="width: 100%;">
                        <div class="page-header">
                            <h4>账户充值记录</h4>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">充值记录表</div>
                            <div class="table-responsive">
                                <table class="table table-bordered no-margin text-center table-hover">
                                    <thead>
                                    <tr>
                                        <th>编号</th>
                                        <th>用户头像</th>
                                        <th>用户ID</th>
                                        <th>用户邮箱</th>
                                        <th>充值数额</th>
                                        <th>用户余额</th>
                                        <th>备注</th>
                                        <th>操作人ID</th>
                                        <th>操作人邮箱</th>
                                        <th>充值时间</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ms-for="el in @data" data-for-rendered='@onLoads'>
                                        <td>@{{ el.id }}</td>
                                        <td><img ms-attr="{src : el.user.avatar ? el.user.avatar : '/assets/images/user.jpg'}" width="30px;" height="30px;"></td>
                                        <td>@{{ el.user_id  }}</td>
                                        <td>@{{ el.user_email }}</td>
                                        <td>@{{ el.money }}</td>
                                        <td>@{{ el.user.balance }}</td>
                                        <td>@{{ el.remark }}</td>
                                        <td>@{{ el.auth_id }}</td>
                                        <td>@{{ el.auth_email }}</td>
                                        <td>@{{ el.created_at*1000 | date("yyyy-MM-dd HH:mm") }}</td>
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
<script type="text/javascript" src="{{ URL::asset('assets/js/admin/supper/record.js') }}"></script>
@endsection