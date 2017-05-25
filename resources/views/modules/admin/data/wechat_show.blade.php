@extends('modules._layout.admin')
@section('content')
    <div ms-controller="show">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-blue text-center">
                    <div class="inner">
                        <h4><i class="fa fa-eye"></i>&nbsp;&nbsp;594655</h4>
                        <span class="info-box-text">总浏览量（ PV ）</span>
                    </div>
                    <ul class="list-inline">
                        <li><span class="info-box-text"><small>今日:</small>90</span></li>
                        <li><span class="info-box-text"><small>昨日:</small>5020</span></li>
                    </ul>
                    <a class="small-box-footer">高于98.2%的H5&nbsp;&nbsp;<i class="fa fa-question-circle"></i></a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-green text-center">
                    <div class="inner">
                        <h4><i class="fa fa-users"></i>&nbsp;&nbsp;100</h4>
                        <span class="info-box-text">总访客数（ UV ）</span>
                    </div>
                    <ul class="list-inline">
                        <li><span class="info-box-text"><small>今日:</small>12</span></li>
                        <li><span class="info-box-text"><small>昨日:</small>6</span></li>
                    </ul>
                    <a class="small-box-footer">高于98.2%的H5&nbsp;&nbsp;<i class="fa fa-question-circle"></i></a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-yellow text-center">
                    <div class="inner">
                        <h4><i class="fa fa-external-link"></i>&nbsp;&nbsp;100</h4>
                        <span class="info-box-text">总分享数</span>
                    </div>
                    <ul class="list-inline">
                        <li><span class="info-box-text"><small>今日:</small>7</span></li>
                        <li><span class="info-box-text"><small>昨日:</small>3</span></li>
                    </ul>
                    <a class="small-box-footer">高于12.2%的H5&nbsp;&nbsp;<i class="fa fa-question-circle"></i></a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-red text-center">
                    <div class="inner">
                        <h4><i class="fa fa-clock-o"></i>&nbsp;&nbsp;28s</h4>
                        <span class="info-box-text">平均停留时长</span>
                    </div>
                    <ul class="list-inline">
                        <li><span class="info-box-text"><small>&nbsp;</small></span></li>
                        <li><span class="info-box-text"><small>&nbsp;</small></span></li>
                    </ul>
                    <a class="small-box-footer">高于98.2%的H5&nbsp;&nbsp;<i class="fa fa-question-circle"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom" style="cursor: move;">
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
