<!DOCTYPE html>
<html>
<head>
    <title>乐其意-DC</title>
    <meta name="DC"content="测试的哦">
    <meta name="description"content="dc的描述哦">
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
    </style>
</head>
<body>
<iframe src="http://1782176218.scene.eqh5.cn/s/kBXGDzWQ?eqrcode=1" frameborder="0" width="100%" height="100%"></iframe>

</body>
<script type="text/javascript" charset="UTF-8">
    var mark = Math.random().toString(36).substr(2);
    wx.config(<?=$js->config(['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareQZone'],false);?>);
    wx.ready(function(){
//        wx.checkJsApi({
//            jsApiList: [
//                'onMenuShareTimeline',
//                'onMenuShareAppMessage',
//		        'onMenuShareQZone',
//		        'onMenuShareQQ'
//            ],
//            success: function (res) {
//                if(res.checkResult.onMenuShareQQ !== true){
//                   alert('当前设备不支持分享到QQ');
//                }
//                if(res.checkResult.onMenuShareTimeline !== true){
//                    alert('当前设备不支持分享到朋友圈');
//                }
//                if(res.checkResult.onMenuShareAppMessage !== true){
//                    alert('当前设备不支持分享给好友');
//                }
//                if(res.checkResult.onMenuShareQZone !== true){
//                    alert('当前设备不支持分享到QQ空间');
//                }
//            }
//        });
        wx.onMenuShareAppMessage({
            title: '发送给朋友-测试',
            desc: '记录该操作所有浏览和分享转发的操作记录',
            link: 'http://www.maoliduo.cn/wechat/test?openid=<?=$user[0]['id']?>&source=wechat&mark='+mark,
            imgUrl: 'https://mmbiz.qlogo.cn/mmbiz_png/TleSlXOm2myMbs8uDovXxkgIOFKFIfD0kO4m7ZTDgibXoFxmdoeNgFEibCn8dVlyicqwylwTiasssRrdVOGFqYFmYg/0?wx_fmt=png',
            trigger: function (res) {
                // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                //alert('用户点击发送给朋友');
            },
            success: function (res) {
		        $.ajax({
		            url: 'http://www.maoliduo.cn/wechat/record?openid=<?=$user[0]['id']?>&action=wechat&mark='+mark+'&upper=<?=$upper?>',
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
            title: '分享到朋友圈-测试',
            desc: '记录该操作所有浏览和分享转发的操作记录',
            link: 'http://www.maoliduo.cn/wechat/test?openid=<?=$user[0]['id']?>&source=timeline',
            imgUrl: 'https://mmbiz.qlogo.cn/mmbiz_png/TleSlXOm2myMbs8uDovXxkgIOFKFIfD0kO4m7ZTDgibXoFxmdoeNgFEibCn8dVlyicqwylwTiasssRrdVOGFqYFmYg/0?wx_fmt=png',
            trigger: function (res) {
                // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                //alert('用户点击发送给朋友');
            },
            success: function (res) {
                $.ajax({
                    url: 'http://www.maoliduo.cn/wechat/record?openid=<?=$user[0]['id']?>&action=timeline&mark='+mark+'&upper=<?=$upper?>',
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
            title: '分享到QQ-测试',
            desc: '记录该操作所有浏览和分享转发的操作记录',
            link: 'http://www.maoliduo.cn/wechat/test?openid=<?=$user[0]['id']?>&source=qq',
            imgUrl: 'https://mmbiz.qlogo.cn/mmbiz_png/TleSlXOm2myMbs8uDovXxkgIOFKFIfD0kO4m7ZTDgibXoFxmdoeNgFEibCn8dVlyicqwylwTiasssRrdVOGFqYFmYg/0?wx_fmt=png',
            trigger: function (res) {
                // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                //alert('用户点击发送给朋友');
            },
            success: function (res) {
                $.ajax({
                    url: 'http://www.maoliduo.cn/wechat/record?openid=<?=$user[0]['id']?>&action=qq&mark='+mark+'&upper=<?=$upper?>',
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
            title: '分享到QQ空间-测试',
            desc: '记录该操作所有浏览和分享转发的操作记录',
            link: 'http://www.maoliduo.cn/wechat/test?openid=<?=$user[0]['id']?>&source=qzone',
            imgUrl: 'https://mmbiz.qlogo.cn/mmbiz_png/TleSlXOm2myMbs8uDovXxkgIOFKFIfD0kO4m7ZTDgibXoFxmdoeNgFEibCn8dVlyicqwylwTiasssRrdVOGFqYFmYg/0?wx_fmt=png',
            trigger: function (res) {
                // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                //alert('用户点击发送给朋友');
            },
            success: function (res) {
                $.ajax({
                    url: 'http://www.maoliduo.cn/wechat/record?openid=<?=$user[0]['id']?>&action=qzone&mark='+mark+'&upper=<?=$upper?>',
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
