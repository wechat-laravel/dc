@extends('modules._layout.admin')
@section('content')
    <div ms-controller="show">
        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left header"><i class="fa fa-question-circle"></i>传播关系分析</li>
                        <li class="pull-right"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('zhuan')" aria-expanded="true">转发客户</a></li>
                        <li class="pull-right"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('ceng')" aria-expanded="true">层级影响力</a></li>
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
                                            <i :class="[@el.level_num>0 ? 'glyphicon glyphicon-triangle-right' : '']"></i>
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
                        <div ms-visible="@shows==='ceng'" style="width: 100%;">
                            <ul class="nav nav-tabs">
                                <li role="presentation" :click="@onLayer(1)" :class="[@layer === 1 ? 'active' : '']"><a href="#">第一级</a></li>
                                <li role="presentation" :click="@onLayer(2)" :class="[@layer === 2 ? 'active' : '']"><a href="#">第二级</a></li>
                                <li role="presentation" :click="@onLayer(3)" :class="[@layer === 3 ? 'active' : '']"><a href="#">第三级</a></li>
                                <li role="presentation" :click="@onLayer(4)" :class="[@layer === 4 ? 'active' : '']"><a href="#">第四级</a></li>
                                <li role="presentation" :click="@onLayer(5)" :class="[@layer === 5 ? 'active' : '']"><a href="#">第五级</a></li>
                                <li role="presentation" :click="@onLayer(6)" :class="[@layer === 6 ? 'active' : '']"><a href="#">第六级</a></li>
                                <li role="presentation" :click="@onLayer(7)" :class="[@layer === 7 ? 'active' : '']"><a href="#">第七级</a></li>
                                <li role="presentation" :click="@onLayer(8)" :class="[@layer === 8 ? 'active' : '']"><a href="#">第八级</a></li>
                                <li role="presentation" :click="@onLayer(9)" :class="[@layer === 9 ? 'active' : '']"><a href="#">第九级</a></li>
                                <li role="presentation" :click="@onLayer(10)" :class="[@layer === 10 ? 'active' : '']"><a href="#">第十级</a></li>
                            </ul>
                            <table class="table table-bordered text-center" style="margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th width="60px;">头像</th>
                                        <th width="200px;">昵称</th>
                                        <th>性别</th>
                                        <th>地区</th>
                                        <th>影响力积分</th>
                                        <th>总阅读次数</th>
                                        <th>最后停留时长</th>
                                        <th>路径</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ms-for="el in @layers">
                                        <td>
                                            <img ms-attr="{src: @el.user.avatar}" class="img-circle" width="30px;" height="30px;"/>
                                        </td>
                                        <td>@{{ el.name }}</td>
                                        <td>@{{ el.sex_name }}</td>
                                        <td>@{{ el.province }} - @{{ el.city }}</td>
                                        <td>@{{ el.read_num*5 + el.people_num*100 }}</td>
                                        <td>@{{ el.read_num }}</td>
                                        <td>@{{ el.single[0].stay }} s</td>
                                        <td><button class="btn btn-default" :click="@onInfo(el.id)">详情</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div ms-visible="@shows==='zhuan'" style="width: 100%;">
                            <table class="table table-bordered text-center">
                                <thead>
                                <tr>
                                    <th width="60px;">头像</th>
                                    <th width="200px;">昵称</th>
                                    <th width="200px;">所属好友</th>
                                    <th>性别</th>
                                    <th>地区</th>
                                    <th>层级</th>
                                    <th>分享(微信/微信群/朋友圈/QQ/QQ空间)</th>
                                    <th>阅读人数(一层)</th>
                                    <th>阅读次数(一层)</th>
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
                                        <td>@{{ el.single.length }} / @{{ el.double.length }} / @{{ el.qqs.length }} / @{{ el.qqzone.length }}</td>
                                        <td>@{{ el.record.length }}</td>
                                        <td>@{{ el.records.length }}</td>
                                        <td><button class="btn btn-default" :click="@onInfo(el.id)">详情</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div ms-visible="@shows==='info'" style="width: 100%;">
                            <div class="page-header">
                                <h4>客户详情</h4>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">最短路径</div>
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
