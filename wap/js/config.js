angular.module("starter.config", [])
.factory("ENV", function($location){
    var hostUrl = $location.protocol() + ':/' + $location.host() + '/';
    hostUrl = hostUrl.replace('wap/','');
    // var hostUrl = 'http://a.qqcapp.com/';

    return {
        "api": hostUrl+"index.php?con=api&act=index",
        'imgUrl': hostUrl,
        'H5Url': hostUrl,
        'wxUrl': 'http://test.git-online.org/',
        //'wxUrl': 'http://localhost/quanqiucang/www/',
        'payList':{'weixin':8, 'appAlipay':7, 'wapAlipay':6}, //支付方式
        'weixin':{appid:'wxabe371a3d4002bc5'},
    };
});
