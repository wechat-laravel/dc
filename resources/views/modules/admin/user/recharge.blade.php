@extends('modules._layout.admin')
@section('title')
    余额充值
@endsection
@section('menu')
    个人中心
@endsection
@section('content')
    <div ms-controller="show">
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">通过微信支付 <small>（ 打开微信扫一扫 ）</small></h4>
                    </div>
                    <div class="modal-body">
                        <img  id="qr" src="{{ URL::asset('assets/images/wx-pub-tip.png') }}" width="50%">
                        <img  src="{{ URL::asset('assets/images/wx-pub-tip.png') }}" width="50%">
                    </div>
                </div>
                <div class="alert alert-success" role="alert">
                    提示：在线支付接口手续费4%，线下打款免手续费
                </div>
            </div>
        </div>
        {{--操作结果提示框--}}
        <div class="modal bs-result-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="mySmallModalLabel">操作提示：</h4>
                    </div>
                    <div class="modal-body" id="infos"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="nav-tabs-custom">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>账户（ 余额充值 ）</strong></div>
                    <div class="panel-body">
                        <div class="col-lg-6 col-md-6">
                            <h4><b>当前余额：<span style="color:#00A65A ">{{ Auth::user()->balance }}</span></b></h4>
                            <form class="form">
                                <div class="form-group">
                                    <div id="error-show"></div>
                                </div>
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <label>充值金额 <small>（整数）</small></label>
                                    <input type="text" name="money" class="form-control"  ms-attr="{value : money ? money : '' }"  placeholder="请输入充值金额" >
                                </div>
                                <div class="form-group">
                                    <a class="btn btn-default"  role="button" style="margin-right: 10px;" :click="@onMoney(2000)">2000元</a>
                                    <a class="btn btn-default"  role="button" style="margin-right: 10px;" :click="@onMoney(999)">999元</a>
                                    <a class="btn btn-default"  role="button" style="margin-right: 10px;" :click="@onMoney(888)">888元</a>
                                    <a class="btn btn-default"  role="button" style="margin-right: 10px;" :click="@onMoney(666)">666元</a>
                                    <a class="btn btn-default"  role="button" style="margin-right: 10px;" :click="@onMoney(500)">500元</a>
                                    <a class="btn btn-default"  role="button" style="margin-right: 10px;" :click="@onMoney(100)">100元</a>
                                    <a class="btn btn-default"  role="button" style="margin-right: 10px;" :click="@onMoney(50)">50元</a>
                                </div>
                                <div class="form-group">
                                    <label>充值方式</label>
                                    <br>
                                    <a style="border: 2px solid #00A65A;padding: 10px 5px;line-height: 40px;">
                                        <img src="{{ URL::asset('assets/images/wx_pay.png') }}" style="width: 116px;height: 31px;">
                                    </a>
                                    <span style="color: darkorange"><b>（ 注：在线支付接口手续费4% ）</b></span>
                                </div>
                                <div class="form-group">
                                    <label>备注 <small>（选填）</small></label>
                                    <textarea class="form-control" name="remark" rows="3"></textarea>
                                </div>
                                <input type="submit" class="btn btn-success btn-block">
                            </form>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <h4><b>温馨提示：</b></h4>
                            <p><b>线下打款免手续费</b></p>
                            <p>
                                <ul>
                                    <li>银行支行：中国建设银行上海市呼玛路支行</li>
                                    <li>银行账户：6217001210020202766</li>
                                    <li>账户姓名：蔡庆忠</li>
                                    <li>客服QQ  ：765898961</li>
                                </ul>
                            </p>
                            <p>有问题，联系客服咨询</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('afterScript')
    <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/user/recharge.js') }}"></script>
@endsection