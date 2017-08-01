@extends('modules._layout.admin')
@section('title')
    微信群发
@endsection
@section('menu')
    服务插件
@endsection
@section('content')
    <div ms-controller="show">
        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div style="width: 100%;">
                            <div class="page-header">
                                <h4>微信群发助手</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <h4><b>微信好友列表</b></h4>
                                <div class="thumbnail">
                                    <img src="{{ URL::asset('assets/images/wx-pub-tip.png') }}" alt="...">
                                    <div class="caption">
                                        <h3>Thumbnail label</h3>
                                        <p>...</p>
                                        <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <h4><b>扫码登录</b></h4>
                                <div class="thumbnail">
                                    <img id="qr" src="{{ URL::asset('assets/images/wx-pub-tip.png') }}" style="width: 250px;height: 250px;">
                                </div>
                                <div class="alert alert-success text-center" style="padding:7px 7px; ">@{{ qrmsg }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>

@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/service/mass.js') }}"></script>
@endsection