wx.config({
    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: 'wx32432d4b782b08c2', // 必填，公众号的唯一标识
    timestamp: 1494986413, // 必填，生成签名的时间戳
    nonceStr: 'qey94m021ik', // 必填，生成签名的随机串
    signature: '4F76593A4245644FAE4E1BC940F6422A0C3EC03E',// 必填，签名，见附录1
    jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});