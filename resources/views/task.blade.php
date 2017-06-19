<!DOCTYPE html>
<html>
<head>
    <title>{{ $task->title }}</title>
    <meta name="DC"content="{{ $task->desc }}">
    <meta name="description"content="{{ $task->desc }}">
    <meta name="viewport" id="viewport" content="width=320, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="{{ URL::asset('assets/js/jquery.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
    <style>
        body{
            height: 100%;
            width: 100%;
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
            top: 0;
            padding: 0;
            margin: 0;
        }
        img{
            width: 100%;
        }
    </style>
</head>
<body>
    @if($task->mark === 'h5')
        <iframe src="{{ $task->page_url }}" frameborder="0" width="100%" height="100%"></iframe>
    @else
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                {!! $task->editorValue !!}
            </div>
        </div>
    @endif
</body>
<script type="text/javascript" charset="UTF-8">
    var mark = Math.random().toString(36).substr(3);
    wx.config(<?=$js->config(['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareQZone'],false);?>);
    wx.ready(function(){
        wx.onMenuShareAppMessage({
            title: '<?=$task->title?>',
            desc: '<?=$task->desc?>',
            link: 'http://www.maoliduo.cn/wechat/task/<?=$task->id?>?openid=<?=$user[0]['id']?>&source=wechat&mark='+mark,
            imgUrl: '<?=$task->img_url?>',
            trigger: function (res) {
                // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                //alert('用户点击发送给朋友');
            },
            success: function (res) {
		        $.ajax({
		            url: 'http://www.maoliduo.cn/wechat/record?openid=<?=$user[0]['id']?>&action=wechat&mark='+mark+'&upper=<?=$upper?>'+'&task_id=<?=$task->id?>',
                    success:function(ret){
                        if(!ret.success){
			                alert(ret.msg);
			            }else{
                            mark = Math.random().toString(36).substr(2);
                        }
		            }
                });
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
        wx.onMenuShareTimeline({
            title: '<?=$task->title?>',
            desc: '<?=$task->desc?>',
            link: 'http://www.maoliduo.cn/wechat/task/<?=$task->id?>?openid=<?=$user[0]['id']?>&source=timeline',
            imgUrl: '<?=$task->img_url?>',
            trigger: function (res) {
                // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                //alert('用户点击发送给朋友');
            },
            success: function (res) {
                $.ajax({
                    url: 'http://www.maoliduo.cn/wechat/record?openid=<?=$user[0]['id']?>&action=timeline&mark='+mark+'&upper=<?=$upper?>'+'&task_id=<?=$task->id?>',
                    success:function(ret){
                        if(!ret.success){
                            alert(ret.msg);
                        }else{
                            mark = Math.random().toString(36).substr(2);
                        }
                    }
                });
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
        wx.onMenuShareQQ({
            title: '<?=$task->title?>',
            desc: '<?=$task->desc?>',
            link: 'http://www.maoliduo.cn/wechat/task/<?=$task->id?>?openid=<?=$user[0]['id']?>&source=qq',
            imgUrl: '<?=$task->img_url?>',
            trigger: function (res) {
                // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                //alert('用户点击发送给朋友');
            },
            success: function (res) {
                $.ajax({
                    url: 'http://www.maoliduo.cn/wechat/record?openid=<?=$user[0]['id']?>&action=qq&mark='+mark+'&upper=<?=$upper?>'+'&task_id=<?=$task->id?>',
                    success:function(ret){
                        if(!ret.success){
                            alert(ret.msg);
                        }else{
                            mark = Math.random().toString(36).substr(2);
                        }
                    }
                });
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
        wx.onMenuShareQZone({
            title: '<?=$task->title?>',
            desc: '<?=$task->desc?>',
            link: 'http://www.maoliduo.cn/wechat/task/<?=$task->id?>?openid=<?=$user[0]['id']?>&source=qzone',
            imgUrl: '<?=$task->img_url?>',
            trigger: function (res) {
                // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                //alert('用户点击发送给朋友');
            },
            success: function (res) {
                $.ajax({
                    url: 'http://www.maoliduo.cn/wechat/record?openid=<?=$user[0]['id']?>&action=qzone&mark='+mark+'&upper=<?=$upper?>'+'&task_id=<?=$task->id?>',
                    success:function(ret){
                        if(!ret.success){
                            alert(ret.msg);
                        }else{
                            mark = Math.random().toString(36).substr(2);
                        }
                    }
                });
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    });

    function stay(){
        $.ajax({
            url : 'http://www.maoliduo.cn/wechat/stay?stay=1'
        })
    }
    setInterval(stay,1000);


</script>

</html>
