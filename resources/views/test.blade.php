<!DOCTYPE html>
<html>
<head>
    <title>Test</title>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 96px;
        }
        /* latin-ext */
        @font-face {
            font-family: 'Lato';
            font-style: normal;
            font-weight: 100;
            src: local('Lato Hairline'), local('Lato-Hairline'), url({{ URL::asset('assets/fonts/eFRpvGLEW31oiexbYNx7Y_esZW2xOQ-xsNqO47m55DA.woff2') }}) format('woff2');
            unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
        }
        /* latin */
        @font-face {
            font-family: 'Lato';
            font-style: normal;
            font-weight: 100;
            src: local('Lato Hairline'), local('Lato-Hairline'), url({{ URL::asset('assets/fonts/GtRkRNTnri0g82CjKnEB0Q.woff2') }}) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">Test</div>
    </div>
</div>
</body>
<script type="text/javascript" charset="UTF-8">
    wx.config(<?=$js->config(['onMenuShareQQ', 'onMenuShareWeibo'],true);?>);
    wx.checkJsApi({
        jsApiList: ['onMenuShareQQ','onMenuShareWeibo'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
        success: function(res) {
            // 以键值对的形式返回，可用的api值true，不可用为false
            // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
            alert({"草":{"噢噢":true},"errMsg":"checkJsApi:ok"});
        }
    });

</script>

</html>
