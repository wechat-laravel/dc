@extends('modules._layout.admin')
@section('title')
    红包工具
@endsection
@section('menu')
    服务插件
@endsection
@section('content')
    <div ms-controller="red_bag" class="ms-controller">

        <div class="modal fade bs-example-modal-sm" id="red_turn" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">红包余额转出</h4>
                    </div>
                    <form class="turn form" enctype="multipart/form-data" id="create">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>转出余额</label>
                                <input type="text" name="red_amount"  class="form-control">
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

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    红包工具
                    <button type="button"
                            class="btn btn-block btn-sm btn-success"
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
                            <th>余额</th>
                            <th>充值</th>
                            <th>余额</th>
                            <th>类型</th>
                            <th>红包金额</th>
                            <th>奖励行为</th>
                            <th>上限/个/人</th>
                            <th>发放时间</th>
                            <th>领取</th>
                            <th>状态</th>
                            <th>配置</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ms-for="($index, data) in @red_bag_data">
                            <td>@{{ data.id }}</td>
                            <td>@{{ data.event |truncate(10) }}</td>
                            <td>@{{ data.title ? data.title.title : '文章已被删除' |truncate(10)}}</td>
                            <td>@{{ data.title ? data.title.id : 0}}</td>
                            <td>@{{ data.total }}</td>
                            <td>
                                @{{ data.amount }}
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                        ms-click="@chongzhi($index, data.id, data.title ? data.title.id : 0)"
                                        data-toggle="modal"
                                        data-target="#chongzhiModal">充值</button>
                            </td>
                            <td><a class="btn btn-success btn-sm" ms-click="@zhuanchu(data.id,data.title ? data.title.id : 100)">转出</a></td>
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
                                        class="btn btn-info btn-sm">
                                    详情
                                </button>
                            </td>
                            <td ms-if="data.status == 1">
                                运行中
                            </td>
                            <td ms-if="data.status == 0">
                                已停止
                            </td>
                            <td>
                                <button type="button"
                                        data-toggle="modal"
                                        data-target="#editConfigModal"
                                        ms-click="@editConfig($index,data.id)"
                                        class="btn btn-sm btn-info">修改</button>
                                <button type="button"
                                        ms-if="data.status == 0"
                                        ms-click="start(data.id)"
                                        class="btn btn-sm btn-info">开启</button>
                                <button type="button"
                                        ms-if="data.status == 1"
                                        ms-click="stop(data.id)"
                                        class="btn btn-sm btn-danger">停止</button>
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
                                <div class="form-group guding" id="guding">
                                    <label>红包金额(红包金额不能小于1)</label>
                                    <input type="number" class="form-control"
                                           name="money"
                                           placeholder="请输入红包金额，红包金额最大不超过200">
                                </div>
                                <div class="form-group suiji" id="suiji" style="display: none">
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
                                    <label>发放条件</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="offer"
                                                   checked="checked"
                                                   value="1">
                                            分享后立即发放
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="offer"
                                                   value="2">
                                            分享内容被好友查看再发放
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>性别</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="sex"
                                                   checked="checked"
                                                   value="3">
                                            不限
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="sex"
                                                   value="1">
                                            男
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="sex"
                                                   value="2">
                                            女
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" id="suiji">
                                    <label>区域</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="area"
                                                   checked="checked"
                                                   ms-click="@area(0)"
                                                   value="0">
                                            不限
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="area"
                                                   ms-click="@area(1)"
                                                   value="1">
                                            指定区域
                                        </label>
                                    </div>
                                    <div class="box-body area" style="display:none">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <select class="form-control province" name="province">
                                                    <option ms-for="province_data in @province"
                                                            ms-attr="{value: province_data.prov_id}">
                                                        @{{ province_data.prov_name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-xs-3">
                                                <select class="form-control" multiple name="city[]">
                                                    <option ms-for="($index, city) in @city">
                                                        @{{ city.city_name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>单个用户领取红包上限</label>
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
                                    <label>红包活动名称</label>
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
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <label>昵称</label>
                                            <input name="name" class="form-control" type="text">
                                        </div>&nbsp&nbsp

                                        <div class="form-group">
                                            <label for="exampleInputEmail2">领取状态</label>
                                            <select class="form-control" name="status">
                                                <option value="0">不限</option>
                                                <option value="1">成功</option>
                                                <option value="2">失败</option>
                                            </select>
                                        </div>
                                        <button ms-click="@search" class="btn btn-info">搜索</button>
                                    </div>
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
        <div class="modal fade" id="chongzhiModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" ms-controller="red_bag">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">充值</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box box-info">
                            <div class="box-body">
                                <div class="table-responsive">
                                    <div role="form" id="chongzhiForm">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label>活动名称</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="event"
                                                       readonly
                                                       ms-attr="{value:red_bag_data[article_id].event}">
                                            </div>
                                            <div class="form-group">
                                                <label>充值金额</label>
                                                <input type="number" class="form-control" name="total" placeholder="输入充值金额">
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" id="submit" ms-click="@chongzhiCommit" class="btn btn-primary">提交</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editConfigModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">修改配置</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" id="editConfig">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>活动名称</label>
                                    <input type="text" class="form-control" name="event" ms-attr="{value:red_bag_data[article_id].event}">
                                </div>
                                <div class="form-group">
                                    <label>营销内容</label>
                                    <input type="text"
                                           readonly
                                           class="form-control"
                                           name="article_id"
                                           ms-attr="{value:red_bag_data[article_id].title.title}">
                                </div>
                                <div class="form-group">
                                    <label>红包类型</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="edit_taxonomy"
                                                   ms-duplex="red_bag_data[article_id].taxonomy"
                                                   ms-click="@guding"
                                                   value="1">
                                            固定金额
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="edit_taxonomy"
                                                   ms-duplex="red_bag_data[article_id].taxonomy"
                                                   value="2"
                                                   ms-click="@suiji">
                                            随机金额
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group guding" id="guding">
                                    <label>红包金额(红包金额不能小于1)</label>
                                    <input type="number" class="form-control"
                                           name="money"
                                           ms-attr="{value:red_bag_data[article_id].money}">
                                </div>
                                <div class="form-group suiji" id="suiji" style="display: none">
                                    <label>红包金额(红包金额不能小于1)</label>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <input type="number" class="form-control" name="edit_money_suiji_begin" placeholder="开始">
                                            </div>
                                            <div class="col-xs-1">
                                                --
                                            </div>
                                            <div class="col-xs-3">
                                                <input type="number" class="form-control" name="edit_money_suiji_end" placeholder="结束">
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
                                        <input type="text" class="form-control pull-right"
                                               ms-attr="{value:red_bag_data[article_id].begin_at +' - '+ red_bag_data[article_id].end_at}"
                                               name="begin_at">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>奖励行为</label>
                                    <input type="checkbox"
                                           ms-duplex-checked="red_bag_data[article_id].action != 2"
                                           name="edit_action_form" value="1">分享给好友
                                    <input type="checkbox"
                                           ms-duplex-checked="red_bag_data[article_id].action != 1"
                                           name="edit_action_form" value="2">分享到朋友圈
                                </div>
                                <div class="form-group">
                                    <label>发放条件</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="offer"
                                                   ms-duplex="red_bag_data[article_id].offer"
                                                   checked="checked"
                                                   value="1">
                                            分享后立即发放
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="offer"
                                                   ms-duplex="red_bag_data[article_id].offer"
                                                   value="2">
                                            分享内容被好友查看再发放
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>性别</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="sex"
                                                   ms-duplex="red_bag_data[article_id].sex"
                                                   checked="checked"
                                                   value="3">
                                            不限
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="sex"
                                                   ms-duplex="red_bag_data[article_id].sex"
                                                   value="1">
                                            男
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="sex"
                                                   ms-duplex="red_bag_data[article_id].sex"
                                                   value="2">
                                            女
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" id="suiji">
                                    <label ms-if="red_bag_data[article_id].area == 0">区域</label>
                                    <label ms-if="red_bag_data[article_id].area == 1">区域（已选择：@{{ red_bag_data[article_id].city }}）</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="area"
                                                   checked
                                                   ms-click="@area(0)"
                                                   value="0">
                                            不限
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio"
                                                   name="area"
                                                   ms-click="@area(1)"
                                                   value="1">
                                            指定区域
                                        </label>
                                    </div>
                                    <div class="box-body area" id="area" style="display:none">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <select class="form-control province" name="province" placeholder="省份">
                                                    <option ms-for="province_data in @province"
                                                            ms-attr="{value: province_data.prov_id}">
                                                        @{{ province_data.prov_name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-xs-3">
                                                <select class="form-control" multiple id="city" name="city[]" placeholder="城市">
                                                    <option ms-for="($index, city) in @city">
                                                        @{{ city.city_name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>单个用户领取红包上限</label>
                                    <select class="form-control"
                                            ms-duplex="red_bag_data[article_id].get_limit"
                                            name="get_limit">
                                        <option value="1">一个</option>
                                        <option value="2">两个</option>
                                        <option value="5">五个</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>红包发送名称</label>
                                    <input type="text"
                                           class="form-control"
                                           ms-duplex="red_bag_data[article_id].send_name"
                                           name="send_name"
                                           placeholder="例如 一问信息科技">
                                </div>
                                <div class="form-group">
                                    <label>祝福语</label>
                                    <input type="text"
                                           class="form-control"
                                           name="wishing"
                                           ms-duplex="red_bag_data[article_id].wishing"
                                           placeholder="例如 恭喜发财">
                                </div>
                                <div class="form-group">
                                    <label>红包活动名称</label>
                                    <input type="text"
                                           class="form-control"
                                           name="act_name"
                                           ms-duplex="red_bag_data[article_id].act_name"
                                           placeholder="例如 一问信息转发赢红包">
                                </div>
                                <div class="form-group">
                                    <label>备注信息</label>
                                    <input type="text"
                                           class="form-control"
                                           name="remark"
                                           ms-duplex="red_bag_data[article_id].remark"
                                           placeholder="">
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
