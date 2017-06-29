@extends('modules._layout.admin')
@section('title')
    账户充值
@endsection
@section('menu')
    管理中心
@endsection
@section('content')
    <div ms-controller="show">
        <div class="row">
            <div class="nav-tabs-custom">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>账户充值</strong></div>
                    <div class="panel-body">
                        <form class="form">
                            <div class="form-group">
                                <div id="error-show"></div>
                            </div>
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label>账户邮箱</label>
                                <input type="text" name="user_email" class="form-control"  placeholder="请输入用户邮箱" >
                            </div>
                            <div class="form-group">
                                <label>充值数额</label>
                                <input type="text" name="money" class="form-control" placeholder="请输入充值的数额" >
                            </div>
                            <div class="form-group">
                                <label>确认数额</label>
                                <input type="text" name="confirm" class="form-control"  placeholder="请确认充值的数额" >
                            </div>
                            <div class="form-group">
                                <label>备注</label>
                                <textarea name="remark" rows="3" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">提交修改</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/supper/recharge.js') }}"></script>
@endsection