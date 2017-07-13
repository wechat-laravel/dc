@extends('modules._layout.admin')
@section('title')
    广告栏设置
@endsection
@section('menu')
    服务
@endsection
@section('content')
    <div ms-controller="show">

        <div class="modal fade bs-qrcode-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h5 class="modal-title" id="myModalLabel">长按图片加好友</h5>
                    </div>
                    <img class="img-rounded center-block" id='qrcode' src="{{ URL::asset('ceshi.jpg') }}" style="width: 200px;height: 200px;">
                </div>
            </div>
        </div>

        <div class="row">
            <section class="col-md-12 col-sm-12 col-xs-12 connectedSortable ui-sortable">
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div style="width: 100%;">
                            <div class="page-header">
                                <h4>自定义广告模板</h4>
                            </div>
                            {{--<div class="form-group">--}}
                                {{--<label>样式选择</label>--}}
                                {{--<select class="form-control" name="method" id="method" ms-on-change="@onMethod">--}}
                                    {{--<option value="0">请选择一种样式</option>--}}
                                    {{--<option value="1">固定底部-可报名留言</option>--}}
                                    {{--<option selected="selected" value="2">文章底部-有各种联系方式</option>--}}
                                {{--</select>--}}
                            {{--</div>--}}
                        </div>
                        <div class="row">
                            <form class="create form" enctype="multipart/form-data">
                            <div class="col-md-5 col-sm-12 col-xs-12">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <label>模板名称</label>
                                            <input type="text" name="name" class="form-control"  placeholder="请输入模板名称">
                                        </div>
                                        <div class="form-group">
                                            <label>分享来源</label>
                                            <input type="text" name="share" class="form-control"  placeholder="请输入分享推荐来源">
                                        </div>
                                        <div class="form-group">
                                            <label>广告图标</label>
                                            <input type="file" name="litimg"  class="projectfile"  multiple="multiple">
                                            <p class="help-block">请上传大小在2M以内的图片</p>
                                        </div>
                                        <div class="form-group">
                                            <label>主体名称</label>
                                            <input type="text" name="title" class="form-control" placeholder="请输入公司主体名称">
                                        </div>
                                        <div class="form-group">
                                            <label>主体标签</label>
                                            <input type="text" name="label" class="form-control"  placeholder="请输入主体标签">
                                        </div>
                                        <div class="form-group">
                                            <label>联系电话</label>
                                            <input type="text" name="mobile" class="form-control"  placeholder="请输入主体标签">
                                        </div>
                                        <div class="form-group">
                                            <label>微信二维码 <small>（ 可以是个人或公众号的二维码 ）</small></label>
                                            <input type="file" name="qrcode"  class="projectfile"  multiple="multiple">
                                            <p class="help-block">请上传大小在2M以内的图片</p>
                                        </div>
                                        <div class="form-group">
                                            <label>在线咨询连接</label>
                                            <input type="text" name="chat_url" class="form-control"  placeholder="请输入小标题一">
                                        </div>
                                        <div class="form-group">
                                            <label>标题一</label>
                                            <input type="text" name="one_t" class="form-control"  placeholder="请输入小标题一">
                                        </div>
                                        <div class="form-group">
                                            <label>标题一内容</label>
                                            <input type="text" name="one_d"  class="form-control"  placeholder="请输入小一内容">
                                        </div>
                                        <div class="form-group">
                                            <label>标题一内容链接 <small>（ 选填 ）</small></label>
                                            <input type="text" name="one_d_url"  class="form-control"  placeholder="请输入小一内容">
                                        </div>
                                        <div class="form-group">
                                            <label>标题二</label>
                                            <input type="text" name="two_t" class="form-control"  placeholder="请输入小标题二">
                                        </div>
                                        <div class="form-group">
                                            <label>标题二内容</label>
                                            <input type="text" name="two_d"  class="form-control"  placeholder="请输入小二内容">
                                        </div>
                                        <div class="form-group">
                                            <label>标题二内容链接 <small>（ 选填 ）</small></label>
                                            <input type="text" name="two_d_url"  class="form-control"  placeholder="请输入小一内容">
                                        </div>
                                        <div class="form-group">
                                            <label>标题三</label>
                                            <input type="text" name="three_t" class="form-control"  placeholder="请输入小标题三">
                                        </div>
                                        <div class="form-group">
                                            <label>标题三内容</label>
                                            <input type="text" name="three_d"  class="form-control"  placeholder="请输入小三内容">
                                        </div>
                                        <div class="form-group">
                                            <label>标题三内容链接 <small>（ 选填 ）</small></label>
                                            <input type="text" name="three_d_url"  class="form-control"  placeholder="请输入小一内容">
                                        </div>
                                        <a class="btn btn-primary btn-block" style="height: 100%;" :click="@preview()">预览</a>
                                </div>
                                <div class="col-md-1 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
                                </div>
                                <div class="col-md-5 col-sm-12 col-xs-12">
                                    <h4><b>样式预览</b></h4>
                                    <div style="background-color: #F4F5F5;border: 1px solid #9FA0A2">
                                        <h5 class="text-center"><b>本文由<span style="color: red"> @{{ share ? ' '+share+' ' : '( 分享来源 ) '}}</span>分享推荐</b></h5>
                                        <div style="padding:10px">
                                            <div style="background-color:#FFFFFF;padding: 10px 10px 0px 10px; ">
                                                <img src="{{ URL::asset('wewen.png') }}" class="img-circle center-block" style="width: 40px;height: 40px;">
                                                <h5 class="text-center"><b>@{{ title ? ' '+title+' ' : '（ 主体名称 ）' }}</b><a class="btn btn-default btn-xs">@{{ label ? ' '+label+' ' : '（ 主体标签 ）' }}</a></h5>
                                                <div class="center-block" style="margin-top: 25px;">
                                                    <div class="text-center col-sm-4 col-xs-4" style="float: left;">
                                                        <a href="#" :click="@onMobile" class="btn" style="background-color: orange;width: 40px;height: 40px; border-radius: 20px;">
                                                            <i class="fa fa-phone" style="color:white;background-color:orange;font-size: 25px;line-height: 28px;margin-left: -2px;"></i>
                                                        </a>
                                                        <br>
                                                        打Ta电话
                                                    </div>
                                                    <div class="text-center col-sm-4 col-xs-4" style="float: left;">
                                                        <a class="btn" :click="@onQrcode" style="background-color: #4CB25C;width: 40px;height: 40px; border-radius: 20px;">
                                                            <i class="fa fa-wechat" style="color:white;background-color:#4CB25C;font-size: 20px;line-height: 28px;margin-left: -3px;"></i>
                                                        </a>
                                                        <br>
                                                        微信二维码
                                                    </div>
                                                    <div class="text-center col-sm-4 col-xs-4" style="float: left;">
                                                        <a target="_blank" ms-attr="{href: chat_url ? chat_url : '#'}" class="btn" style="background-color: #337AB7;width: 40px;height: 40px; border-radius: 20px;">
                                                            <i class="fa fa-commenting-o" style="color:white;background-color:#337AB7;font-size: 20px;line-height: 27px;margin-left: -2px;"></i>
                                                        </a>
                                                        <br>
                                                        在线咨询
                                                    </div>
                                                </div>
                                                <div style="clear: both"></div>
                                                <div style="margin-top: 20px;color: #938C8C">
                                                    <p class="text-justify">
                                                    <ul class="list-unstyled">
                                                        <li><b>@{{ one_t ? one_t : '标题一' }}</b> ：<span><b><a ms-attr="{href : one_d_url ? one_d_url : '#'}">@{{ one_d ? one_d : '标题一内容' }}</a></b></span></li>
                                                        <li><b>@{{ two_t ? two_t : '标题二' }}</b> ：<span><b><a ms-attr="{href : two_d_url ? two_d_url : '#'}">@{{ two_d ? two_d : '标题二内容' }}</a></b></span></li>
                                                        <li><b>@{{ three_t ? three_t : '标题三' }}</b> ：<span><b><a ms-attr="{href : three_d_url ? three_d_url : '#'}">@{{ three_d ? three_d : '标题三内容' }}</a></b></span></li>
                                                    </ul>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <input type="submit" class="btn btn-success btn-block" style="height: 100%;" value="确认提交">
                                    <br>
                                    <div class="form-group">
                                        <div id="error-show"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
@endsection
@section('afterScript')
    <script src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/service/ad_create.js') }}"></script>
@endsection