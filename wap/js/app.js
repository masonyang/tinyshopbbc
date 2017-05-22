angular.module('starter', ['ionic', 'starter.controllers', 'starter.services', 'starter.config', 'ngResource', 'starter.directive', 'ionic-citypicker', 'ionicLazyLoad', 'ngCordova', 'ionic-native-transitions'])

.run(function($ionicPlatform, $rootScope, $timeout, jpushService, checkUpdate, $cordovaFile, $ionicHistory, $cordovaToast, $cordovaKeyboard, $location, $cordovaNetwork, $cordovaDevice, $state) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);

    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }

    //检测更新
    document.addEventListener("deviceready", function () {
      var platform = $cordovaDevice.getPlatform();
      if(platform == 'Android') {
        checkUpdate.get();
      }
    });
    //检测更新

    //极光推送
    var notificationCallback=function(data){
      var alertContent; //推送的内容
      var id = 0; //商品id
      if(device.platform == "Android") {
        alertContent = event.alert;
        id = event.extras.id;
      } else {
        alertContent = event.aps.alert;
        id = event.id;
        jpushService.setIosBadge(0);
      }

      $timeout(function(){
        if(id > 0) {
          $state.go('tab.detail',{id:id});
        }else{
          $state.go('tab.home');
        }
      });
    };

    jpushService.init(notificationCallback);
    jpushService.setIosBadge(0);

    //极光推送

    // 监听手机网络离线事件
    document.addEventListener("deviceready", function () {
      var isOffline = $cordovaNetwork.isOffline();
      $rootScope.$on('$cordovaNetwork:offline', function(event, networkState){
        if(networkState == 'none') {
          $cordovaToast.showShortBottom('网络已断开');
        }
      });
    }, false);

  });

  //物理返回按钮控制&双击退出应用
  $ionicPlatform.registerBackButtonAction(function (e) {

    //判断处于哪个页面时双击退出
    if ($location.path() == '/tab/home') {
      if ($rootScope.backButtonPressedOnceToExit) {
        ionic.Platform.exitApp();
      } else {
        $rootScope.backButtonPressedOnceToExit = true;
        $cordovaToast.showShortBottom('再按一次退出系统');
        setTimeout(function () {
          $rootScope.backButtonPressedOnceToExit = false;
        }, 2000);
      }
    }else if ($ionicHistory.backView()) {
      //需要单独处理的路由
      if($ionicHistory.backView().stateName === 'tab.done') {
        $state.go('tab.home');
        return false;
      }
      if ($cordovaKeyboard.isVisible()) {
        $cordovaKeyboard.close();
      } else {
        $ionicHistory.goBack();
      }
    }else{
      $rootScope.backButtonPressedOnceToExit = true;
      $cordovaToast.showShortBottom('再按一次退出系统');
      setTimeout(function () {
        $rootScope.backButtonPressedOnceToExit = false;
      }, 2000);
    }
    e.preventDefault();
    return false;
  }, 101);

})


.config(function($stateProvider, $urlRouterProvider, $ionicConfigProvider, $ionicNativeTransitionsProvider,$locationProvider) {

  //全局配置
  $ionicNativeTransitionsProvider.setDefaultOptions({
    duration: 250, // 转场时间,
    slowdownfactor: 4, // overlap views (higher number is more) or no overlap (1), default 4
    iosdelay: -1, // ms to wait for the iOS webview to update before animation kicks in, default -1
    androiddelay: -1, // same as above but for Android, default -1
    winphonedelay: -1, // same as above but for Windows Phone, default -1,
    fixedPixelsTop: 0, // the number of pixels of your fixed header, default 0 (iOS and Android)
    fixedPixelsBottom: 0, // the number of pixels of your fixed footer (f.i. a tab bar), default 0 (iOS and Android)
    triggerTransitionEvent: '$ionicView.afterEnter', // internal ionic-native-transitions option
    backInOppositeDirection: false // Takes over default back transition and state back transition to use the opposite direction transition to go back
  });
  //默认页面切换效果
  $ionicNativeTransitionsProvider.setDefaultTransition({
    type: 'slide',
    direction: 'left'
  });
  //默认返回切换效果
  $ionicNativeTransitionsProvider.setDefaultBackTransition({
    type: 'slide',
    direction: 'right'
  });

  $ionicConfigProvider.scrolling.jsScrolling(true); //开启js原生滚动

  $ionicConfigProvider.views.swipeBackEnabled(false); //IOS 侧边滑动 出现白屏的问题
  $ionicConfigProvider.views.forwardCache(true);

  //Tab 标签设置
  $ionicConfigProvider.platform.ios.tabs.style('standard');
  $ionicConfigProvider.platform.ios.tabs.position('bottom');
  $ionicConfigProvider.platform.android.tabs.style('standard');
  $ionicConfigProvider.platform.android.tabs.position('standard');

  //navBar的标签的对齐方式
  $ionicConfigProvider.platform.ios.navBar.alignTitle('center');
  $ionicConfigProvider.platform.android.navBar.alignTitle('center');

  //切换页面的转换方式
  $ionicConfigProvider.platform.ios.views.transition('ios');
  $ionicConfigProvider.platform.android.views.transition('android');

  //back按钮设置
  $ionicConfigProvider.backButton.text('');
  $ionicConfigProvider.backButton.previousTitleText(false);
  $ionicConfigProvider.platform.ios.backButton.icon('ion-ios-arrow-left');
  $ionicConfigProvider.platform.android.backButton.icon('ion-ios-arrow-back');

  $locationProvider.html5Mode(true);
  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider

  // setup an abstract state for the tabs directive
  .state('tab', {
    url: '/tab',
    abstract: true,
    templateUrl: 'templates/tabs.html',
    controller:'tabsCtrl',
  })

   // 首页
  .state('tab.home', {
    url: '/home',
    nativeTransitions:null,
    views: {
      'tab-home': {
        templateUrl: 'templates/home/home.html',
        controller: 'HomeCtrl'
      }
    }
  })

    .state('login', {
          url: '/login',
          templateUrl: 'templates/login.html',
          controller: 'LoginCtrl'
  })

  // 会员中心
  .state('tab.user', {
    url: '/user',
    nativeTransitions:null,
    views: {
      'tab-user': {
        templateUrl: 'templates/user/user.html',
        controller: 'UserCtrl'
      }
    }
  })

  // 下载页
  .state('downApp', {
    url: '/downApp',
    templateUrl: 'templates/downApp.html',
    controller: 'DownAppCtrl'
  })

  // 注册
  .state('register', {
    url: '/register',
    templateUrl: 'templates/register.html',
    controller: 'RegisterCtrl'
  })

  // 找回密码
  .state('findpwd', {
    url: '/findpwd',
    templateUrl: 'templates/findpwd.html',
    controller: 'FindpwdCtrl'
  })

  // 我的信息
  .state('tab.userinfo', {
    url: '/userinfo',
    views: {
      'tab-user': {
        templateUrl: 'templates/user/userinfo.html',
        controller: 'UserInfoCtrl'
      }
    }
  })

  // 收货地址列表
  .state('tab.addresslist', {
    url: '/addresslist',
    views: {
      'tab-user': {
        templateUrl: 'templates/user/addressList.html',
        controller: 'AddressListCtrl'
      }
    }
  })

  // 添加收货地址
  .state('tab.addaddress', {
    url: '/addaddress',
    views: {
      'tab-user': {
        templateUrl: 'templates/user/addaddress.html',
        controller: 'UpAddressCtrl'
      }
    }
  })

  // 修改收货地址
  .state('tab.editaddress', {
    url: '/editaddress/:id',
    views: {
      'tab-user': {
        templateUrl: 'templates/user/addaddress.html',
        controller: 'UpAddressCtrl'
      }
    }
  })

  // 订单列表页
  .state('tab.myorder', {
        url: '/myorder',
        views: {
          'tab-user': {
            templateUrl: 'templates/user/myOrder.html',
            controller: 'MyOrderCtrl'
          }
        }
  })

  // 订单详情页
  .state('tab.orderdetail', {
    url: '/orderdetail/:id',
    views: {
      'tab-user': {
        templateUrl: 'templates/user/orderDetail.html',
        controller: 'OrderDetailCtrl'
      }
    }
  })


  // 分类页
  .state('tab.category', {
    url: '/category',
    nativeTransitions:null,
    views: {
      'tab-category': {
        templateUrl: 'templates/category/category.html',
        controller: 'CategoryCtrl'
      }
    }
  })

  // 商品列表页
  .state('tab.goodsList', {
    url: '/goodsList/:id',
    // params:{'id':null},
    views: {
      'tab-category': {
        templateUrl: 'templates/category/goodsList.html',
        controller: 'GoodsListCtrl'
      }
    }
  })

  // 搜索列表
  .state('tab.search', {
    url: '/search/:keyword',
    views: {
      'tab-category': {
        templateUrl: 'templates/category/search.html',
        controller: 'SearchCtrl'
      }
    }
  })

  // 详情页
  .state('tab.detail', {
    url: '/detail/:id',
    views: {
      'tab-category': {
        templateUrl: 'templates/category/detail.html',
        controller: 'DetailCtrl'
      }
    }
  })

  // 购物车
  .state('tab.cart', {
    url: '/cart',
    nativeTransitions:null,
    views: {
      'tab-cart': {
        templateUrl: 'templates/cart/cart.html',
        controller: 'CartCtrl'
      }
    }
  })

  // 结算
  .state('tab.flow', {
    url: '/flow',
    views: {
      'tab-cart': {
        templateUrl: 'templates/cart/flow.html',
        controller: 'FlowCtrl'
      }
    }
  })

  //支付
  .state('tab.done', {
    url: '/done/:id',
    views: {
      'tab-cart': {
        templateUrl: 'templates/cart/done.html',
        controller: 'DownCtrl'
      }
    }
  })

  //微信接口借权
  .state('getWxCode', {
      url: '/getWxCode/:str',
      controller: 'getWxCodeCtrl'
  })

  //微支付接口借权
  .state('wxPayIng', {
      url: '/wxPayIng/:str',
      controller: 'wxPayIngCtrl'
  })

  // 下单收货地址管理
  .state('tab.flowaddress', {
    url: '/flowaddress/:act',
    views: {
      'tab-cart': {
        templateUrl: 'templates/cart/flowAddress.html',
        controller: 'FlowAddressCtrl'
      }
    }
  });

  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/tab/home');

});
