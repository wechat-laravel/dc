@extends('modules._layout.admin')
@section('title')
    微信群发
@endsection
@section('menu')
    服务插件
@endsection
@section('content')
    <div ms-controller="show">
        <div class="modal fade ontask" id="red_turn" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">群发任务</h4>
                    </div>
                    <form class="turn form" enctype="multipart/form-data" id="create">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>文字内容：</label>
                                <input type="text" name="red_amount"  class="form-control">
                            </div>
                            {{--<div class="form-group">--}}
                                {{--<label>图片内容</label>--}}
                                {{--<input type="file" name="img_url"  class="projectfile"  multiple="multiple">--}}
                                {{--<p class="help-block">请上传大小在2M以内的图片</p>--}}
                            {{--</div>--}}
                            <div class="form-group">
                                <label>群发对象</label>
                                <select class="form-control" name="object" id="method" ms-on-change="@onMethod">
                                    <option value="all">发送给全部好友与群聊</option>
                                    <option value="some">发送给部分好友或群聊</option>
                                </select>
                            </div>
                            <div class="form-group" :visible="method === 'some'">
                                <label>指定好友或群聊</label>
                                <select class="form-control" name="city">
                                    <option value="0">不限制</option>
                                    <option value="1">好友</option>
                                    <option value="2">群聊</option>
                                </select>
                            </div>
                            <div class="form-group" :visible="method === 'some'">
                                <label>指定性别</label>
                                <select class="form-control" name="sex" >
                                    <option value="0">不限制</option>
                                    <option value="1">男</option>
                                    <option value="2">女</option>
                                </select>
                            </div>
                            <div class="form-group" :visible="method === 'some'">
                                <label>指定地区</label>
                                <select class="form-control" name="city">
                                    <option value="0">不限制</option>
                                    <option value="1">男</option>
                                    <option value="2">女</option>
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
                            </div>
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <div style="margin-bottom: 8px;margin-top: -5px;">
                                    <button class="btn btn-success" :click="@onTask()">群发消息</button>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">微信好友列表</div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered no-margin text-center table-hover">
                                            <thead>
                                            <tr>
                                                <th>头像</th>
                                                <th>名称</th>
                                                <th>性别</th>
                                                <th>所在地</th>
                                                <th>群聊</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr ms-for="el in @allList">
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
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/service/mass.js') }}"></script>
@endsection