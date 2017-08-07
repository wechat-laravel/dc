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
                        <div style="margin-bottom: 10px;">
                            <ul class="nav nav-tabs">
                                <li role="presentation" :class="{active: current === 'admin'}" :click="@onCurr('admin')"><a href="#">管理员充值用户记录</a></li>
                                <li role="presentation" :class="{active: current === 'user'}" :click="@onCurr('user')"><a href="#">微信扫码充值记录</a></li>
                            </ul>
                        </div>

                        <div class="panel panel-default" :visible="current === 'admin'">
                            <div class="panel-heading">管理员充值用户记录</div>
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
                                        <td><span style="color: red">@{{ el.money }}</span></td>
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

                        <div class="panel panel-default" :visible="current === 'user'">
                            <div class="panel-heading">微信扫码充值记录</div>
                            <div class="table-responsive">
                                <table class="table table-bordered no-margin text-center table-hover">
                                    <thead>
                                    <tr>
                                        <th>编号</th>
                                        <th>商户订单号</th>
                                        <th>用户ID</th>
                                        <th>用户邮箱</th>
                                        <th>用户余额</th>
                                        <th>充值数额</th>
                                        <th>支付状态</th>
                                        <th>支付时间</th>
                                        <th>订单创建日期</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ms-for="el in @data" data-for-rendered='@onLoads'>
                                        <td>@{{ el.id }}</td>
                                        <td>@{{ el.out_trade_no  }}</td>
                                        <td>@{{ el.user_id }}</td>
                                        <td>@{{ el.user.email }}</td>
                                        <td>@{{ el.user.balance }}</td>
                                        <td><span style="color: red">@{{ el.total_fee }}</span></td>
                                        <td>成功</td>
                                        <td>@{{ el.pay_time*1000 | date("yyyy-MM-dd HH:mm") }}</td>
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