@extends('modules._layout.admin')
@section('content')
    <div ms-controller="show">
        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left header"><i class="fa fa-question-circle"></i>传播关系分析</li>
                        <li class="pull-right"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('zhuan')" aria-expanded="true">转发客户</a></li>
                        <li class="pull-right"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('tab')" aria-expanded="true">表格数据</a></li>
                        <li class="pull-right  active"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('wang')" aria-expanded="true">脉络图</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="wgt" ms-visible="@shows==='wang'" style="width: 100%;height: 800px;"></div>
                        <div ms-visible="@shows==='tab'" style="width: 100%;">
                            <table class="table table-bordered text-center" id="people">
                                <thead>
                                <tr>
                                    <th width="350px;" style="text-align: left">昵称</th>
                                    <th width="100px;">所在层级</th>
                                    <th>下级层数 / 下级人数</th>
                                    <th>阅读次数</th>
                                    <th>最后阅读时间</th>
                                    <th>性别</th>
                                    <th>地址</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr ms-for="el in @peoples">
                                        <td style="text-align: left" :attr="{id:'s'+@el.id}">
                                            <i ms-class="[@el.level_num>0 ? 'glyphicon glyphicon-triangle-right' : '']"></i>
                                            @{{ el.name }}
                                        </td>
                                        <td>@{{ el.level_name }}</td>
                                        <td>@{{ el.level_num }} / @{{ el.people_num }}</td>
                                        <td>@{{ el.read_num }}</td>
                                        <td>@{{ el.read_at }}</td>
                                        <td>@{{ el.sex_name }}</td>
                                        <td>@{{ el.province }} - @{{ el.city }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div ms-visible="@shows==='zhuan'" style="width: 100%;">
                            <table class="table table-bordered text-center" id="people">
                                <thead>
                                <tr>
                                    <th width="100px;" style="text-align: left">头像</th>
                                    <th width="200px;">昵称</th>
                                    <th width="100px;">所属好友</th>
                                    <th>性别</th>
                                    <th>地区</th>
                                    <th>层级</th>
                                    <th>分享方式(微信/微信群/朋友圈/QQ/QQ空间)</th>
                                    <th>阅读人数</th>
                                    <th>阅读次数</th>
                                    <th>路径</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr ms-for="el in @forwards">
                                        <td>
                                            <img ms-attr="{src: @el.user.avatar}" class="img-circle" width="30px;" height="30px;"/>
                                        </td>
                                        <td>@{{ el.name }}</td>
                                        <td>@{{ el.upp ? el.upp.name : '' }}</td>
                                        <td>@{{ el.sex_name }}</td>
                                        <td>@{{ el.province }} - @{{ el.city }}</td>
                                        <td>@{{ el.level_name }}</td>
                                        <td>1/1/1/1</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td><button>路径</button></td>
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
