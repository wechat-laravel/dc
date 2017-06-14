@extends('modules._layout.admin')
@section('content')
    <div ms-controller="red_bag">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    红包工具
                    <button type="button"
                            class="btn btn-block btn-success"
                            ms-on-click="addConfig()"
                            style="margin-top: 3%;">
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


@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/service/red_bag.js') }}"></script>
    <script type="text/javascript">
        red_bag.getData();
    </script>
@endsection
