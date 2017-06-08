@extends('modules._layout.admin')
@section('content')
    <div ms-controller="show">
        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left header"><i class="fa fa-question-circle"></i>传播关系分析</li>
                        {{--<li class="pull-right"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('tree')" aria-expanded="true">树状图</a></li>--}}
                        <li class="pull-right"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('wang')" aria-expanded="true">脉络图</a></li>
                        <li class="pull-right active"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('tab')" aria-expanded="true">表格数据</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="wgt" ms-visible="@shows==='wang'" style="width: 100%;height: 800px;"></div>
                        <div ms-visible="@shows==='tab'" style="width: 100%;">
                            <table class="table table-bordered" id="people">
                                <thead>
                                <tr>
                                    <th width="350px;">昵称</th>
                                    <th width="100px;">所在层级</th>
                                    <th>下级层数 / 下级人数</th>
                                    <th>阅读次数</th>
                                    <th>阅读时间</th>
                                    <th>性别</th>
                                    <th>地址</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr ms-for="el in @peoples">
                                        <td :attr="{id:'s'+@el.id}">
                                            <i :if="@el.level_num>0" class="glyphicon glyphicon-triangle-right"></i>
                                            @{{ el.name }}
                                        </td>
                                        <td>@{{ el.level }}</td>
                                        <td>@{{ el.level_num }} / @{{ el.people_num }}</td>
                                        <td>@{{ el.read_num }}</td>
                                        <td>@{{ el.read_at }}</td>
                                        <td>@{{ el.sex_name }}</td>
                                        <td>@{{ el.province }} - @{{ el.city }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/data/wechat_people.js') }}"></script>
@endsection
