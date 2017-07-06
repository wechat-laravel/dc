@extends('modules._layout.admin')
@section('title')
    <a href="/admin/data/wechat/{{ $task->id }}" style="margin-right: 10px;">数据图</a><a href="/admin/data/wechat_people/{{ $task->id }}" style="margin-right: 10px;">关系图</a><a href="/admin/data/entered/{{ $task->id }}">报名信息</a> 用户来源图
@endsection
@section('menu')
    任务管理 > {{ $task->title }}
@endsection
@section('content')
    <div ms-controller="show">
        <div class="modal fade red reward" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">红包发送</h4>
                    </div>
                    <form class="create form" enctype="multipart/form-data" id="create">
                        <div class="form-group">
                            <div id="error-show"></div>
                        </div>
                        {!! csrf_field() !!}
                        <input type="hidden" name="openid" value="{{ $openid }}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>红包发送者名称<small>（ 10个字以内 ）</small></label>
                                <input type="text" name="send_name" class="form-control"  placeholder="请输入红包发送名称">
                            </div>
                            <div class="form-group">
                                <label>红包祝福语<small>（ 10个字以内 ）</small></label>
                                <input type="text" name="wishing" class="form-control"  placeholder="请输入红包祝福语">
                            </div>
                            <div class="form-group">
                                <label>红包活动名称<small>（ 10个字以内 ）</small></label>
                                <input type="text" name="act_name" class="form-control"  placeholder="请输入红包活动名称">
                            </div>
                            <div class="form-group">
                                <label>红包发送金额</label>
                                <input type="text" name="money" class="form-control"  placeholder="请输入红包发送金额">
                            </div>
                            <div class="form-group">
                                <label>备注<small>（ 可填项 ）</small></label>
                                <input type="text" name="remark" class="form-control"  placeholder="请输入备注">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-danger">发送</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{--备注框--}}
        <div class="modal fade user remark" id="user_remark" tabindex="-1" role="dialog" aria-labelledby="userModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="userModalLabel">用户信息备注</h4>
                    </div>
                    <form class="remark form" enctype="multipart/form-data" id="remark">
                        <div class="form-group">
                            <div id="rerror-show"></div>
                        </div>
                        {!! csrf_field() !!}
                        <input type="hidden" name="openid" value="{{ $openid }}">
                        <div class="modal-body">
                            <label>请注意： 微信号与手机号必须填其中一项!</label>
                            @if($user_remark)
                                <div class="form-group">
                                    <label>姓名</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user_remark->name }}"  placeholder="请输入姓名">
                                </div>
                                <div class="form-group">
                                    <label>年龄</label>
                                    <input type="text" name="age"  class="form-control" value="{{ $user_remark->age }}" placeholder="没有可不填">
                                </div>
                                <div class="form-group">
                                    <label>性别</label>
                                    <select class="form-control" name="sex">
                                        @if($user_remark->sex === 0)
                                            <option value="0" selected="selected">不详</option>
                                            <option value="1">男</option>
                                            <option value="2">女</option>
                                        @elseif($user_remark->sex === 1)
                                            <option value="0">不详</option>
                                            <option value="1" selected="selected">男</option>
                                            <option value="2">女</option>
                                        @else
                                            <option value="0">不详</option>
                                            <option value="1">男</option>
                                            <option value="2" selected="selected">女</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>微信号</label>
                                    <input type="text" name="wechat_id" class="form-control" value="{{ $user_remark->wechat_id }}" placeholder="微信号与手机号必须填其中一项!">
                                </div>
                                <div class="form-group">
                                    <label>手机号</label>
                                    <input type="text" name="mobile" class="form-control"  value="{{ $user_remark->mobile }}" placeholder="微信号与手机号必须填其中一项!">
                                </div>
                                <div class="form-group">
                                    <label>备注</label>
                                    <input type="text" name="remark" class="form-control"  value="{{ $user_remark->remark }}" placeholder="没有可不填">
                                </div>
                            @else
                                <div class="form-group">
                                    <label>姓名</label>
                                    <input type="text" name="name" class="form-control"  placeholder="请输入姓名">
                                </div>
                                <div class="form-group">
                                    <label>年龄</label>
                                    <input type="text" name="age"  class="form-control"  placeholder="没有可不填">
                                </div>
                                <div class="form-group">
                                    <label>性别</label>
                                    <select class="form-control" name="sex">
                                        <option value="0">不详</option>
                                        <option value="1">男</option>
                                        <option value="2">女</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>微信号</label>
                                    <input type="text" name="wechat_id" class="form-control"  placeholder="微信号与手机号必须填其中一项!">
                                </div>
                                <div class="form-group">
                                    <label>手机号</label>
                                    <input type="text" name="mobile" class="form-control"  placeholder="微信号与手机号必须填其中一项!">
                                </div>
                                <div class="form-group">
                                    <label>备注</label>
                                    <input type="text" name="remark" class="form-control"  placeholder="没有可不填">
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-success">修改</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
                                <div class="table-responsive">
                                    <table class="table table-bordered no-margin text-center table-hover">
                                        <thead>
                                        <tr>
                                            <th>头像</th>
                                            <th>用户名</th>
                                            <th>性别</th>
                                            <th>城市</th>
                                            <th>设置</th>
                                            <th>功能</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><img src="{{ $user_info->avatar }}" class="img-circle" style="width: 40px;height: 40px;"></td>
                                                <td style="line-height: 40px;">{{ $user_info->name }}</td>
                                                <td style="line-height: 40px;">{{ $user_info->sex_name }}</td>
                                                <td style="line-height: 40px;">{{ $user_info->province }}-{{ $user_info->city }}</td>
                                                <td style="line-height: 40px;"><a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#user_remark">信息备注</a></td>
                                                <td style="line-height: 40px;">
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal">红包奖励</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="page-header">
                            <h4>奖励记录</h4>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">红包奖励记录</div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered no-margin text-center table-hover">
                                        <thead>
                                        <tr>
                                            <th>编号</th>
                                            <th>账户ID</th>
                                            <th>奖励金额</th>
                                            <th>状态</th>
                                            <th>说明</th>
                                            <th>时间</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr ms-for="el in @rdata">
                                                <td>@{{ el.id }}</td>
                                                <td>@{{ el.user_id }}</td>
                                                <td>@{{ el.total_amount }}</td>
                                                <td>@{{ el.status_name }}</td>
                                                <td>@{{ el.return_msg }}</td>
                                                <td>@{{ el.created_at }}</td>
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
                                <li :visible="@rcurr > 1">
                                    <a :click="@rtoPage(rcurr-1)" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li :for="el in @rpages" :class="{active : @el===@rcurr}">
                                    <a :click="@rtoPage(el)" href="#">@{{ el }}</a>
                                </li>
                                <li :visible="@rcurr < @rlast">
                                    <a :click="@rtoPage(rcurr+1)" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">共@{{ rtotal }}条数据</a>
                                </li>
                            </ul>
                        </nav>
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
            </section>
        </div>
    </div>
@endsection
@section('afterScript')
    <script src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/data/wechat_info.js') }}"></script>
@endsection
