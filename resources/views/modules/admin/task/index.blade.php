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
                                            <th style="text-align: left;width: 80px;">编号</th>
                                            <th>标题</th>
                                            <th>描述</th>
                                            <th>预览</th>
                                            <th>操作</th>
                                            <th>分析查看</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ms-for="el in @data" data-for-rendered='@onLoads'>
                                            <td>@{{ el.id }}</td>
                                            <td>@{{ el.title }}</td>
                                            <td>@{{ el.desc }}</td>
                                            <td style="width: 70px;padding: 0;margin:0;">
                                                {{--<button class="btn btn-default qrcode" data-trigger="focus" data-html="true" title="请在微信中扫码" data-placement="bottom" data-content="<img src='' class='img-rounded' width='150px;' height='150px;'/>">点击查看</button>--}}
                                                <a ms-attr="{href:@el.qrcode_url}" target="_blank"><img :attr="{src: @el.qrcode_url}" class="img-rounded" style="width: 40px;height: 40px;" title="点击查看大图" /></a>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-sm" ms-attr="{href:'/admin/task/'+@el.id+'/edit'}" role="button">编辑</a>
                                            </td>
                                            <td>
                                                <p>
                                                    <button class="btn btn-danger btn-sm" href="#" type="button">数据图</button>
                                                    <button class="btn btn-success btn-sm" href="#" type="button">关系图</button>
                                                </p>
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