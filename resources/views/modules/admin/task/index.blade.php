@extends('modules._layout.admin')
@section('content')
    <div ms-controller="show">
        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div style="width: 100%;">
                            <div class="page-header">
                                <h4>传播任务列表</h4>
                            </div>
                            <div class="panel panel-default">
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left;width: 80px;">序号</th>
                                            <th>标题</th>
                                            <th>描述</th>
                                            <th>二维码预览</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ms-for="el in @data">
                                            <td>@{{ el.id }}</td>
                                            <td>@{{ el.title }}</td>
                                            <td>@{{ el.desc }}</td>
                                            <td>
                                                <img ms-attr="{src: @el.qrcode_url}" class="img-rounded" width="100px;" height="100px;"/></td>
                                            <td>
                                                <button>传播数据</button>
                                                <button>传播关系</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/task/index.js') }}"></script>
@endsection