@extends('modules._layout.admin')
@section('title')
    用户管理
@endsection
@section('menu')
    管理中心
@endsection
@section('content')
    <div ms-controller="show">
        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">身份修改</h4>
                    </div>
                    <form class="edit form" enctype="multipart/form-data" id="create">
                        {!! csrf_field() !!}
                        <div class="modal-body">
                            <div class="form-group">
                                <label>用户邮箱</label>
                                <input type="text" name="email" class="form-control"  ms-attr="{value : email}" readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>身份标识</label><br>
                                <select name="identity" id="identity" class="form-control" ms-on-change="@onSelect">
                                    <option  ms-attr="{selected : mark==='visitor' ? 'selected' : '' }" value="visitor">游客</option>
                                    <option  ms-attr="{selected : mark==='vip' ? 'vip' : '' }" value="vip">会员</option>
                                </select>
                            </div>
                            <div class="form-group" :visible="vipshow">
                                <label>有效时间</label><br>
                                <select name="overdue_at" id="overdue_at" class="form-control">
                                    <option  value="1">一个月</option>
                                    <option  value="4">一季度</option>
                                    <option  value="6">半年</option>
                                    <option  value="12">一年</option>
                                </select>
                            </div>
                            <div class="row">
                                <div id="error-show"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="box box-widget">
            <div class="box-header">
                <h3 class="box-title">用户管理</h3>
            </div>
            <div class="box-body">
                <div class="panel panel-default">
                    <div class="panel-heading">用户列表</div>
                    <div class="table-responsive">
                        <table class="table table-bordered no-margin text-center table-hover">
                            <thead>
                            <tr>
                                <th>用户ID</th>
                                <th>头像</th>
                                <th>用户名</th>
                                <th>邮箱</th>
                                <th>微信</th>
                                <th>QQ</th>
                                <th>余额</th>
                                <th>消费总额</th>
                                <th>身份</th>
                                <th>设置</th>
                                <th>注册时间</th>
                                <th>到期时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ms-for="el in @data" data-for-rendered='@onLoads'>
                                <td>@{{ el.id }}</td>
                                <td><img ms-attr="{src : el.avatar ? el.avatar : '/assets/images/user.jpg'}" width="30px;" height="30px;"></td>
                                <td>@{{ el.name  }}</td>
                                <td>@{{ el.email }}</td>
                                <td>@{{ el.wechat_id }}</td>
                                <td>@{{ el.qq }}</td>
                                <td>@{{ el.balance }}</td>
                                <td>@{{ el.consume }}</td>
                                <td>
                                    @{{ el.identity_name }}
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm" :click="@editMark(el.id,el.email,el.identity)">修改</button>
                                </td>
                                <td>@{{ el.created_at*1000 | date("yyyy-MM-dd HH:mm") }}</td>
                                <td>@{{ el.overdue_at*1000 | date("yyyy-MM-dd HH:mm") }}</td>
                            </tr>
                            </tbody>
                        </table>
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
            <!-- /.box-body -->
            <!-- Loading (remove the following to stop the loading)-->
            <div class="overlay" :visible="load">
                <i class="fa fa-spinner fa-spin"></i>
            </div>
            <!-- end loading -->
        </div>
    </div>

@endsection
@section('afterScript')
    <script src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/supper/user.js') }}"></script>
@endsection