angular.module("starter.config", [])
.factory("ENV", function($location){
    var host = $location.host();

    host = host.replace('wap/','');
        
    var hostUrl = $location.protocol() + '://' + host + '/';
    // var hostUrl = 'http://a.git-online.org/';
    // var hostUrl = 'http://a.qqcapp.com/';
    return {
        "api": hostUrl+"index.php?con=api&act=index",
        'imgUrl': hostUrl,
        'H5Url': hostUrl,
        'payList':{'weixin':8, 'appAlipay':7, 'wapAlipay':6}, //支付方式
        'weixin':{appid:'wxabe371a3d4002bc5'},
    };
});
