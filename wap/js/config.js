angular.module("starter.config", [])
.factory("ENV", function($location){
    var hostUrl = $location.protocol() + '://' + $location.host() + '/';
    var H5Url   = hostUrl;
    hostUrl = hostUrl.replace('wap/','');
    // hostUrl = 'http://a.qqcapp.com/';
    // console.log(hostUrl);

    return {
        "api": hostUrl+"index.php?con=api&act=index",
        'imgUrl': hostUrl,
        'H5Url': hostUrl+'wap/',
        'wxUrl': 'http://zd.qqcapp.com/',
        //'wxUrl': 'http://localhost/quanqiucang/www/',
        'payList':{'weixin':8, 'appAlipay':7, 'wapAlipay':6}, //支付方式
        'weixin':{appid:'wxabe371a3d4002bc5'},
    };
});
