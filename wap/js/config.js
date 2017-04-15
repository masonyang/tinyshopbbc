angular.module("starter.config", [])
.constant("ENV", {
    "api": "http://a.git-online.org/index.php?con=api&act=index",
    'imgUrl':"http://a.git-online.org/",
    // "api": "http://a.qqcapp.com/index.php?con=api&act=index",
    // 'imgUrl':"http://a.qqcapp.com/",
    'payList':{'weixin':8, 'appAlipay':7, 'wapAlipay':6}, //支付方式
    'weixin':{appid:'wxabe371a3d4002bc5'},
 });
