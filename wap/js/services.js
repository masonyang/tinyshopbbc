angular.module('starter.services', [])

    //微信接口
    .factory('WeiXin', function(ENV, GlobalFun) {
        var appid       = ENV.weixin.appid;

        return {
            getOauthCodeUrl: function(callback, state) {
                callback = encodeURIComponent(callback);
                return "https://open.weixin.qq.com/connect/oauth2/authorize?appid="+appid+"&redirect_uri="+callback+"&response_type=code&scope=snsapi_base&state="+state+"#wechat_redirect";
            },
            wapPay: function(data) {
                var oid = data.oid;
                var url = GlobalFun.getH5Url()+'#/tab/orderdetail/'+oid;

                function onBridgeReady(){
                    WeixinJSBridge.invoke(
                        'getBrandWCPayRequest', {
                            "appId":data.appId,     //公众号名称，由商户传入
                            "timeStamp":data.timeStamp, //时间戳，自1970年以来的秒数
                            "nonceStr":data.nonceStr, //随机串
                            "package":data.package,
                            "signType":data.signType,         //微信签名方式：
                            "paySign":data.paySign, //微信签名
                            "payment_id":data.payment_id, //订单id
                        },
                        function(res){
                            if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                                alert('支付成功');
                            }else{
                                alert('支付失败');
                            }
                            window.location = url;
                        }
                    );
                }
                if (typeof WeixinJSBridge == "undefined"){
                    if( document.addEventListener ){
                        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                    }else if (document.attachEvent){
                        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                    }
                }else{
                    onBridgeReady();
                }
            }
        };
    })

    //自定义方法
    .factory('GlobalFun', function($timeout, $cordovaDevice, $ionicPopup, $ionicActionSheet, ENV, msg, $cordovaClipboard, $location) {
        return {
            getH5Url: $location.protocol() + '://' + $location.host() + '/www',
            GetQueryString: function (name) {
                var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
                var r = window.location.search.substr(1).match(reg);
                if(r!=null)return  unescape(r[2]); return null;
            },
            //判断是否是微信打开返回true
            isWechat: function() {
                var ua = navigator.userAgent.toLowerCase();
                if(ua.match(/MicroMessenger/i)=="micromessenger") {
                    return true;
                }else{
                    return false;
                }
            },
            //判断是否是pc平台
            isPc: function() {
                var userAgentInfo = navigator.userAgent;
                var Agents = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"];
                var flag = true;
                for (var v = 0; v < Agents.length; v++) {
                    if (userAgentInfo.indexOf(Agents[v]) > 0) {
                        flag = false;
                        break;
                    }
                }
                return flag;
            },
            //返回内核平台
            getKernel: function() {
                var u = navigator.userAgent;
                var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //android终端或者uc浏览器
                var isIos = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
                if(isAndroid) {
                    return 'android';
                }else if(isIos) {
                    return 'ios';
                }
                return 'other';
            },
            //获取终端平台
            checkPlatform: function() {
                //system: app weixin h5
                //platform: pc mobile
                //name: android ios weixin h5
                var data = {system:'', platform:'mobile', name:'h5', kernel:'other'};

                document.addEventListener("deviceready", function () {
                    data.system = 'app';
                    data.platform = 'mobile';
                    data.name   = $cordovaDevice.getPlatform();
                }, false);

                if(data.system === '') {
                    if(this.isPc()) {
                        data.platform = 'pc';
                    }

                    if(this.isWechat()) {
                        data.system = 'weixin';
                        data.name   = 'weixin';
                    }else{
                        data.system = 'h5';
                    }
                }
                data.kernel = this.getKernel();
                return data;
            },
            getPlatform: function() {
                var platform = this.checkPlatform();
                return platform;
            },
            openShare: function(system, gid) {
                if(gid <= 0) {
                    return false;
                }
                var url = this.getH5Url+'#/tab/detail/'+gid;

                var weixinShare = function() {
                    var option = {
                        template: "<input type='text' disabled='disabled' value='"+url+"'>",
                        title: "复制链接地址到微信或浏览器打开",
                        buttons: []
                    };
                    if(system == 'app') {
                        option.buttons = [{
                            text: "复制网址",
                            type: "button button-assertive",
                            onTap: function(e) {
                                document.addEventListener("deviceready", function () {
                                    $cordovaClipboard.copy(url).then(function () {
                                        msg('复制成功');
                                    }, function () {
                                        msg('复制失败，请手动复制');
                                    });
                                });
                            }
                        }];
                    }
                    $ionicPopup.show(option);
                };

                weixinShare();
            }
        };
    })

    // 下载App
    .factory('DownloadApp', function(ENV, $http, Des3) {
        return function() {
            var sign = Des3.encrypt({"source":0});
            return $http.get(ENV.api+'&method=wpdownload&sign='+sign);
        };
    })

    // 获取商店基本信息 v2
    .factory('ShopInfoV2', function(ENV, $http, Des3) {
        return function() {
            var sign = Des3.encrypt({"_params":null});
            return $http.get(ENV.api+'&method=siteconf&sign='+sign);
        };
    })

    // 广告位 v2
    .factory('AdvListV2', function(ENV, $http, Des3) {
        return function(apid) {
            var sign = Des3.encrypt('{"apid":'+apid+'}');
            return $http.get(ENV.api+'&method=adpositionv1&sign='+sign);
        };
    })

    // 首页轮播图 v2
    .factory('HomeAdvV2', function(ENV, $http, Des3) {
        return function() {
            var sign = Des3.encrypt({"_params":null});
            return $http.get(ENV.api+'&method=advert&sign='+sign);
        };
    })

    //Des3 加密、解密处理
    .factory('Des3', function() {
        return {
            encrypt: function(data) {
                if(typeof data == 'object') {
                    data = JSON.stringify(data);
                }
                return encodeURI(DES3.encrypt(data)).replace(/\+/g,'%2B');
                //return DES3.encrypt(data);
            }
        };
    })

    //msg提示
    //type 消息类型 0:关闭，1:无背景，2:20秒延时关闭
    .factory('myMsg', function($ionicLoading){
        return function(text, type){
            if(type === 0) {
                $ionicLoading.hide();
                return false;
            }
            var opts = {
                noBackdrop:false, //是否显示背景
                delay:0, //延时显示
                duration:1600 //延时关闭
            };

            if(text !== '' && text !== undefined) {
                opts.template = text;
                if(text.length) {
                    opts.duration = 2500;
                }
            }

            if(type == 1) {
                opts.noBackdrop = true;
            }else if(type == 2) {
                opts.duration = 20000;
            }

            $ionicLoading.show(opts);
        };
    })

    //goto跳转
    //url 跳转url  reload 强制刷新
    .factory('goto', function(msg, $state, $timeout) {
        return function(url, params, reload) {
            if(params === undefined || params === '') {
                params = {};
            }
            $timeout(function() {
                var obj = {reload:false};
                if(reload === true) {
                    obj.reload = true;
                }
                $timeout(function() {
                    $state.go(url, params, reload);
                }, 1500);
            });
        };
    })


    //检查更新
    .factory('checkUpdate', function($rootScope, ENV, $resource, $timeout, $ionicActionSheet, $ionicLoading, $cordovaAppVersion, $ionicPopup, $cordovaFileTransfer, $cordovaFile, $cordovaFileOpener2, $cordovaNetwork, msg) {

        return {
            get: function() {
                var AppVersionCode = 0, ServerUrl='';
                var sign = DES3.encrypt('{"_params":null}');
                $resource(ENV.api).get({method:'siteconf',sign:sign}, function(r){
                    var res = r.data.app_info;

                    if(res.android_appversion !== '' && res.android_download_url) {
                        AppVersionCode = res.android_appversion;
                        ServerUrl = res.android_download_url;

                        document.addEventListener("deviceready", function () {

                            //获取本地APP版本
                            $cordovaAppVersion.getVersionNumber().then(function (version) {
                                var nowVersionNum = parseInt(version.toString().replace(new RegExp(/(\.)/g), '0'));
                                var newVersionNum = parseInt(AppVersionCode.toString().replace(new RegExp(/(\.)/g), '0'));
                                var content = '版本升级至'+AppVersionCode;
                                if(res.android_content !== '') {
                                    content = res.android_content;
                                }

                                if (newVersionNum > nowVersionNum) {
                                    $ionicPopup.confirm({
                                        title: '版本升级',
                                        template: content,
                                        cancelText: '取消',
                                        okText: '升级'
                                    }).then(function (res) {
                                        if (res) {
                                            $ionicLoading.show({
                                                template: "已经下载：0%"
                                            });
                                            var url = ServerUrl; // 下载地址
                                            var path = cordova.file.externalDataDirectory;
                                            //var filename = r.data.site_name+AppVersionCode+'.apk';
                                            var filename = r.data.site_name+'.apk';
                                            var targetPath = path+filename;
                                            var trustHosts = true;
                                            var options = {};
                                            $cordovaFileTransfer.download(url, targetPath, options, trustHosts).then(function (result) {
                                                $cordovaFileOpener2.open(targetPath, 'application/vnd.android.package-archive').then(function () {// 成功
                                                }, function (err) {//失败
                                                });
                                                $ionicLoading.hide();
                                            }, function (err) {
                                                $ionicLoading.show({
                                                    template: "下载失败"
                                                });
                                                $timeout(function () {
                                                    $ionicLoading.hide();
                                                }, 2000);
                                            }, function (progress) {
                                                $timeout(function () {
                                                    var downloadProgress = (progress.loaded / progress.total) * 100;
                                                    $ionicLoading.show({
                                                        template: "正在下载：" + Math.floor(downloadProgress) + "%"
                                                    });
                                                    if (downloadProgress > 99) {
                                                        $ionicLoading.hide();
                                                    }
                                                });
                                            });
                                        }
                                    });
                                }
                            });

                        }, false);

                    }

                });
            }
        };

    })

    //获取短信验证码 v2
    .factory('Sms', function(ENV, $http, msg) {
        return function(mobile, rand, source_type) {
            var sign = DES3.encrypt('{"mobile":"'+mobile+'", "rand":"'+rand+'", "source_type":"'+source_type+'"}');
            sign = encodeURI(sign).replace(/\+/g,'%2B');
            var data = '&method=sms&sign='+sign;
            return $http.get(ENV.api+'&method=sms&sign='+sign);
        };
    })

    //极光推送
    .factory('jpushService', function($http, $window, $document){
        var jpushServiceFactory={};

        //获取状态
        var _isPushStopped=function(fun){
            $window.plugins.jPushPlugin.isPushStopped(fun);
        };
        //var jpushapi=$window.plugins.jPushPlugin;

        //启动极光推送
        var _init=function(notificationCallback){
            $window.plugins.jPushPlugin.init();
            //设置tag和Alias触发事件处理
            // document.addEventListener('jpush.setTagsWithAlias',config.stac,false);
            //打开推送消息事件处理
            // $window.plugins.jPushPlugin.openNotificationInAndroidCallback=notificationCallback;
            document.addEventListener("jpush.openNotification", notificationCallback, false);
            $window.plugins.jPushPlugin.setDebugMode(true);
        };
        //停止极光推送
        var _stopPush=function(){
            $window.plugins.jPushPlugin.stopPush();
        };

        //重启极光推送
        var _resumePush=function(){
            $window.plugins.jPushPlugin.resumePush();
        };

        //设置标签和别名
        var _setTagsWithAlias=function(tags,alias){
            $window.plugins.jPushPlugin.setTagsWithAlias(tags,alias);
        };

        //设置标签
        var _setTags=function(tags){
            $window.plugins.jPushPlugin.setTags(tags);
        };

        //设置别名
        var _setAlias=function(alias){
            $window.plugins.jPushPlugin.setAlias(alias);
        };

        //设置ios Badge
        var _setIosBadge=function(value){
            $window.plugins.jPushPlugin.setApplicationIconBadgeNumber(value);
        };

        jpushServiceFactory.init=_init;
        jpushServiceFactory.isPushStopped=_isPushStopped;
        jpushServiceFactory.stopPush=_stopPush;
        jpushServiceFactory.resumePush=_resumePush;

        jpushServiceFactory.setTagsWithAlias=_setTagsWithAlias;
        jpushServiceFactory.setTags=_setTags;
        jpushServiceFactory.setAlias=_setAlias;

        jpushServiceFactory.setIosBadge=_setIosBadge;

        return jpushServiceFactory;
    })

    // 验证表单字段方法
    .factory('checkField', function() {
        return {
            isEmpty: function(str) {//是否为空
                return (str === '' || str === undefined || str == '0') ? true : false;
            },
            isMobile: function(str) {//是否为手机号码
                if(!str || str.length != 11) {
                    return true;
                }
                return false;
            }
        };
    })

    //我的购物车 v2
    .factory('MyCartFactory', function(ENV, $http, Des3) {

        return {
            getCartCount: function(uid) {
                var sign = Des3.encrypt({uid:uid, source:'scount', sign:sign});
                return $http.get(ENV.api+'&method=carts&sign='+sign);
            },
            addCart: function(uid, pro_num, pro_no) {
                var sign = Des3.encrypt({uid:uid, pro_num:pro_num, pro_no:pro_no});
                return $http.get(ENV.api+'&method=carts&source=addcart&sign='+sign);
            },
            removeCart: function(uid, pro_num, pro_no) {
                var sign = Des3.encrypt({uid:uid, pro_num:pro_num, pro_no:pro_no});
                return $http.post(ENV.api+'&method=carts&source=removecart&sign='+sign);
            },
            getCartList: function(uid) {
                var sign = Des3.encrypt({uid:uid});
                return $http.get(ENV.api+'&method=carts&source=cindex&sign='+sign);
            },
            checkFreight: function(uid, addr_id) {
                var sign = Des3.encrypt({uid:uid, addr_id:addr_id});
                return $http.get(ENV.api+'&method=carts&source=changecheckout&sign='+sign);
            }
        };
    })

    //重构获取商品列表
    .factory('NewGoodsList', function($rootScope, ENV, $resource) {
        var result;

        return {
            getTopList: function(name, obj) {
                var hasNext = true; //是否有下一页
                var type    = obj.type  ? obj.type : 1; //搜索类型:1-按商品分类id搜索 2-按关键字搜索 现在是根据商品名进行搜索
                var filter  = obj.filter  ? obj.filter : 0; //筛选条件:1-按id搜索 2-按关键字搜索,
                var order   = obj.order  ? obj.order : 1; //排序方式:1-最新 2-价格 3-销量 4-人气
                var sort    = obj.sort ? obj.sort : 'desc';
                var limit   = obj.limit  ? obj.limit : 10; //每次请求加载的数据条数
                var iscount = obj.iscount  ? obj.iscount : false; //是否返回总数
                var page    = obj.page > 1 ? obj.page : 1;

                var sign  = DES3.encrypt('{"type":'+type+', "filter":"'+filter+'", "order":'+order+', "sort":"'+sort+'", "limit":'+limit+', "offset":'+page+', "iscount":'+iscount+'}');

                $resource(ENV.api).get({method:'goods', sign:sign}, function(r) {
                    if(!r.data.goods || r.data.goods.length < limit || r.data.count <= limit) {
                        hasNext = false;
                    }

                    var data = [];
                    if(r.status == 'succ' && r.data.goods) {
                        data = r.data.goods;
                    }
                    result = {
                        'type':type,
                        'filter':filter,
                        'order':order,
                        'sort':sort,
                        'limit':limit,
                        'page':page,
                        'nextPage':2,
                        'hasNext':hasNext,
                        'iscount':iscount,
                        'data':data,
                        'total': r.data.count,
                    };
                    //console.log(moreData);
                    $rootScope.$broadcast('NewGoodsList.List'+name);
                });
            },
            getList: function() {
                if(result === undefined) {
                    return false;
                }
                return result.data;
            },
            getResult: function() {
                if(result === undefined) {
                    return false;
                }
                return result;
            },
            getMoreList: function(name, obj, $scope) {
                if(result === undefined) {
                    return false;
                }
                var type        = result.type;
                var filter      = result.filter;
                var order       = result.order;
                var sort       = result.sort;
                var page        = result.page;
                var limit       = result.limit;
                var iscount     = result.iscount;
                var nextPage    = result.nextPage;
                var hasNext     = result.hasNext;
                var moreData    = result.data;
                var sign  = DES3.encrypt('{"type":'+type+', "filter":"'+filter+'", "order":'+order+', "sort":"'+sort+'", "limit":'+limit+', "offset":'+nextPage+', "iscount":'+iscount+'}');
                $resource(ENV.api).get({method:'goods', sign:sign}, function(r) {

                    if(!r.data.goods || r.data.goods.length < limit || r.data.count <= limit) {
                        hasNext = false;
                    }
                    nextPage++;
                    moreData = moreData.concat(r.data.goods);
                    result = {
                        'type':type,
                        'filter':filter,
                        'order':order,
                        'sort':sort,
                        'limit':limit,
                        'page':page,
                        'nextPage':nextPage,
                        'hasNext':hasNext,
                        'data':moreData,
                        'iscount':iscount,
                        'total': r.data.count,
                    };
                    $rootScope.$broadcast('NewGoodsList.List'+name);
                    $scope.$broadcast("scroll.infiniteScrollComplete");
                });
            },
            hasNext: function() {
                if(result === undefined) {
                    return false;
                }
                return result.hasNext;
            }
        };
    })

    // 获取商品列表
    .factory('GoodsListFactory', function($rootScope, ENV, $resource) {

        var result,
            size   = 10, //每页显示条数
            order  = 1,  //排序方式
            sort   = 'desc',
            filter = 0,  //筛选条件
            type   = 1;  //搜索类型

        var resource = $resource(ENV.api, {}, {
            query: {
                method: 'get',
                params: {method:'goods', sign:'@sign'},
                timeout: 20000
            }
        });

        return {
            getTopList: function(name) {

                var hasNextPage = true;
                var data  = '{"type":'+type+', "filter":'+filter+', "order":'+order+', "sort":"'+sort+'", "limit":'+size+', "offset":1, "iscount":1}';

                var sign  = DES3.encrypt(data);
                resource.query({sign:sign}, function(r) {
                    if(!r.data.goods || r.data.goods.length < size || r.data.count <= size) {
                        hasNextPage = false;
                    }
                    var data = [];
                    if(r.status == 'succ' && r.data.goods) {
                        data = r.data.goods;
                    }
                    result = {
                        'hasNextPage':hasNextPage,
                        'nextPage':2,
                        'size':size,
                        'order':order,
                        'filter':filter,
                        'type':type,
                        'data':data,
                        'sort':sort,
                    };
                    $rootScope.$broadcast('GoodsList.List'+name);
                });
            },
            getList: function() {
                if(result === undefined) {
                    return false;
                }
                return result.data;
            },
            getTopicList: function(name, obj) {
                filter = obj.filter;
                order  = obj.order;
                sort  = obj.sort ? obj.sort : 'desc';
                this.getTopList(name);
            },
            getMoreList: function(name, obj, $scope) {
                if(result === undefined) {
                    return false;
                }

                var hasNextPage = result.hasNextPage;
                var nextPage    = result.nextPage;
                var moreData    = result.data;
                var type        = result.type;
                var filter      = result.filter;
                var order       = result.order;
                var sort        = result.sort;

                var data  = '{"type":'+type+', "filter":'+filter+', "order":'+order+', "sort":"'+sort+'", "limit":'+size+', "offset":'+nextPage+', "iscount":1}';

                var sign  = DES3.encrypt(data);
                resource.query({sign:sign}, function(r) {
                    nextPage++;
                    if(!r.data || r.data.goods.length < size) {
                        hasNextPage = false;
                    }
                    moreData = moreData.concat(r.data.goods);
                    result = {
                        'hasNextPage':hasNextPage,
                        'nextPage':nextPage,
                        'size':size,
                        'order':order,
                        'filter':filter,
                        'type':type,
                        'data':moreData,
                        'sort':sort,
                    };
                    //console.log(moreData);
                    $rootScope.$broadcast('GoodsList.List'+name);
                    $scope.$broadcast("scroll.infiniteScrollComplete");
                });
            },
            hasNextPage: function() {
                if(result === undefined) {
                    return false;
                }
                return result.hasNextPage;
            }
        };
    })

    // 商品详情 v2
    .factory('GoodsInfo', function(ENV, $http, Des3) {
        return {
            GetInfo: function(gid, uid) {
                var sign = Des3.encrypt({id:gid, uid:uid});
                return $http.get(ENV.api+'&method=productsv1&sign='+sign);
            },
            recommendGoods: function(limit) {
                var sign = Des3.encrypt({type:1, sort:'desc', limit:limit});
                return $http.get(ENV.api+'&method=wpproducts&source=recommend&sign='+sign);
            }
        };
    })

    //取消、收藏商品 v2
    .factory('Attention', function(ENV, $http, Des3) {
        var result;
        return {
            add: function(uid, gid) {
                var sign = Des3.encrypt({uid:uid, gid:gid});
                return $http.get(ENV.api+'&method=attention&source=add&sign='+sign);
            },
            Cancel: function(uid, gid) {
                var sign = Des3.encrypt({uid:uid, gid:gid});
                return $http.get(ENV.api+'&method=attention&source=cancel&sign='+sign);
            }
        };
    })

    .factory('Storage', function() {
        return {
            set: function(key, data) {
                return window.localStorage.setItem(key, window.JSON.stringify(data));
            },
            get: function(key) {

                return window.JSON.parse(window.localStorage.getItem(key));
            },
            remove: function(key) {
                return window.localStorage.removeItem(key);
            }
        };
    })

    // 获取地区列表 v2
    .factory('AreaFactory', function(ENV, $http, $cordovaFile) {
        return {
            getArea: function() {
                var sign = DES3.encrypt('{"__params":null}');
                return $http.get(ENV.api+'&method=arealist&v=1&sign='+sign);
            }
        };
    })

    // 获取收货地址 v2
    .factory('AddressFactory', function(ENV, $http, msg, $state, $ionicLoading, User, $rootScope) {
        var result;
        var user = User._User();
        var uid = user.user_id;

        return {
            getAddressList: function() {
                var sign = DES3.encrypt('{"uid":'+uid+'}');
                sign = encodeURI(sign).replace(/\+/g,'%2B');
                $http.get(ENV.api+'&method=customer&source=address&sign='+sign).success(function(r) {
                    result = r.data;
                    $rootScope.$broadcast('AddressFactory.getAddressList');
                });
            },
            changeAddress: function(field) {
                var str, tmp = [];
                for(var key in field) {
                    tmp.push('"'+key+'":"'+field[key]+'"');
                }
                str = tmp.join(', ');
                var sign = DES3.encrypt('{'+str+'}');
                sign = encodeURI(sign).replace(/\+/g,'%2B');
                var data = '&method=customer&source=doaddr&sign='+sign;
                return $http.post(ENV.api+data);
            },
            getOne: function(aid) {
                var sign = DES3.encrypt('{"addr_id":'+aid+'}');
                sign = encodeURI(sign).replace(/\+/g,'%2B');
                return $http.get(ENV.api+'&method=customer&source=addaddr&sign='+sign);
            },
            getResult: function() {
                return result;
            }
        };
    })

    // 订单管理 v2
    .factory('Order', function(ENV, $http, Des3, GlobalFun) {
        var result;
        return {
            //创建订单
            createOrder: function(uid, addr_id, paymentid, user_remark) {
                var sign = Des3.encrypt({uid:uid, addr_id:addr_id, paymentid:paymentid, user_remark:user_remark});
                return $http.post(ENV.api+'&method=carts&source=docheckout&sign='+sign);
            },
            //获取我的订单详情
            getOrderDetail: function(oid) {
                var sign = Des3.encrypt({oid:oid});
                return $http.get(ENV.api+'&method=orders&source=orderdetail&sign='+sign);
            },
            //支付检查订单是否有效
            payCheck: function(uid, oid, paymentid, code) {
                var url = encodeURI(GlobalFun.getH5Url+'#/tab/detail/'+oid);
                var sign = Des3.encrypt({uid:uid, oid:oid, paymentid:paymentid, return_url:url, code:code});
                if(paymentid == 7) {
                    return $http.get(ENV.api+'&method=paylinkv1&source=paylinkv&sign='+sign);
                }else{
                    return $http.get(ENV.api+'&method=wppaylink&source=paylinkv&sign='+sign);
                }
            },
            //获取我的订单列表
            getMyOrder: function(uid, field) {
                var type = [
                    null,
                    'waitpay',   //未支付
                    'delivery',  //已支付
                    'finish',    //已完成
                    //'cancel'     //已作废
                ];

                var data = {uid:uid, status:type[field.status]};

                var sign = Des3.encrypt({uid:uid, status:type[field.status]});
                return $http.get(ENV.api+'&method=orders&source=morders&sign='+sign);
            },
            //取消订单
            CancelOrder: function(oid) {
                var sign = Des3.encrypt({oid:oid});
                return $http.post(ENV.api+'&method=orders&source=ordercancel&sign='+sign);
            }
        };
    })

    //支付工厂
    .factory('Payment', function(ENV, Des3, $http, $ionicLoading, $timeout, msg, $state, $resource) {
        var result = {'code':0, 'msg':'', data:''};
        var errorcode = {
            '9000':'订单支付成功',
            '8000':'正在处理中',
            '4000':'订单支付失败',
            '6001':'用户中途取消',
            '6002':'网络连接出错',
            '6004':'支付结果未知',
            '其它':'其它支付错误，请联系管理员'
        };

        return {
            //支付宝支付
            alipayPay: function(oid, payData) {

                var paymentid    = payData.payment_id;
                var out_trade_no = payData.out_trade_no; //订单号
                var title        = '订单支付：'+payData.out_trade_no; //订单标题
                var body         = payData.subject; //订单内容
                var price        = payData.total_fee; //支付金额
                var notifyUrl    = payData.notify_url; //回调

                // price = 0.01; //支付金额 调试专用

                if(price <= 0) {
                    $state.go('tab.myorder');
                }

                window.alipay.pay({
                    tradeNo: out_trade_no,
                    subject: title,
                    body: body,
                    price: price,
                    notifyUrl: notifyUrl,
                }, function(successResults) {
                    var paydata = successResults;
                    //console.log('memo='+paydata.memo+"\n"+'resultStatus='+paydata.resultStatus+"\n"+'result='+paydata.result);
                    if(paydata.memo === '') {
                        paydata.memo = errorcode[paydata.resultStatus];
                    }

                    $ionicLoading.show({
                        template:paydata.memo,
                        noBackdrop:false,
                        duration: 5000
                    });
                    //同步通知
                    var pay_data = angular.toJson(paydata);

                    var sign = DES3.encrypt('{"paymentid":"'+paymentid+'", "pay_data":'+pay_data+'}');
                    sign = encodeURI(sign).replace(/\+/g,'%2B');
                    $http.post(ENV.api+'&method=paylinkv1&source=syncdopay&sign='+sign).success(function(r) {
                        $ionicLoading.show({
                            template:r.msg,
                            noBackdrop:false,
                            duration: 5000
                        });
                        //console.log(r.msg);
                        $timeout(function(){
                            $ionicLoading.hide();
                            $state.go('tab.orderdetail', {id:oid}, {reload:true});
                        }, 2000);
                    });

                }, function(errorResults) {
                    var data = errorResults;
                    //console.log('memo='+data.memo+"\n"+'resultStatus='+data.resultStatus+"\n"+'result='+data.result);
                    if(data.memo === '') {
                        data.memo = errorcode[data.resultStatus];
                    }
                    $ionicLoading.show({
                        template:data.memo,
                        noBackdrop:false,
                        duration: 1800
                    });
                    $timeout(function(){
                        $state.go('tab.orderdetail', {id:oid}, {reload:true});
                    }, 2000);
                });

            }
        };
    })

    // 用户登陆、退出 v2
    .factory('User', function(ENV, $http, Storage, $ionicHistory, msg, $state, $timeout) {
        var result,
            storageKey = 'User';

        return {
            _login: function(username, password, rand) {
                var sign = DES3.encrypt('{"mobile":'+username+',"password":"'+password+'","rand":"'+rand+'"}');
                sign = encodeURI(sign).replace(/\+/g,'%2B');
                var data = '&method=customer&source=login&sign='+sign;
                $http.post(ENV.api+data).success(function(r) {
                    msg(r.msg);
                    if(r.status == 'succ') {//登陆成功
                        Storage.set(storageKey, r.data);
                        $timeout(function() {
                            if($ionicHistory.backView() === null) {
                                $state.go('tab.user');
                            }else{
                                $ionicHistory.goBack();
                            }
                        }, 1000);
                    }else{
                        return false;
                    }
                });
            },
            _logout: function() {
                result = {};
                Storage.remove(storageKey);
                this.clearView();
                msg('退出成功');
                $state.go('tab.home', {}, {reload:true});  //路由跳转
            },
            _User: function() {
                var user = false;
                if(Storage.get(storageKey)) {
                    user = Storage.get(storageKey);
                }
                return user;
            },
            clearView: function() {
                $ionicHistory.clearHistory();
            },
            _UserInfo: function(uid) {
                var sign = DES3.encrypt('{"uid":'+uid+'}');
                sign = encodeURI(sign).replace(/\+/g,'%2B');
                var data = '&method=customer&source=uinfo&sign='+sign;
                return $http.post(ENV.api+data);
            }
        };
    })

    //注册 v2
    .factory('Register', function(ENV, $http, msg, $state, User) {
        return {
            submit: function(username, password, repassword, smscode, rand) {
                var sign = DES3.encrypt('{"mobile":'+username+',"password":"'+password+'","repassword":"'+repassword+'","smscode":"'+smscode+'","rand":"'+rand+'"}');
                sign = encodeURI(sign).replace(/\+/g,'%2B');
                var data = '&method=customer&source=register&sign='+sign;
                $http.post(ENV.api+data).success(function(r) {
                    msg(r.msg);
                    if(r.status == 'succ') {
                        $state.go('login', {}, {reload:true});
                    }else{
                        return false;
                    }
                });
            },
            upPwd: function(mobile, password, smscode, rand) {
                var sign = DES3.encrypt('{"mobile":'+mobile+', "password":"'+password+'", "smscode":"'+smscode+'", "rand":"'+rand+'"}');
                sign = encodeURI(sign).replace(/\+/g,'%2B');
                var data = '&method=customer&source=forgetpwd&sign='+sign;
                $http.post(ENV.api+data).success(function(r) {
                    msg(r.msg);
                    if(r.status == 'succ') {
                        msg(r.msg);
                        if(User._User() !== false) {
                            User.logout();
                        }
                        $state.go('login', {}, {reload:true});
                    }else{
                        return false;
                    }
                });
            }
        };

    })

    // 分类
    .factory('CategoryFactory', function(ENV, $resource, $rootScope, msg) {

        var result;

        return {
            getList: function() {
                //console.log(result);
                return result;
            },
            getChild: function() {
                var sign = DES3.encrypt('{"_params":null}');
                $resource(ENV.api).get({method:'gcat', source:'scategory', sign:sign}, function(r) {
                    //console.log(r.data);
                    result = r.data;
                    $rootScope.$broadcast('Category.list');
                });
            }
        };
    })

    // 消息提醒
    .factory('msg', ['$timeout', '$ionicLoading', function($timeout, $ionicLoading){
        return function (txt){
            if(!txt)
                return;
            $ionicLoading.show({
                template: txt,
                noBackdrop: true
            });

            $timeout(function(){
                $ionicLoading.hide();
            }, txt.length*80 < 1500?1500: txt.length*80);
        };
    }])

    //go跳转
    .factory('go', function($timeout, $state, $ionicLoading) {
        return function(url, obj) {
            var params = obj.params ? obj.params : '';
            var reload = obj.reload ? obj.reload : false;
            var time   = obj.time ? obj.time : 2000;
            $timeout(function(){
                $ionicLoading.hide();
                $state.go(url, params, {reload:true});
            }, time);
        };
    })

    // 消息提醒
    .factory('loading', function($timeout, $ionicLoading){
        return function (txt){
            if(!txt)
                return;
            $ionicLoading.show({
                template: txt,
                noBackdrop: false
            });

            $timeout(function(){
                $ionicLoading.hide();
            }, txt.length*80 < 1500?1500: txt.length*80);
        };
    });