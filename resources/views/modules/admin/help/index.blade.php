@extends('modules._layout.admin')
@section('title')
    使用指南
@endsection
@section('menu')
    使用指南
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="#">更新日志</a></li>
                <li role="presentation"><a href="#">使用教程</a></li>
                <li role="presentation"><a href="#">问题帮助</a></li>
            </ul>
        </div>

        {{--更新日志--}}
        <div class="panel-body">

            {{--每次更新日志记录的开始--}}
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">更新时间：2017-7-13 v1.1.7</h3>
                </div>
                <div class="box-body">
                    <p>新增功能：</p>
                    <ul>
                        <li>服务插件 > 广告栏设置 :添加新的广告模板（自定义模板）</li>
                        <li>自定义模板适用范围不包括H5页面，抓取的微信文章页面、自定义的文章页面都可正常使用</li>
                    </ul>
                    <p>改动功能：</p>
                    <ul>
                        <li>任务管理 > 文章创建 ：添加封面图片地址URL 改为 本地上传图片（该图片用于微信转发分享所显示的图标）</li>
                        <li>原所有创建的任务文章的封面图标 请重新编辑上传，避免转发封面图片不显示</li>
                    </ul>
                </div>
            </div>
            {{--每次更新日志记录的结束--}}

        </div>


    </div>
@endsection