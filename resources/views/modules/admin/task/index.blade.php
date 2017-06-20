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
                                            <th width="60px;">编号</th>
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
                                                <button class="btn btn-default qrcode" data-trigger="focus" data-html="true" title="请打开微信扫一扫" data-placement="bottom" ms-attr="{'data-content':'<img src='+@el.qrcode_url+'>'}">点击查看</button>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-sm" target="_blank" ms-if="@el.mark==='h5'" ms-attr="{href:'/admin/task/'+@el.id+'/edit'}" role="button">任务编辑</a>
                                                <a class="btn btn-primary btn-sm" target="_blank" ms-if="@el.mark==='custom'" ms-attr="{href:'/admin/custom/'+@el.id+'/edit'}" role="button">任务编辑</a>
                                            </td>
                                            <td>
                                                <a class="btn btn-danger btn-sm"  ms-attr="{href:'/admin/data/wechat/'+@el.id}" target="_blank" type="button">数据图</a>
                                                <a class="btn btn-success btn-sm" ms-attr="{href:'/admin/data/wechat_people/'+@el.id}" target="_blank" type="button">关系图</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="jumbotron text-center" :visible="visible" >
                            <h4><i class="glyphicon glyphicon-exclamation-sign" style="margin-right: 20px;"></i>抱歉，暂没有数据</h4>
                        </div>
                        <nav aria-label="Page navigation">
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
                                    <a href="#">共@{{ total }}条</a>
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