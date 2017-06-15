@extends('modules._layout.admin')
@section('content')
    <div ms-controller="red_bag">
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
                            <th>appid</th>
                            <th>商户id</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><a href="pages/examples/invoice.html">OR9842</a></td>
                            <td>Call of Duty IV</td>
                            <td><span class="label label-success">Shipped</span></td>
                            <td>
                                <button type="button" class="btn btn-block btn-success" style="width: 50%;">修改</button>
                                <button type="button" class="btn btn-block btn-danger" style="width: 50%;">删除</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">下一页</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-right">上一页</a>
            </div>
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
                                <input type="text" class="form-control" name="title" placeholder="活动名称只用于后台管理，不在微信中显示">
                            </div>
                            <div class="form-group">
                                <label>选择营销内容</label>
                                <select class="form-control" name="article_id">
                                    <option value="1">文章标题1</option>
                                    <option value="1">文章标题2</option>
                                    <option value="1">文章标题3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>总金额</label>
                                <input type="text" class="form-control" name="amount" placeholder="总金额">
                            </div>
                            <div class="form-group">
                                <label>单个红包金额</label>
                                <input type="text" class="form-control" name="money" placeholder="随机金额：例如 1-2。固定金额：例如 1。红包金额最大不超过200">
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
                                <input type="checkbox" name="action" value="1">转发给好友/群
                                <input type="checkbox" name="action" value="2">分享到朋友圈
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
                                <input type="text" class="form-control" name="act_name" placeholder="例如 推广活动">
                            </div>
                            <div class="form-group">
                                <label>备注信息</label>
                                <input type="text" class="form-control" name="remark" placeholder="">
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/service/red_bag.js') }}"></script>
    <script type="text/javascript">
        red_bag.getData();
    </script>
@endsection
