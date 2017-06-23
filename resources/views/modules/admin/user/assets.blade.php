@extends('modules._layout.admin')
@section('title')
    账户资产
@endsection
@section('menu')
    个人中心
@endsection
@section('content')
    <div ms-controller="show">
        <div class="row">
            <div class="nav-tabs-custom">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>账户资产</strong></div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered no-margin text-center">
                                <thead>
                                <tr>
                                    <th>账户余额：</th>
                                    <th>{{ Auth::user()->balance }}</th>

                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>消费总额：</th>
                                        <th>{{ Auth::user()->consume }}</th>
                                    </tr>
                                    <tr>
                                        <th>共计：</th>
                                        <th>{{ Auth::user()->consume + Auth::user()->balance }}</th>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/user/assets.js') }}"></script>
@endsection