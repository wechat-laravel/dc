@extends('modules._layout.admin')
@section('title')
    数据图 <a href="/admin/data/wechat_people/{{ $task_id }}" style="margin-right: 10px;">关系图</a><a href="/admin/data/entered/{{ $task_id }}">报名信息</a>
@endsection
@section('menu')
    任务管理 > {{ $title }}
@endsection
@section('content')
    <div ms-controller="show">
        <input type="hidden" name="task_id" value="{{ $task_id }}">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-blue text-center">
                    <div class="inner">
                        <h4><i class="fa fa-eye" style="margin-right: 10px;"></i>@{{top.pv_num}}</h4>
                        <span class="info-box-text">总浏览量（ PV ）</span>
                    </div>
                    <ul class="list-inline">
                        <li><span class="info-box-text"><small>今日:</small>@{{top.pv_today}}</span></li>
                        <li><span class="info-box-text"><small>昨日:</small>@{{top.pv_yesterday}}</span></li>
                    </ul>
                    {{--<a class="small-box-footer">高于98.2%的H5&nbsp;&nbsp;<i class="fa fa-question-circle"></i></a>--}}
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-green text-center">
                    <div class="inner">
                        <h4><i class="fa fa-users" style="margin-right: 10px;"></i>@{{ top.uv_num }}</h4>
                        <span class="info-box-text">总访客数（ UV ）</span>
                    </div>
                    <ul class="list-inline">
                        <li><span class="info-box-text"><small>今日:</small>@{{ top.uv_today }}</span></li>
                        <li><span class="info-box-text"><small>昨日:</small>@{{ top.uv_yesterday }}</span></li>
                    </ul>
                    {{--<a class="small-box-footer">高于98.2%的H5&nbsp;&nbsp;<i class="fa fa-question-circle"></i></a>--}}
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-yellow text-center">
                    <div class="inner">
                        <h4><i class="fa fa-external-link" style="margin-right: 10px;"></i>@{{ top.share_num }}</h4>
                        <span class="info-box-text">总分享数</span>
                    </div>
                    <ul class="list-inline">
                        <li><span class="info-box-text"><small>今日:</small>@{{ top.share_today }}</span></li>
                        <li><span class="info-box-text"><small>昨日:</small>@{{ top.share_yesterday }}</span></li>
                    </ul>
                    {{--<a class="small-box-footer">高于98.2%的H5&nbsp;&nbsp;<i class="fa fa-question-circle"></i></a>--}}
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-red text-center">
                    <div class="inner">
                        <h4><i class="fa fa-clock-o" style="margin-right:10px;"></i>@{{ top.stay_avg }}s</h4>
                        <span class="info-box-text">平均停留时长</span>
                    </div>
                    <ul class="list-inline">
                        <li><span class="info-box-text"><small>&nbsp;</small></span></li>
                        <li><span class="info-box-text"><small>&nbsp;</small></span></li>
                    </ul>
                    {{--<a class="small-box-footer">高于98.2%的H5&nbsp;&nbsp;<i class="fa fa-question-circle"></i></a>--}}
                </div>
            </div>
        </div>
        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left header"><i class="fa fa-question-circle"></i>PV、UV、分享走势</li>
                        <li class="pull-left active"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('day')" aria-expanded="true">每日走势</a></li>
                        <li class="pull-left"><a href="#sales-chart" data-toggle="tab" :click="@onPUF('hour')" aria-expanded="false">每小时走势</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="puf" style="width: 100%;height: 400px;"></div>
                    </div>
                </div>
            </section>
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left header"><i class="fa fa-question-circle"></i>传播层级</li>
                    </ul>
                    <div class="tab-content">
                        <div id="cbcj" style="width: 100%;height: 400px;"></div>
                    </div>
                </div>
            </section>
            <section class="col-md-6 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left header"><i class="fa fa-question-circle"></i>停留时长分布</li>
                    </ul>
                    <div class="tab-content">
                        <div id="tlsc" style="width: 100%;height: 400px;"></div>
                    </div>
                </div>
            </section>
            <section class="col-md-6 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left header"><i class="fa fa-question-circle"></i>访问时间分布</li>
                    </ul>
                    <div class="tab-content">
                        <div id="fwsj" style="width: 100%;height: 400px;"></div>
                    </div>
                </div>
            </section>
            <section class="col-md-6 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left header"><i class="fa fa-question-circle"></i>PV-微信内来源</li>
                    </ul>
                    <div class="tab-content">
                        <div id="wxly" style="width: 100%;height: 400px;"></div>
                    </div>
                </div>
            </section>
            <section class="col-md-6 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left header"><i class="fa fa-question-circle"></i>分享去向</li>
                    </ul>
                    <div class="tab-content">
                        <div id="fxqx" style="width: 100%;height: 400px;"></div>
                    </div>
                </div>
            </section>
        </div>
    </div>


@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/data/wechat_show.js') }}"></script>
@endsection
