@extends('modules._layout.admin')
@section('content')
    <div ms-controller="red_bag" class="ms-controller">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    红包工具
                    <button type="button"
                            class="btn btn-block btn-success"
                            style="margin-top: 3%;"
                            data-toggle="modal"
                            data-target="#myModal">
                        添加配置
                    </button>
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>活动名称</th>
                            <th>文章标题</th>
                            <th>文章编号</th>
                            <th>总金额</th>
                            <th>类型</th>
                            <th>红包金额</th>
                            <th>奖励行为</th>
                            <th>上限/个/人/天</th>
                            <th>发放时间</th>
                            <th>领取详情</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ms-for="($index, data) in @red_bag_data">
                            <td>@{{ data.id }}</td>
                            <td>@{{ data.event }}</td>
                            <td>@{{ data.title.title }}</td>
                            <td>@{{ data.title.id }}</td>
                            <td>@{{ data.amount }}</td>
                            <td>@{{ data.taxonomy_name }}</td>
                            <td>@{{ data.money }}</td>
                            <td>@{{ data.action_name }}</td>
                            <td>@{{ data.get_limit }}</td>
                            <td>@{{ data.begin_at }}<br>@{{ data.end_at }}</td>
                            <td>
                                <button type="button"
                                        data-toggle="modal"
                                        ms-click="tasks(data.title.id)"
                                        data-target="#detailModal"
                                        class="btn btn-block btn-info">
                                    领取详情
                                </button>
                            </td>
                            <td ms-if="data.status == 1">
                                <button class="btn btn-default">运行</button>
                            </td>
                            <td ms-if="data.status == 0">
                                <button class="btn btn-default">停止</button>
                            </td>
                            <td>
                                <button type="button"
                                        ms-if="data.status == 0"
                                        ms-click="start(data.id)"
                                        class="btn btn-block btn-info">开启</button>
                                <button type="button"
                                        ms-if="data.status == 1"
                                        ms-click="stop(data.id)"
                                        class="btn btn-block btn-danger">停止</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer clearfix">
                <a href="javascript:void(0)"
                   class="btn btn-sm btn-default btn-flat"
                   ms-click="toPage(current_page-1)"><<
                </a>
                <a  ms-for="page in pageBase" class="btn"
                    :class="[current_page == page && 'btn-info active']"
                    ms-click="toPage(page)"
                    ms-attr="{title:@page}">
                    @{{ page }}
                </a>
                <a href="javascript:void(0)"
                   class="btn btn-sm btn-default btn-flat"
                   ms-click="toPage(current_page+1)">>>
                </a>
            </div>
        </div>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">添加配置</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" id="addConfig">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>活动名称</label>
                                    <input type="text" class="form-control" name="event" placeholder="活动名称只用于后台管理，不在微信中显示">
                                </div>
                                <div class="form-group">
                                    <label>选择营销内容</label>
                                    <select class="form-control" name="article_id">
                                        <option ms-for="el in @article" ms-attr="{value:el.id}">@{{ el.title }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>总金额</label>
                                    <input type="text" class="form-control" name="amount" placeholder="总金额">
                                </div>
                                <div class="form-group">
                                    <label>红包类型</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="taxonomy"
                                                   ms-click="@guding"
                                                   checked="checked"
                                                   value="1">
                                            固定金额
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="taxonomy"
                                                   value="2"
                                                   ms-click="@suiji">
                                            随机金额
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" id="guding">
                                    <label>红包金额(红包金额不能小于1)</label>
                                    <input type="number" class="form-control"
                                           name="money"
                                           placeholder="请输入红包金额，红包金额最大不超过200">
                                </div>
                                <div class="form-group" id="suiji" style="display: none">
                                    <label>红包金额(红包金额不能小于1)</label>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <input type="number" class="form-control" name="money_suiji_begin" placeholder="开始">
                                            </div>
                                            <div class="col-xs-1">
                                                --
                                            </div>
                                            <div class="col-xs-3">
                                                <input type="number" class="form-control" name="money_suiji_end" placeholder="结束">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>红包发放时间</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" name="begin_at">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>奖励行为</label>
                                    <input type="checkbox" name="action_form" value="1">分享给好友
                                    <input type="checkbox" name="action_form" value="2">分享到朋友圈
                                </div>
                                <div class="form-group">
                                    <label>单个用户日领取红包上限</label>
                                    <select class="form-control" name="get_limit">
                                        <option value="1">一个</option>
                                        <option value="2">两个</option>
                                        <option value="5">五个</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>红包发送名称</label>
                                    <input type="text" class="form-control" name="send_name" placeholder="例如 一问信息科技">
                                </div>
                                <div class="form-group">
                                    <label>祝福语</label>
                                    <input type="text" class="form-control" name="wishing" placeholder="例如 恭喜发财">
                                </div>
                                <div class="form-group">
                                    <label>活动名称</label>
                                    <input type="text" class="form-control" name="act_name" placeholder="例如 一问信息转发赢红包">
                                </div>
                                <div class="form-group">
                                    <label>备注信息</label>
                                    <input type="text" class="form-control" name="remark" placeholder="">
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" id="submit" class="btn btn-primary">提交</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" ms-controller="red_log">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">红包领取详情</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box box-info">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table no-margin">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>头像</th>
                                            <th>昵称</th>
                                            <th>金额</th>
                                            <th>状态</th>
                                            <th>说明</th>
                                            <th>时间</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr ms-for="($index, data) in @data">
                                            <td>@{{ data.id }}</td>
                                            <td>
                                                <img style="width: 50px;height: 50px;"
                                                        ms-attr="{ src: @data.info.avatar}">
                                            </td>
                                            <td>@{{ data.info.name }}</td>
                                            <td>@{{ data.total_amount }}</td>
                                            <td>@{{ data.status_name }}</td>
                                            <td>@{{ data.return_msg }}</td>
                                            <td>@{{ data.created_at }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="box-footer clearfix">
                                <a href="javascript:void(0)"
                                   class="btn btn-sm btn-default btn-flat"
                                   ms-click="toPage(current_page-1)"><<
                                </a>
                                <a  ms-for="page in pageBase" class="btn"
                                    :class="[current_page == page && 'btn-info active']"
                                    ms-click="toPage(page)"
                                    ms-attr="{title:@page}">
                                    @{{ page }}
                                </a>
                                <a href="javascript:void(0)"
                                   class="btn btn-sm btn-default btn-flat"
                                   ms-click="toPage(current_page+1)">>>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/moment.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/service/red_bag.js') }}"></script>
    <script type="text/javascript">
        red_bag.getData();
        red_bag.getRedBag();
    </script>
@endsection
