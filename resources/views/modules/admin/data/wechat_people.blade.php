@extends('modules._layout.admin')
@section('content')
    <div ms-controller="show">
        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left header"><i class="fa fa-question-circle"></i>传播关系分析</li>
                        <li class="pull-right"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('day')" aria-expanded="true">表格数据</a></li>
                        <li class="pull-right"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('day')" aria-expanded="true">关系导图</a></li>
                        <li class="pull-right active"><a href="#revenue-chart" data-toggle="tab" :click="@onPUF('day')" aria-expanded="true">脉络图</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="wgt" style="width: 100%;height: 800px;"></div>
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
