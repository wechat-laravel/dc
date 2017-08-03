@extends('modules._layout.admin')
@section('title')
    微信群发
@endsection
@section('menu')
    服务插件
@endsection
@section('content')
    <div ms-controller="show">
        <div class="modal setcondition" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">群发任务（ 第一步：设置条件 ）</h4>
                    </div>
                    <form class="condition form">
                        {!! csrf_field() !!}
                        <div class="modal-body">
                            <div class="form-group">
                                <label>指定发送对象</label>
                                <select class="form-control" name="ChatRoom">
                                    <option value="0">不限制</option>
                                    <option value="false">只发给好友</option>
                                    <option value="true">只发给群聊</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>指定性别（ 根据微信资料判断，可能有误差 ）</label>
                                <select class="form-control" name="Sex">
                                    <option value="0">不限制</option>
                                    <option value="1">男</option>
                                    <option value="2">女</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>指定区域（ 根据微信资料判断，可能有误差 ） </label>
                                <div class="form-inline">
                                    <select class="form-control" name="Province" id='prov' ms-on-change="@onCity">
                                        <option value="0">不限制</option>
                                        @foreach( $province as $pro)
                                            <option value="{{ $pro->prov_id }}">{{ $pro->prov_name }}</option>
                                        @endforeach
                                    </select>
                                    <select class="form-control" name="City" style="margin-left: 10px;">
                                        <option value="0">不限制</option>
                                        <option ms-for="ct in @city" ms-attr="{value : ct.id}">@{{ ct.city_name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div id="error-show"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal setmessage" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">群发任务（ 第二步：设置发送内容 ）</h4>
                    </div>
                    <form class="message form" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="modal-body">
                            <p><b>符合条件的好友有：<span style="color: red">@{{ oks }}</span>个</b></p>
                            <hr>
                            <div class="form-group">
                                <label>文字内容</label>
                                <input type="text" name="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>图片内容 （ 可选项 ）</label>
                                <input type="file" name="picture"  multiple="multiple">
                                <p class="help-block">请上传大小在2M以内的图片</p>
                            </div>
                            <div class="form-group">
                                <label>指定发送速度（ 每一个发送的时长 ）</label>
                                <select class="form-control" name="delay">
                                    <option value="12-20">慢（12s - 20s）</option>
                                    <option value="6-11">中（6s  - 11s）</option>
                                    <option value="3-5">快（3s - 5s）</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal send" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">群发任务（ 第三步：确认发送 ）</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-center"><b>发送的内容设置成功！</b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" :click="@onSend">开始发送</button>
                    </div>
                </div>
            </div>
        </div>

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
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <h4><b>扫码登录</b></h4>
                                <div class="thumbnail">
                                    <img id="qr" src="{{ URL::asset('assets/images/wx-pub-tip.png') }}" style="width: 250px;height: 250px;">
                                </div>
                                <div :if="!@login" class="alert alert-success text-center" style="padding:7px 7px;background-color: #3C8DBC; ">@{{ qrmsg }}</div>
                                <div :if="@login" class="btn btn-block btn-success" :click="@onLogout()">已登录（点击退出）</div>
                                <p style="margin-top: 10px;"><b>温馨提示：</b> </p>
                                <p><b>扫码登录后若没有反应，请刷新页面后重试</b></p>
                                <p><b>登录二维码有效时长为15秒，超过请刷新页面</b></p>
                                <p><b>勾选式发送，默认单个发送时长为3-8秒</b></p>
                                <p><b>如有问题，请联系我们的管理员。（公测阶段，暂没有群发数量限制）</b></p>
                            </div>
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <div style="margin-bottom: 8px;margin-top: -5px;">
                                    <button class="btn btn-success" :click="@checkTo()">勾选式群发</button>
                                    <button class="btn btn-success" :click="@onTask()" style="margin-left: 20px;">条件式群发</button>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">微信好友列表
                                        <span :visible="result" style="margin-left: 20px;color:#3C8DBC"><b>当前进行到：@{{ result }}</b></span>
                                    </div>
                                    <div class="table-responsive" style="overflow: auto;height: 600px;">
                                        <table class="table table-bordered no-margin text-center table-hover">
                                            <thead>
                                            <tr>
                                                <th width="20px;"><input type="checkbox" ms-duplex-checked="@allchecked"  data-duplex-changed="@checkAll" style="width: 17px;height: 17px;"></th>
                                                <th>头像</th>
                                                <th>名称</th>
                                                <th>性别</th>
                                                <th>所在地</th>
                                                <th>群聊</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr ms-for="el in @allList">
                                                    <td>
                                                        <input type="checkbox" name="checks" ms-duplex="@checkData" style="width: 17px;height: 17px;" ms-attr="{value: el.UserName}">
                                                    </td>
                                                    <td><img class="lazy" ms-attr="{src : 'http://rzwei.cn:5050/getheadimg?id='+nowId+'&username='+el.UserName}" width="30px;" height="30px;"></td>
                                                    <td>@{{ el.NickName }}</td>
                                                    <td>@{{ el.Sex === 1 ? '男' : '女' }}</td>
                                                    <td>@{{ el.Province }} - @{{ el.City }}</td>
                                                    <td>@{{ el.ChatRoom ? '是' : '否' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
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
    <script src="https://cdn.bootcss.com/jquery_lazyload/1.9.7/jquery.lazyload.js"></script>
    <script src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/service/mass.js') }}"></script>
@endsection