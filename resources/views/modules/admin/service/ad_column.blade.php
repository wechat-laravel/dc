@extends('modules._layout.admin')
@section('title')
    广告栏设置
@endsection
@section('menu')
    服务
@endsection
@section('content')
    <div ms-controller="show">

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">添加一个模板</h4>
                    </div>
                    <form class="create form" enctype="multipart/form-data" id="create">
                        <div class="form-group">
                            <div id="error-show"></div>
                        </div>
                        {!! csrf_field() !!}
                        <div class="modal-body">
                            <div class="form-group">
                                <label>模板名称<small>（ 20字以内 ）</small></label>
                                <input type="text" name="name" class="form-control"  placeholder="请输入模板名称">
                            </div>
                            <div class="form-group">
                                <label>广告图标</label>
                                <input type="file" name="litimg"  class="projectfile" multiple="multiple">
                                <p class="help-block">请上传大小在2M以内的正方形图片</p>
                            </div>
                            <div class="form-group">
                                <label>广告标题 <small>（ 10个字符以内 ）</small></label>
                                <input type="text" name="title" class="form-control"  placeholder="请输入广告标题">
                            </div>
                            <div class="form-group">
                                <label>广告跳转链接</label>
                                <input type="text" name="url" class="form-control"  placeholder="请输入跳转的URL链接">
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

        {{--编辑框--}}
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="editModalLabel">编辑模板</h4>
                    </div>
                    <form class="edit form" enctype="multipart/form-data" id="edit">
                        <div class="form-group">
                            <div id="error-edit"></div>
                        </div>
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" ms-attr="{value : ad.id}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>模板名称<small>（ 20字以内 ）</small></label>
                                <input type="text" name="name" class="form-control"  ms-attr="{value : ad.name}" placeholder="请输入模板名称">
                            </div>
                            <div class="form-group">
                                <label>广告图标 <small>（ 如果上传，则替换掉原来的图片 ）</small></label>
                                <input type="file" name="litimg"  class="projectfile"  multiple="multiple">
                                <p class="help-block">请上传大小在2M以内的正方形图片</p>
                            </div>
                            <div class="form-group">
                                <label>广告标题 <small>（ 10个字符以内 ）</small></label>
                                <input type="text" name="title" class="form-control"  ms-attr="{value : ad.title}" placeholder="请输入广告标题">
                            </div>
                            <div class="form-group">
                                <label>广告跳转链接</label>
                                <input type="text" name="url" class="form-control"  ms-attr="{value : ad.url}" placeholder="请输入跳转的URL链接">
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
                                <h4>广告栏设置</h4>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">添加一个模板</button>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">广告模板</div>
                                <div class="table-responsive">
                                    <table class="table table-bordered no-margin text-center table-hover">
                                        <thead>
                                        <tr>
                                            @if(\Auth::user()->identity === 'admin')
                                            <th>用户ID</th>
                                            @endif
                                            <th>模板ID</th>
                                            <th>模板名称</th>
                                            <th>广告图标</th>
                                            <th>广告标题</th>
                                            <th>广告跳转链接</th>
                                            <th>样式类别</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr ms-for="el in @data" data-for-rendered='@onLoads'>
                                                @if(\Auth::user()->identity === 'admin')
                                                <td>@{{ el.user_id }}</td>
                                                @endif
                                                <td>@{{ el.id }}</td>
                                                <td>@{{ el.name  | truncate(20) }}</td>
                                                <td><img ms-attr="{src : el.litimg}" width="30px;" height="30px;"></td>
                                                <td>@{{ el.title | truncate(20) }}</td>
                                                <td><a ms-attr="{href : el.url }" target="_blank">跳转的地址</a></td>
                                                <td>样式一</td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm" :click="onEdit(el.id)">编辑</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="jumbotron text-center" :visible="visible" >
                            <h4><i class="glyphicon glyphicon-exclamation-sign" style="margin-right: 20px;"></i>抱歉，暂没有数据</h4>
                        </div>
                        <nav aria-label="Page navigation" style="text-align: center">
                            <ul class="pagination">
                                <li :visible="@curr > 1">
                                    <a :click="@toPage(curr-1)" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li :for="el in @pages" :class="{active : @el===@curr}">
                                    <a :click="@toPage(el)" href="#">@{{ el }}</a>
                                </li>
                                <li :visible="@curr < @last">
                                    <a :click="@toPage(curr+1)" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">共@{{ total }}条数据</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </section>
        </div>

    </div>

@endsection
@section('afterScript')
    <script src="{{ URL::asset('assets/js/bootstrapValidator.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/admin/service/ad_column.js') }}"></script>
@endsection