@extends('modules._layout.admin')
@section('title')
    任务列表
@endsection
@section('menu')
    任务管理
@endsection
@section('content')
    <div ms-controller="show">
        {{--操作结果提示框--}}
        <div class="modal bs-result-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="mySmallModalLabel">操作提示：</h4>
                    </div>
                    <div class="modal-body" id="infos"></div>
                </div>
            </div>
        </div>

        {{--删除确认框--}}
        <div class="modal bs-delete-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">操作提示：</h4>
                    </div>
                    <div class="modal-body">
                        <p>确定要删除嘛？一经删除不可恢复！</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary" :click="@onDelete()">确定</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 红包领取信息 -->
        <div class="modal fade red bag" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">红包领取详情</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered no-margin text-center table-hover">
                                <thead>
                                <tr>
                                    <th width="60px;">ID</th>
                                    <th>头像</th>
                                    <th>昵称</th>
                                    <th>金额</th>
                                    <th>状态</th>
                                    <th>说明</th>
                                    <th>时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ms-for="el in @rdata" data-for-rendered='@onLoads'>
                                    <td>@{{ el.id }}</td>
                                    <td>
                                        <img ms-attr="{src : el.info.avatar}" alt="" width="30px;" height="30px;">
                                    </td>
                                    <td>@{{ el.info.name }}</td>
                                    <td>@{{ el.total_amount }}</td>
                                    <td>@{{ el.status_name }}</td>
                                    <td>@{{ el.return_msg }}</td>
                                    <td>@{{ el.created_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-content">
                            <div class="jumbotron text-center" :visible="rvisible" >
                                <h4><i class="glyphicon glyphicon-exclamation-sign" style="margin-right: 20px;"></i>抱歉，暂没有领取红包的信息</h4>
                            </div>
                            <nav aria-label="Page navigation" style="text-align: center">
                                <ul class="pagination">
                                    <li :visible="@rcurr > 1">
                                        <a :click="@toPages(rcurr-1)" href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li :for="el in @rpages" :class="{active : @el===@rcurr}">
                                        <a :click="@toPages(el)" href="#">@{{ el }}</a>
                                    </li>
                                    <li :visible="@rcurr < @rlast">
                                        <a :click="@toPages(rcurr+1)" href="#" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">共@{{ rtotal }}条数据</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div style="width: 100%;">
                            <div class="page-header">
                                <h4>传播任务列表</h4>
                            </div>
                            <div class="panel panel-default">
                                <div class="table-responsive">
                                    <table class="table table-bordered no-margin text-center table-hover">
                                    <thead>
                                        <tr>
                                            <th width="60px;">编号</th>
                                            @if(Auth::user()->identity === 'admin')
                                            <th>用户ID</th>
                                            @endif
                                            <th>标题</th>
                                            <th>创建时间</th>
                                            <th>预览</th>
                                            <th>分析查看</th>
                                            <th>红包信息</th>
                                            <th>报名信息</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ms-for="el in @data" data-for-rendered='@onLoads'>
                                            <td>@{{ el.id }}</td>
                                            @if(Auth::user()->identity === 'admin')
                                                <td>@{{ el.user_id }}</td>
                                            @endif
                                            <td>
                                                <a  class="desc" role="button" data-toggle="popover"  data-placement="top"  title="描述详情"  ms-attr="{'data-content':el.title}">
                                                    @{{ el.title | truncate(10) }}
                                                </a>
                                            </td>
                                            <td>@{{ el.created_at*1000 | date("yyyy-MM-dd HH:mm") }}</td>
                                            <td>
                                                <button class="btn btn-default qrcode" data-toggle="popover" data-html="true" title="请打开微信扫一扫" data-placement="bottom" ms-attr="{'data-content':'<img src='+@el.qrcode_url+'>'}">查看</button>
                                            </td>
                                            <td>
                                                <a class="btn btn-danger btn-sm"  ms-attr="{href:'/admin/data/wechat/'+@el.id}" target="_blank" type="button">数据图</a>
                                                <a class="btn btn-success btn-sm" ms-attr="{href:'/admin/data/wechat_people/'+@el.id}" target="_blank" type="button">关系图</a>
                                            </td>
                                            <td>
                                                <a :visible="el.red" class="btn btn-warning btn-sm" :click="onRed(el.id)" target="_blank" type="button">领取查看</a>
                                                <a :visible="!el.red" class="btn btn-warning btn-sm" href="#">未设置</a>
                                            </td>
                                            <td>
                                                <a :visible="el.is_ad" class="btn btn-danger btn-sm" ms-attr="{href:'/admin/data/entered/'+@el.id}" target="_blank" type="button">留言查看</a>
                                                <a :visible="!el.is_ad" class="btn btn-danger btn-sm" href="#">未设置</a>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-sm" target="_blank" ms-if="@el.mark==='h5'" ms-attr="{href:'/admin/task/'+@el.id+'/edit'}" role="button">编辑</a>
                                                <a class="btn btn-primary btn-sm" target="_blank" ms-if="@el.mark==='custom'" ms-attr="{href:'/admin/custom/'+@el.id+'/edit'}" role="button">编辑</a>
                                                <a href="#" class="btn btn-danger btn-sm" :click="@onConfirm(el.id)">删除</a>
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
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/task/index.js') }}"></script>
@endsection