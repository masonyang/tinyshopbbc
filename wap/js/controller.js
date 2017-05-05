angular.module('starter.controllers', [])

    //首页 v2
    .controller('HomeCtrl', function($scope, ENV, ShopInfoV2, AdvListV2, HomeAdvV2, NewGoodsList, $ionicSlideBoxDelegate, $ionicLoading, $ionicModal, $state, $rootScope, msg, GlobalFun) {
        $ionicLoading.show({
            noBackdrop:false,
            duration: 10000
        });

        $scope.$on('$ionicView.afterEnter', function() {
            //显示菜单
            $scope.$on('$ionicView.enter', function () {
                $rootScope.hideTabs = false;
            });
            //显示菜单

            $scope.getPlatform = GlobalFun.getPlatform();
        });

        //幻灯片
        var getAdvList = function() {
            HomeAdvV2().then(function(json) {
                var result = json.data;
                var advList = result.data;

                if(advList.length > 0) {
                    for(var i in advList) {
                        advList[i].img_url = '#';
                        if(advList[i].s_type == 'goods') {
                            advList[i].img_url = '#/tab/detail/'+advList[i].url;
                        }else if(advList[i].s_type == 'category') {
                            advList[i].img_url = 'tab.goodsList({id:'+advList[i].url+'})';
                        }
                    }
                    $scope.ads = advList;
                    $ionicSlideBoxDelegate.update();
                    $ionicSlideBoxDelegate.$getByHandle('slideBanner').loop(true);
                }
                $ionicLoading.hide();
            });
        };
        //幻灯片
        getAdvList();

        //商品列表
        var name = 'home';
        NewGoodsList.getTopList(name, {});
        $scope.$on('NewGoodsList.List'+name, function() {
            $scope.GoodsList = NewGoodsList.getList();
        });
        //商品列表

        //获取商店信息
        var getShopInfo = function() {
            ShopInfoV2().then(function(json) {
                var result = json.data;
                var shopinfo = result.data;

                $scope.shopinfo = shopinfo;
                $scope.shopname = shopinfo.site_name;
                if(shopinfo.adpositions.length > 0) {
                    AdvListV2(shopinfo.adpositions).then(function(json) {
                        var result = json.data;
                        var advList = result.data;
                        for(var z in advList) {
                            for (var i in advList[z]) {
                                advList[z][i].img_url = '#';
                                if (advList[z][i].s_type == 'goods') {
                                    advList[z][i].img_url = '#/tab/detail/' + advList[z][i].url;
                                } else if (advList[z][i].s_type == 'category') {
                                    advList[z][i].img_url = 'tab.goodsList({id:' + advList[z][i].url + '})';
                                }
                                $scope.advlist = {apid: advList[z]};
                            }
                        }
                    });
                }
            });
        };
        //获取商店信息
        getShopInfo();

        //下拉刷新
        $scope.doRefresh = function() {
            getAdvList();
            getShopInfo();
            NewGoodsList.getTopList(name, {});
            $scope.$broadcast("scroll.refreshComplete");
        };
        //下拉刷新

        //上拉更新
        $scope.loadMore = function() {
            NewGoodsList.getMoreList(name, {}, $scope);
            //$scope.$broadcast("scroll.infiniteScrollComplete");
        };
        //上拉更新

        $scope.hasNextPage = function() {
            return NewGoodsList.hasNext();
        };

        //搜索表单
        $ionicModal.fromTemplateUrl('templates/category/search-form-model.html', {
            scope: $scope,
        }).then(function(modal) {
            $scope.searchFormModel = modal;
        });

        $scope.SearchFormModel = function(status) {
            if(status == '0') {
                $scope.searchFormModel.show();
            }else{
                $scope.searchFormModel.hide();
            }
        };
        $scope.colseSearchFormModel = function() {
            $scope.searchFormModel.hide();
        };

        $scope.search = {keyword:''};
        $scope.searchButton = function() {
            if($scope.search.keyword === '' || !$scope.search.keyword) {
                msg('搜索条件不能为空');
            }else{
                $scope.searchFormModel.hide();
                $state.go('tab.search', {keyword:$scope.search.keyword});
            }
        };
        $scope.$on('$destroy', function() {
            $scope.searchFormModel.remove();
        });
        //搜索表单

    })

    //处理路由 v2
    .controller('tabsCtrl', function($state, $scope, $location) {
        $scope.goToState = function(state) {
            $state.go(state, {}, {reload:true});
        };
        // $scope.goToState = function (state) {
        //     if ($state.current.name != state) {
        //         $state.transitionTo(state, {}, {reload: state == 'tab.home' ? false : true});
        //     }
        // };
    })

    //下载app
    .controller('DownAppCtrl', function($scope, $ionicHistory, ShopInfoV2, DownloadApp) {
        $scope.goBack = function() {
            $ionicHistory.goBack();
        };

        ShopInfoV2().then(function(json) {
            var result = json.data;
            var shopinfo = result.data;
            $scope.shopinfo = shopinfo;
        });

        DownloadApp().then(function(json) {
            var result = json.data;
            $scope.downUrl = result.data.download_url;
        });

    })

    //商品分类
    .controller('CategoryCtrl', function($scope, CategoryFactory, ENV, $ionicLoading, $ionicModal, msg, $state, $rootScope) {

        $scope.$on('$ionicView.afterEnter', function() {
            //显示菜单
            $scope.$on('$ionicView.enter', function () {
                $rootScope.hideTabs = false;
            });
            //显示菜单
        });

        $scope.imgPath = ENV.imgUrl;

        $ionicLoading.show({
            noBackdrop:false,
            duration: 10000
        });

        // 分类切换效果
        $scope.changeTab=function(i){
            $('#menu-list .menu-item').attr('class', 'menu-item item');
            $('#menu-list .menu-item').eq(i).attr('class', 'menu-item item item-hover');
            $('.menu-right-list').attr('class', 'menu-right-list hide');
            $('.menu-right-list').eq(i).attr('class', 'menu-right-list show');
        };
        // 分类切换效果

        // 获取所有分类
        CategoryFactory.getChild();
        $scope.$on('Category.list', function() {
            $scope.getList = CategoryFactory.getList();
            if($scope.getList) {
                $ionicLoading.hide();
            }
        });
        // 获取所有分类

        //搜索表单
        $ionicModal.fromTemplateUrl('templates/category/search-form-model.html', {
            scope: $scope,
        }).then(function(modal) {
            $scope.searchFormModel = modal;
        });

        $scope.SearchFormModel = function(status) {
            if(status == '0') {
                $scope.searchFormModel.show();
            }else{
                $scope.searchFormModel.hide();
            }
        };

        $scope.search = {keyword:''};
        $scope.searchButton = function() {
            if($scope.search.keyword === '' || !$scope.search.keyword) {
                msg('搜索条件不能为空');
            }else{
                $scope.searchFormModel.hide();
                $state.go('tab.search', {keyword:$scope.search.keyword});
            }
        };

        $scope.$on('$destroy', function() {
            $scope.searchFormModel.remove();
        });
        //搜索表单
    })

    //商品列表页
    .controller('GoodsListCtrl', function($scope, $rootScope, $stateParams, msg, GoodsListFactory, $ionicLoading, $ionicScrollDelegate, $ionicHistory) {
        //显示菜单
        $scope.$on('$ionicView.enter', function () {
            $rootScope.hideTabs = false;
        });
        //显示菜单

        $scope.goBack = function() {
            $ionicHistory.goBack();
        };
        var topid = parseInt($stateParams.id);
        var order = $stateParams.order ? $stateParams.order : 1; //排序方式:1-最新 2-价格 3-销量 4-人气
        var by    = $stateParams.by;

        $ionicLoading.show({
            noBackdrop:false,
            duration: 10000
        });

        //商品列表
        var name = 'cate';
        GoodsListFactory.getTopicList(name, {filter:topid, order:order});
        $scope.$on('GoodsList.List'+name, function() {
            $scope.GoodsList = GoodsListFactory.getList();
            if($scope.GoodsList) {
                $ionicLoading.hide();
            }
        });
        //商品列表

        //下拉刷新
        $scope.doRefresh = function() {
            GoodsListFactory.getTopicList(name, {filter:topid, order:order});
            $scope.$broadcast("scroll.refreshComplete");
        };
        //下拉刷新

        //上拉更新
        $scope.loadMore = function() {
            GoodsListFactory.getMoreList(name, {filter:topid, order:order}, $scope);
            //$scope.$broadcast("scroll.infiniteScrollComplete");
        };
        //上拉更新

        $scope.hasNextPage = function() {
            return GoodsListFactory.hasNextPage();
        };

        $scope.menuIconClass1 = 'ion-arrow-down-a';
        $scope.menuIconClass2 = 'ion-arrow-down-a hide';
        $scope.menuIconClass3 = 'ion-arrow-down-a hide';
        $scope.menuIcon = 1;
        $scope.orderBy = function(order) {
            $ionicLoading.show({
                noBackdrop:false,
                duration: 10000
            });

            $scope.menuIcon = order;
            var sort = 'desc';
            if(order == 1) {
                $scope.menuIconClass1 = 'ion-arrow-down-a';
                $scope.menuIconClass2 = 'ion-arrow-down-a hide';
                $scope.menuIconClass3 = 'ion-arrow-down-a hide';
            }else if(order == 2) {
                $scope.menuIconClass1 = 'ion-arrow-down-a hide';
                $scope.menuIconClass3 = 'ion-arrow-down-a hide';

                if($scope.menuIconClass2 == 'ion-arrow-down-a') {
                    $scope.menuIconClass2 = 'ion-arrow-up-a';
                    sort = 'asc';
                }else{
                    $scope.menuIconClass2 = 'ion-arrow-down-a';
                    sort = 'desc';
                }

            }else if(order == 3) {
                $scope.menuIconClass1 = 'ion-arrow-down-a hide';
                $scope.menuIconClass2 = 'ion-arrow-down-a hide';
                $scope.menuIconClass3 = 'ion-arrow-down-a';
            }

            GoodsListFactory.getTopicList(name, {filter:topid, order:order, sort:sort});
            $ionicScrollDelegate.scrollTop(true);
        };
    })

    //搜索列表页
    .controller('SearchCtrl', function($scope, $rootScope, $stateParams, msg, NewGoodsList, $ionicLoading, $ionicScrollDelegate) {
        //显示菜单
        $scope.$on('$ionicView.enter', function () {
            $rootScope.hideTabs = false;
        });
        //显示菜单

        var keyword = $scope.keyword = $stateParams.keyword;

        $ionicLoading.show({
            noBackdrop:false,
            duration: 10000
        });

        //商品列表
        var name = 'search';
        NewGoodsList.getTopList(name, {type:2, filter:keyword, order:1, iscount:1});
        $scope.$on('NewGoodsList.List'+name, function() {
            var result = NewGoodsList.getResult();
            $scope.GoodsList = result.data;
            console.log($scope.GoodsList);
            $scope.page = result.page;
            $scope.total = result.total;
            $ionicLoading.hide();
        });
        //商品列表

        //下拉刷新
        $scope.doRefresh = function() {
            NewGoodsList.getTopList(name, {type:2, filter:keyword, order:1, iscount:1});
            $scope.$broadcast("scroll.refreshComplete");
        };
        //下拉刷新

        //上拉更新
        $scope.loadMore = function() {
            NewGoodsList.getMoreList(name, {type:2, filter:keyword, order:1, iscount:1}, $scope);
            //$scope.$broadcast("scroll.infiniteScrollComplete");
        };
        //上拉更新

        $scope.hasNext = function() {
            return NewGoodsList.hasNext();
        };

    })

    //商品详情页 v2
    .controller('DetailCtrl', function($scope, $stateParams, $state, GoodsInfo, $ionicLoading, $ionicSlideBoxDelegate, ENV, msg, User, MyCartFactory, Attention, $ionicHistory, GlobalFun) {
        $scope.goBack = function() {
            $ionicHistory.goBack();
        };

        var id = $stateParams.id;
        if(id <= 0) {
            $state.go('tab.category');
        }

        $scope.num = 0;

        var user = User._User();
        var uid = user.user_id;
        if(uid) {
            MyCartFactory.getCartCount(uid).then(function(json) {
                var result = json.data;
                $scope.num = result.data.count;
            });
        }

        $ionicLoading.show({
            noBackdrop:false,
            duration: 10000
        });

        //去购物车
        $scope.goCart = function() {
            $state.go('tab.cart');
        };
        var attr_list = {};
        GoodsInfo.GetInfo(id, uid).then(function(json) {
            var result = json.data;
            $scope.goods = result.data;
            $scope.goods.gid = id;
            $scope.goods.use_price = $scope.goods.price;
            $scope.goods.use_store_num = $scope.goods.store_nums;

            if($scope.goods) {
                $ionicLoading.hide();
            }

            if($scope.goods.content_data) {
                $scope.goods.content_data = $scope.goods.content_data.replace(/ src="/g, ' src="'+ENV.imgUrl);
            }
            $ionicSlideBoxDelegate.update();
            $ionicSlideBoxDelegate.loop(true);
        });

        GoodsInfo.recommendGoods(4).then(function(json) {
            var result = json.data;
            $scope.recommendList = result.data.goods;
        });

        $scope.attr_list = {};
        //选择属性
        $scope.changeAttr = function(p, i) {
            if($scope.attr_list[p] == i) {
                delete $scope.attr_list[p];
            }else{
                $scope.attr_list[p] = i;
            }

            var count = 0;
            var arr = [];
            for(var property in $scope.attr_list) {
                if (Object.prototype.hasOwnProperty.call($scope.attr_list, property)) {
                    arr.push($scope.attr_list[property]);
                    count++;
                }
            }
            if(count == $scope.goods.sys_attrprice.specslist_arr.length) {
                var k = arr.join('_');
                $scope.goods.use_price = $scope.goods.sys_attrprice.combine_arr[k].price;
                $scope.goods.use_store_num = $scope.goods.sys_attrprice.combine_arr[k].store_num;
            }else if(count === 0) {
                $scope.goods.use_price = $scope.goods.price;
                $scope.goods.use_store_num = $scope.goods.store_num;
            }
        };

        // 加减数量
        $scope.count = 1;
        $scope.calculation = function(str) {
            if(str == 'sub') {
                $scope.count--;
            }else if(str == 'add') {
                $scope.count++;
            }else{
                $scope.count=str;
            }

            if($scope.count < 1) {
                $scope.count = 1;
            }
        };

        //加入购物车
        $scope.addCart = function() {
            $ionicLoading.show({
                template:'处理中...',
                noBackdrop:false,
                duration: 10000
            });

            if(!user) {
                msg('请登录您的账号');
                $state.go('login');
                return false;
            }

            if($scope.count <= 0) {
                msg('请填写商品数量');
                return false;
            }

            //有商品规格需选择规格
            if($scope.goods.sys_attrprice.specslist_arr !== undefined && $scope.goods.sys_attrprice.specslist_arr.length > 0) {
                var count = 0;
                var arr = [];
                for(var property in $scope.attr_list) {
                    if (Object.prototype.hasOwnProperty.call($scope.attr_list, property)) {
                        arr.push($scope.attr_list[property]);
                        count++;
                    }
                }
                if(count != $scope.goods.sys_attrprice.specslist_arr.length) {
                    msg('请选择商品规格');
                    return false;
                }
                if($scope.goods.use_store_num < $scope.count) {
                    msg('库存不足，库存剩余'+$scope.goods.use_store_num+$scope.goods.unit);
                    return false;
                }
                var k = arr.join('_');
                pro_no = $scope.goods.sys_attrprice.combine_arr[k].pro_no;
            }else{
                if($scope.goods.use_store_num < $scope.count) {
                    msg('库存不足，库存剩余'+$scope.goods.use_store_num+$scope.goods.unit);
                    return false;
                }
                pro_no = $scope.goods.pro_no;
            }

            MyCartFactory.addCart(uid, $scope.count, pro_no).then(function(json) {
                $ionicLoading.hide();

                var result = json.data;
                if(result.status == 'fail') {
                    msg(result.msg);
                    return false;
                }else{
                    $scope.num = result.data.count;
                    msg('成功加入购物车');
                }
            });
        };

        //收藏、取消收藏 status: 1.收藏 2.取消收藏
        $scope.shoucang = function(status) {
            if(uid <= 0) {
                msg('请登录用户');
                return false;
            }
            if(status == 1) {
                Attention.add(uid, id).then(function(json) {
                    var result = json.data;
                    if(result.status == 'succ') {
                        $scope.goods.attention = 'fav';
                        msg(result.msg);
                    }
                });
            }else{
                Attention.Cancel(uid, id).then(function(json) {
                    var result = json.data;
                    if(result.status == 'succ') {
                        $scope.goods.attention = 'nofav';
                        msg(result.msg);
                    }
                });
            }
        };

        $scope.openShare = function(gid) {
            var platform = GlobalFun.getPlatform();
            GlobalFun.openShare(platform.system, gid);
        };
    })

    //购物车 v2
    .controller('CartCtrl', function($scope, $state, msg, User, $ionicLoading, MyCartFactory, $ionicHistory) {
        $scope.goBack = function() {
            $ionicHistory.goBack();
        };

        $ionicLoading.show({
            noBackdrop:false,
            duration: 5000
        });

        var user = User._User();
        var uid  = user.user_id;
        $scope.uid = uid;

        $scope.num = 0;
        $scope.$on('$ionicView.afterEnter', function() {
            $scope.list = {};
            $scope.num = 0;
            $scope.total = 0;

            if(user) {
                var getCartList = function() {
                    MyCartFactory.getCartList(uid).then(function (json) {
                        $ionicLoading.hide();

                        var result = json.data;
                        if(result.status == "succ") {
                            var list = {};
                            if(result.data.gitem) {
                                for(i=0; i<result.data.gitem.length; i++) {
                                    list[result.data.gitem[i].product_no] = result.data.gitem[i];
                                }
                            }else{
                                list = result.data.gitem;
                            }

                            $scope.list = list;
                            $scope.num = result.data.goods_count;
                            $scope.total = result.data.total;
                        }
                    });
                };
                getCartList();
            }else{
                $ionicLoading.hide();
            }

            $scope.doRefresh = function() {
                if(user) {
                    $scope.flag.showDelete = false;
                    getCartList();
                }else{
                    msg('请登录账号');
                }
                $scope.$broadcast("scroll.refreshComplete");
            };
        });

        $scope.calculation = function(str, index) {
            var goods  = $scope.list[index];
            var pro_no = goods.product_no;

            if(str == 'sub') {
                MyCartFactory.removeCart(uid, 1, pro_no).then(function(json) {
                    var result = json.data;

                    if(result.status == 'succ') {
                        var goods =  $scope.list[pro_no];
                        if(goods) {
                            goods.num = result.data.buy_num;
                            goods.amount = result.data.current_goods_amount;
                        }
                        if(result.data.buy_num == '0') {
                            delete $scope.list[pro_no];
                        }
                        $scope.total = result.data.goods_amount;
                        $scope.num = result.data.count;
                    }else{
                        msg(result.msg);
                        return false;
                    }
                });
            }else if(str == 'add') {
                MyCartFactory.addCart(uid, 1, pro_no).then(function(json) {
                    var result = json.data;

                    if(result.status == 'succ') {
                        var goods =  $scope.list[pro_no];
                        if(goods) {
                            goods.num = result.data.buy_num;
                            goods.amount = result.data.current_goods_amount;
                        }
                        $scope.total = result.data.goods_amount;
                        $scope.num = result.data.count;
                    }else{
                        msg(result.msg);
                        return false;
                    }
                });
            }
        };


        $scope.flag={showDelete:false};
        $scope.deleteGoods=function(key){
            $ionicLoading.show({
                template:'删除中...',
                noBackdrop:false,
                duration: 10000
            });
            MyCartFactory.removeCart(uid, ($scope.list[key].num)*1+1, key).then(function(json) {
                var result = json.data;

                console.log(result);
                if(result.status == 'succ') {
                    delete $scope.list[key];
                    $scope.num = result.data.count;
                    $scope.total = result.data.goods_amount;
                }
                $ionicLoading.hide();
            });
        };
    })

    //订单收货地址 v2
    .controller('FlowAddressCtrl', function($scope, msg, AddressFactory, $ionicLoading, $state, $stateParams, AreaFactory, User) {

        $scope.formShow = false;
        $scope.$on('$ionicView.afterEnter', function() {

            var user = User._User();
            if(user === false) {
                $state.go('login');
                return false;
            }

            $ionicLoading.show({
                noBackdrop:false,
                duration: 5000
            });

            var uid = user.user_id;
            var act = $stateParams.act;
            $scope.address = {
                addr_id:0,
                name:'',
                mobile:'',
                zip:'',
                phone:'',
                province:'',
                city:'',
                county:'',
                address:'',
                is_default:true,
            };

            var vm=$scope.vm={};
            var defaultAreaData = [];
            AreaFactory.getArea().then(function(json) {
                var result = json.data;
                var CityPickerService = result.data;

                vm.CityPickData = {
                    title: '收货地区：',
                    defaultAreaId:0,
                    defaultAreaData:defaultAreaData,
                    backdropClickToClose: true,
                    CityPickerService: CityPickerService,
                    buttonClicked: function () {
                        vm.getAreaId();
                    }
                };
            });
            vm.getAreaId = function () {
                var province = vm.CityPickData.areaData[0];
                var city = vm.CityPickData.areaData[1];
                var county = vm.CityPickData.areaData[2];

                for(var i in vm.CityPickData.CityPickerService) {
                    if(vm.CityPickData.CityPickerService[i].name == province) {
                        $scope.address.province = vm.CityPickData.CityPickerService[i].id; // 设置省id
                        for(var j in vm.CityPickData.CityPickerService[i].sub) {
                            if(vm.CityPickData.CityPickerService[i].sub[j].name == city) {
                                $scope.address.city = vm.CityPickData.CityPickerService[i].sub[j].id; // 设置市id
                                for(var k in vm.CityPickData.CityPickerService[i].sub[j].sub) {
                                    if(vm.CityPickData.CityPickerService[i].sub[j].sub[k].name == county) {
                                        $scope.address.county = vm.CityPickData.CityPickerService[i].sub[j].sub[k].id; // 设置区id
                                    }
                                }
                            }
                        }
                    }
                }
            };
            //获取收货地址
            AddressFactory.getAddressList();
            $scope.$on('AddressFactory.getAddressList', function() {
                $ionicLoading.hide();
                $scope.addressList = AddressFactory.getResult();

                if($scope.addressList.length) {
                    $scope.formShow = false;
                }else{
                    $scope.formShow = true;
                }
            });
            if(act == 'add') {
                $scope.formShow = true;
            }

            $scope.title = '请选择收货地址';
            if($scope.formShow === true) {
                $scope.title = '请填写收货地址';
            }

            $scope.update = function() {
                if(!$scope.address.province || !$scope.address.city || !$scope.address.county) {
                    msg('请选择收货地址');
                }

                $ionicLoading.show({
                    template:'处理中...',
                    noBackdrop:false,
                });
                AddressFactory.changeAddress({
                    _act:'add',
                    user_id:uid,
                    addr_id:$scope.address.addr_id,
                    name:$scope.address.name,
                    mobile:$scope.address.mobile,
                    province:$scope.address.province,
                    city:$scope.address.city,
                    county:$scope.address.county,
                    address:$scope.address.address,
                    is_default:true,
                }).then(function(json) {
                    var result = json.data;
                    msg(result.msg);
                    if(result.status == 'succ') {
                        $ionicLoading.hide();
                        $state.go('tab.flow');
                    }
                });
            };

            // 设置默认地址
            $scope.setDefault2 = function(id) {
                $ionicLoading.show({
                    noBackdrop:false,
                    duration: 5000
                });
                AddressFactory.changeAddress({
                    _act:"setdefault",
                    addr_id:id,
                    user_id:uid,
                    is_default:true,
                }).then(function(json) {
                    var result = json.data;

                    $ionicLoading.hide();
                    if(result.status == 'succ') {
                        AddressFactory.getAddressList();
                        $state.go('tab.flow');
                    }else{
                        msg(result.msg);
                    }
                });
            };
        });

        $scope.showForm = function() {
            $scope.formShow = true;
        };
        $scope.hideForm = function() {
            $scope.formShow = false;
        };

    })

    //确认订单 v2
    .controller('FlowCtrl', function($scope, $state, msg, User, $ionicLoading, MyCartFactory, AddressFactory, Order, ENV, $timeout, GlobalFun, $cordovaDevice) {

        $scope.order = {uid:0, addr_id:0, payment:'', system:'', user_remark:''};

        var paylist = ENV.payList;
        $scope.$on('$ionicView.afterEnter', function() {

            var user = User._User();
            var uid  = user.user_id;
            if(user === false) {
                msg('请先登录账号');
                $state.go('login');
                return false;
            }

            $ionicLoading.show({
                noBackdrop:false,
                duration: 10000
            });
            $scope.order.uid = $scope.uid = uid;

            //获取收货地址
            AddressFactory.getAddressList(uid);
            $scope.$on('AddressFactory.getAddressList', function() {
                $scope.addressList = AddressFactory.getResult();

                if($scope.addressList.length > 0) {
                    for(i=0; i<$scope.addressList.length; i++) {
                        if($scope.addressList[i].is_default == 1) {
                            $scope.order.addr_id = $scope.addressList[i].addr_id;
                            $scope.defAddress = $scope.addressList[i];
                        }
                    }
                    if($scope.order.addr_id <= 0) {
                        $scope.order.addr_id = $scope.addressList[0].addr_id;
                        $scope.defAddress = $scope.addressList[0];
                    }

                    if($scope.order.addr_id <= 0) {
                        $state.go('tab.flowaddress', {act:'list'});
                    }

                    if($scope.defAddress.addr_id > 0) {
                        //检查运费
                        MyCartFactory.checkFreight(uid, $scope.defAddress.addr_id).then(function(json) {
                            var result = json.data;
                            if(result.status == 'succ') {
                                $scope.payable_freight = result.data.payable_freight;
                            }
                        });
                    }
                }else{
                    $state.go('tab.flowaddress', {act:'add'});
                }
            });

            MyCartFactory.getCartList(uid).then(function(json) {
                $ionicLoading.hide();

                var result = json.data;
                if(result.status == "succ") {
                    if(result.data.goods_count <= 0) {
                        msg('购物车是空的，赶快去逛逛吧！');
                        $state.go('tab.home');
                    }

                    var list = {};
                    if(result.data.gitem) {
                        for(i=0; i<result.data.gitem.length; i++) {
                            list[result.data.gitem[i].product_no] = result.data.gitem[i];
                        }
                    }else{
                        list = result.data.gitem;
                    }

                    $scope.list = list;
                    $scope.num = result.data.goods_count;
                    $scope.total = result.data.total;
                }else{
                    $ionicLoading.show({
                        template:result.msg,
                        noBackdrop:false,
                        duration: 10000
                    });
                    $timeout(function() {
                        $ionicLoading.hide();
                        $state.go('tab.home');
                    }, 1800);

                }
            });

            $scope.payment = {alipay:false, weixin:false};
            $scope.platform = GlobalFun.getPlatform();
            var platform = $scope.platform;
            if(platform.system == 'app') {
                $scope.payment.alipay = true;
                $scope.order.payment = 'appAlipay';
            }
            if(platform.system == 'h5') {
                $scope.payment.alipay = true;
                $scope.order.payment = 'wapAlipay';
            }
            if(platform.system == 'weixin') {
                $scope.payment.weixin = true;
                $scope.order.payment = 'weixin';
            }
            $scope.onPayment = function(payName) {
                $scope.order.payment = payName;
                $scope.order.system = $scope.platform.system;
            };
        });

        $scope.subOrder = function() {
            var order = $scope.order;
            if(!order.uid) {
                msg('请登录用户');
                return false;
            }
            if(!order.addr_id) {
                msg('请选择收货地址');
                return false;
            }
            if(!order.payment) {
                msg('请选择支付方式');
                return false;
            }

            var paymentid = ENV.payList[order.payment];
            if(paymentid < 0 ) {
                msg('参数错误，请联系管理员');
                return false;
            }
            $ionicLoading.show({
                template:'订单提交中...',
                noBackdrop:false,
                duration: 10000
            });
            Order.createOrder(order.uid, order.addr_id, paymentid, order.user_remark).then(function(json) {
                $ionicLoading.hide();

                var result = json.data;
                if(result.status == 'succ') {
                    $ionicLoading.show({
                        template:'订单创建成功...',
                        noBackdrop:false,
                        duration: 10000
                    });
                    var oid = result.data.orderid;
                    if(oid > 0) {
                        $state.go('tab.done', {id:oid});
                    }else{
                        $state.go('tab.myorder');
                    }

                }else{
                    msg(result.msg);
                }

            });
        };

        $scope.upAddress = function() {
            $state.go('tab.flowaddress', {act:'list'});
        };
    })

    //马上支付 v2
    .controller('DownCtrl', function($scope, $state, msg, User, $stateParams, $ionicLoading, Order, $timeout, Payment, ENV, WeiXin, GlobalFun, ENV) {

        $scope.$on('$ionicView.afterEnter', function() {
            var user = User._User();
            var uid  = user.user_id;
            $scope.uid = uid;

            if(user === false) {
                msg('请先登录账号');
                $state.go('login');
                return false;
            }

            $ionicLoading.show({
                template:'获取订单信息...',
                noBackdrop:false,
                duration: 10000
            });

            var oid = $stateParams.id;
            if(oid <=0) {
                $state.go('tab.myorder');
            }

            //获取支付订单
            $scope.payment = function(order, result) {
                if(order.goods_amount > 0 && order.payment_id > 0 && order.status == '等待付款') {
                    /*                    $ionicLoading.show({
                     template:'启动支付...',
                     noBackdrop:false,
                     duration: 5000
                     });*/

                    var code = '';
                    var getCode = GlobalFun.GetQueryString('code');

                    if(result.data.payment_id === ENV.payList.weixin) {
                        if(getCode === null) {
                            var callback = window.btoa(ENV.H5Url+'#/tab/done/'+oid);
                            var wxcallback = ENV.wxUrl+'wap/#/getWxCode/'+callback;
                            // console.log(wxcallback);
                            var callUrl = WeiXin.getOauthCodeUrl(wxcallback, oid);
                            console.log(callUrl);
                            window.location = callUrl;
                            return false;
                        }else{
                            var host = location.host;
                            var str = window.btoa(JSON.stringify({host:host, oid:order.oid, payment_id:order.payment_id, code:getCode, uid:uid}));
                            console.log(ENV.wxUrl+'wap/#/wxPayIng/'+str);
                            window.location = ENV.wxUrl+'wap/#/wxPayIng/'+str;
                            return false;
                        }
                    }

                    code = getCode;
                    var host = location.host;
                    Order.payCheck(uid, order.oid, order.payment_id, {host:host, code:code}).then(function(json) {
                        var res = json.data;

                        if(res.status == 'succ') {
                            if(result.data.payment_id === ENV.payList.appAlipay) {
                                //支付宝app支付
                                Payment.alipayPay(order.oid, res.data.pay_data);
                            }else if(result.data.payment_id === ENV.payList.weixin) {
                                // res.data.pay_data.oid = order.oid;
                                // var str = window.btoa(JSON.stringify({url:ENV.H5Url+'#/tab/orderdetail/'+order.oid, data:res.data}));
                                // console.log(str);
                                // window.location = ENV.wxUrl+'wap/#/wxPayIng/'+str;
                            }else if(result.data.payment_id === ENV.payList.wapAlipay) {
                                window.location = res.data.pay_data;
                            }
                        }else{
                            msg(res.msg);
                        }
                    });
                }else{
                    $state.go('tab.orderdetail', {id:oid});
                }
            };
            Order.getOrderDetail(oid).then(function(json) {
                var result = json.data;
                $scope.order = result.data;
                $scope.payment($scope.order, result);
            });
        });
    })

    .controller('getWxCodeCtrl', function($scope, $state, $stateParams, GlobalFun) {
        var str = $stateParams.str;
        url = window.atob(str);
        var code = GlobalFun.GetQueryString('code');
        if(code) {
            url = url.replace('#', '?code='+code+'&#');
        }
        window.location = url;
    })

    .controller('wxPayIngCtrl', function($scope, $state, $stateParams, GlobalFun, WeiXin, Order, msg, ENV) {
        var str = $stateParams.str;
        if(!str) {
            return false;
        }
        var obj = JSON.parse(window.atob(str));
        // console.log(obj);
        var return_url = encodeURI(location.protocol+'//'+obj.host+'#/tab/detail/'+obj.oid);
        console.log(return_url);
        Order.payCheck(obj.uid, obj.oid, obj.payment_id, {host:obj.host, code:obj.code, return_url:return_url}).then(function(json) {
            var res = json.data;
            console.log(res);

            if(res.status == 'succ') {
                // WeiXin.wapPay(res.data.pay_data, obj.url);

                var p = JSON.stringify({pay_data:res.data.pay_data,url:return_url});
                window.location = ENV.wxUrl + 'wap/test.html?p='+window.btoa(p);
            }else{
                msg(res.msg);
            }
        });

        //WeiXin.wapPay(obj.data.pay_data, obj.url);
    })

    //会员中心 v2
    .controller('UserCtrl', function($scope, $state, msg, $ionicActionSheet, User, ShopInfoV2, $cordovaAppVersion, $rootScope) {

        $scope.$on('$ionicView.beforeEnter', function() {
            //显示菜单
            $scope.$on('$ionicView.enter', function () {
                $rootScope.hideTabs = false;
            });
            //显示菜单

            document.addEventListener("deviceready", function () {
                $cordovaAppVersion.getVersionNumber().then(function (version) {
                    $scope.version = version;
                });
            });

            //商店信息
            ShopInfoV2().then(function(json) {
                var result = json.data;
                $scope.shopinfo = result.data;
            });
            //商店信息

            $scope.userInfo = User._User();
        });

        //退出登录
        $scope.logout = function() {
            var hideSheet = $ionicActionSheet.show({
                destructiveText: '退出登录',
                titleText: '确定退出当前登陆账号吗？',
                cancelText: '取消',
                cancel: function() {},
                destructiveButtonClicked: function() {
                    User._logout();
                    return true;
                }
            });
        };
    })

    //登录 v2
    .controller('LoginCtrl', function($scope, User, $state, $ionicLoading, $ionicHistory, $cordovaDevice, checkField) {
        $scope.goBack = function() {
            $ionicHistory.goBack();
        };

        $scope.$on('$ionicView.beforeEnter', function() {
            if(User._User() !== false) {
                $state.go('tab.user');
            }
        });

        $scope.user={
            username:'',
            password:'',
        };
        document.addEventListener("deviceready", function () {
            $scope.uuid = $cordovaDevice.getUUID();
        }, false);

        $scope.Login = function(){

            if(checkField.isEmpty($scope.uuid) && $scope.user.username) {
                $scope.uuid = $scope.user.username;
            }
            $ionicLoading.show({
                template: "登录中...",
                noBackdrop:false
            });
            User._login($scope.user.username, $scope.user.password, $scope.uuid);
        };

    })

    //注册 v2
    .controller('RegisterCtrl', function($scope, User, msg, $ionicLoading, Register, $state, $cordovaDevice, checkField, $interval, Sms) {

        $scope.$on('$ionicView.beforeEnter', function() {
            if(User._User() !== false) {
                $state.go('tab.user');
            }
        });

        $scope.user={
            username:'',
            password:'',
            repassword:'',
            smscode:''
        };

        document.addEventListener("deviceready", function () {
            $scope.uuid = $cordovaDevice.getUUID();
        }, false);

        $scope.Register = function() {
            if(checkField.isEmpty($scope.uuid) && $scope.user.username) {
                $scope.uuid = $scope.user.username;
            }

            if($scope.user.password != $scope.user.repassword) {
                msg('两次密码不一致');
                return false;
            }

            if(checkField.isEmpty($scope.uuid) && $scope.user.username) {
                $scope.uuid = $scope.user.username;
            }

            $ionicLoading.show({
                template: "注册中...",
                noBackdrop:false
            });
            Register.submit($scope.user.username, $scope.user.password, $scope.user.repassword, $scope.user.smscode, $scope.uuid);
        };

        $scope.codetext = '获取验证码';
        var second = 60;
        $scope.isdisabled = false;

        $scope.getCode = function() {
            if(checkField.isMobile($scope.user.username)) {
                msg('请填写正确的手机号');
                return false;
            }

            if(checkField.isEmpty($scope.uuid) && $scope.user.username) {
                $scope.uuid = $scope.user.username;
            }

            $scope.codetext = "短信发送中...";
            $scope.isdisabled = true;
            Sms($scope.user.username, $scope.uuid, 'reg').then(function(json) {
                var result = json.data;
                var timePromise = null;
                if(result.status == 'fail') {
                    second = 60;
                    $scope.isdisabled = false;
                    $scope.codetext = "重发验证码";
                    msg(result.msg);
                    return false;
                }else{
                    $scope.codetext = second + " 秒后可重发";
                    $scope.isdisabled = true;

                    timePromise = $interval(function(){
                        if(second<=0){
                            $interval.cancel(timePromise);
                            timePromise = null;
                            second = 60;
                            $scope.isdisabled = false;
                            $scope.codetext = "重发验证码";
                        }else{
                            second--;
                            $scope.codetext = second + " 秒后可重发";
                        }
                    },1000);
                }
            });
        };

    })

    //忘记密码 v2
    .controller('FindpwdCtrl', function($scope, msg, checkField, Sms, $interval, $cordovaDevice, Register, $state, User, $ionicHistory) {
        $scope.goBack = function() {
            $ionicHistory.goBack();
        };

        $scope.user = {
            'username':'',
            'password':'',
            'repassword':'',
            'smscode':'',
        };
        var user = User._User();
        if(user !== false) {
            console.log('ok');
        }


        document.addEventListener("deviceready", function () {
            $scope.uuid = $cordovaDevice.getUUID();
        }, false);

        $scope.codetext = '获取验证码';
        var second = 60;
        $scope.isdisabled = false;

        $scope.upPwd = function() {
            if(checkField.isEmpty($scope.uuid) && $scope.user.username) {
                $scope.uuid = $scope.user.username;
            }
            if($scope.user.password != $scope.user.repassword) {
                msg('两次密码不一致');
                return false;
            }
            Register.upPwd($scope.user.username, $scope.user.password, $scope.user.smscode, $scope.uuid);
        };

        $scope.getCode = function() {
            if(checkField.isMobile($scope.user.username)) {
                msg('请填写正确的手机号');
                return false;
            }

            if(checkField.isEmpty($scope.uuid) && $scope.user.username) {
                $scope.uuid = $scope.user.username;
            }

            $scope.codetext = "短信发送中...";
            $scope.isdisabled = true;
            Sms($scope.user.username, $scope.uuid, 'resetpwd').then(function(json) {
                var result = json.data;
                var timePromise = null;

                if(result.status == 'fail') {
                    second = 60;
                    $scope.isdisabled = false;
                    $scope.codetext = "重发验证码";
                    msg(result.msg);
                    return;
                }else{
                    $scope.codetext = second + " 秒后可重发";
                    $scope.isdisabled = true;

                    timePromise = $interval(function(){
                        if(second<=0){
                            $interval.cancel(timePromise);
                            timePromise = null;
                            second = 60;
                            $scope.isdisabled = false;
                            $scope.codetext = "重发验证码";
                        }else{
                            second--;
                            $scope.codetext = second + " 秒后可重发";
                        }
                    },1000);
                }
            });
        };

    })

    //我的信息 v2
    .controller('UserInfoCtrl', function($scope, ENV, User, msg, $state, $ionicActionSheet) {

        $scope.$on('$ionicView.afterEnter', function() {

            var user = User._User();
            if(user === false) {
                msg('请先登录账号');
                $state.go('login');
                return false;
            }
            User._UserInfo(user.user_id).then(function(json) {
                var result = json.data;
                $scope.user = result.data;
            });
        });

        $scope.changeSex = function() {
            //$ionicActionSheet.show({
            //    buttons: [{
            //        text: '男'
            //    }, {
            //        text: '女'
            //    }, {
            //        text: '保密'
            //    }],
            //    cancelText: '取消',
            //    buttonClicked: function (index) {
            //        switch (index) {
            //            case 0:
            //                $scope.editSex(1,'男');
            //                break;
            //            case 1:
            //                $scope.editSex(2,'女');
            //                break;
            //            case 2:
            //                $scope.editSex(0,'保密');
            //                break;
            //            default:
            //                break;
            //        }
            //        return true;
            //    },
            //    destructiveButtonClicked: function () {
            //        return true;
            //    }
            //})
        };
        //更新用户信息
        $scope.upUserInfo = function() {
        };
    })

    //收货地址列表 v2
    .controller('AddressListCtrl', function($scope, User, msg, $ionicLoading, AddressFactory, $ionicPopup, $state) {

        $ionicLoading.show({
            template: ""
        });
        var user = User._User();
        if(user === false) {
            $state.go('login');
            return false;
        }
        $scope.uid = user.user_id;
        //获取收货地址
        AddressFactory.getAddressList();
        $scope.$on('AddressFactory.getAddressList', function() {
            $ionicLoading.hide();
            var result = AddressFactory.getResult();
            $scope.addressList = result;
        });

        // 设置默认地址
        $scope.setDefault = function(id) {
            AddressFactory.changeAddress({
                _act:"setdefault",
                addr_id:id,
                user_id:$scope.uid,
                is_default:true,
            }).then(function(json) {
                var result = json.data;
                msg(result.msg);
                if(result.status == 'succ') {
                    AddressFactory.getAddressList();
                }
            });
        };

        // 删除地址
        $scope.delAddress = function(id) {

            for(i=0; i<$scope.addressList.length; i++) {
                if($scope.addressList[i].addr_id == id && $scope.addressList[i].is_default == 1) {
                    msg('默认地址不允许删除');
                    return false;
                }
            }
            $ionicPopup.confirm({
                title: "确认删除？",
                //template: "您确定要删除收货地址吗？",
                okText:"确定",
                okType:'button-assertive',
                cancelText:'取消',
            }).then(function(res) {
                if(res) {
                    AddressFactory.changeAddress({
                        _act:"del",
                        addr_id:id,
                        user_id:$scope.uid,
                    }).then(function(json) {
                        AddressFactory.getAddressList();
                    });
                }
            });
        };
    })

    //添加、修改收货地址 v2
    .controller('UpAddressCtrl', function($scope, User, msg, AddressFactory, AreaFactory, $state, $stateParams, $ionicLoading) {

        $scope.$on('$ionicView.afterEnter', function() {
            $ionicLoading.show({
                template: ""
            });
            var user = User._User();
            if(user === false) {
                $state.go('login');
                return false;
            }
            var id = $stateParams.id ? parseInt($stateParams.id) : 0;

            var defaultAreaData = [];
            if(id > 0) {
                AddressFactory.getOne(id).then(function(json) {
                    var result = json.data;
                    $scope.address = result.data;
                    if($scope.address.is_default == 1) {
                        $scope.address.is_default = true;
                    }else{
                        $scope.address.is_default = false;
                    }
                    defaultAreaData = [$scope.address.province_name, $scope.address.city_name, $scope.address.county_name];
                });
            }else{
                $scope.address = {
                    addr_id:0,
                    name:'',
                    mobile:'',
                    zip:'',
                    phone:'',
                    province:'',
                    city:'',
                    county:'',
                    address:'',
                    is_default:true,
                };
            }

            var vm=$scope.vm={};
            AreaFactory.getArea().then(function(json) {
                var result = json.data;
                var CityPickerService = result.data;
                vm.CityPickData = {
                    title: '收货地区：',
                    defaultAreaId:0,
                    defaultAreaData:defaultAreaData,
                    backdropClickToClose: true,
                    CityPickerService: CityPickerService,
                    buttonClicked: function () {
                        vm.getAreaId();
                    }
                };
                $ionicLoading.hide();
            });

            vm.getAreaId = function () {
                var province = vm.CityPickData.areaData[0];
                var city = vm.CityPickData.areaData[1];
                var county = vm.CityPickData.areaData[2];

                for(var i in vm.CityPickData.CityPickerService) {
                    if(vm.CityPickData.CityPickerService[i].name == province) {
                        $scope.address.province = vm.CityPickData.CityPickerService[i].id; // 设置省id
                        for(var j in vm.CityPickData.CityPickerService[i].sub) {
                            if(vm.CityPickData.CityPickerService[i].sub[j].name == city) {
                                $scope.address.city = vm.CityPickData.CityPickerService[i].sub[j].id; // 设置市id
                                for(var k in vm.CityPickData.CityPickerService[i].sub[j].sub) {
                                    if(vm.CityPickData.CityPickerService[i].sub[j].sub[k].name == county) {
                                        $scope.address.county = vm.CityPickData.CityPickerService[i].sub[j].sub[k].id; // 设置区id
                                    }
                                }
                            }
                        }
                    }
                }
            };

            $scope.update = function() {
                if(!$scope.address.province || !$scope.address.city || !$scope.address.county) {
                    msg('请选择收货地址');
                }
                var act;
                if(id > 0) {
                    act = 'edit';
                }else{
                    act = 'add';
                }

                $ionicLoading.show({
                    template: "正在保存...",
                    noBackdrop:false
                });
                AddressFactory.changeAddress({
                    _act:act,
                    user_id:user.user_id,
                    addr_id:$scope.address.addr_id,
                    name:$scope.address.name,
                    mobile:$scope.address.mobile,
                    province:$scope.address.province,
                    city:$scope.address.city,
                    county:$scope.address.county,
                    address:$scope.address.address,
                    is_default:$scope.address.is_default,
                }).then(function(json) {
                    var result = json.data;
                    msg(result.msg);
                    $ionicLoading.hide();
                    if (result.status === 'succ') {
                        AddressFactory.getAddressList();
                        $state.go('tab.addresslist', {}, {reload:true});
                    }
                });
            };
        });
    })

    //我的订单 v2
    .controller('MyOrderCtrl', function($scope, User, msg, $state, $ionicLoading, $ionicHistory, Payment, $rootScope, Order, GlobalFun, ENV) {
        $scope.goBack = function() {
            $ionicHistory.goBack();
        };

        $scope.$on('$ionicView.afterEnter', function() {

            //显示菜单
            $scope.$on('$ionicView.enter', function () {
                $rootScope.hideTabs = false;
            });
            //显示菜单

            var user = User._User();
            var uid  = user.user_id;
            if(user === false) {
                $state.go('login');
                return false;
            }
            $ionicLoading.show({
                noBackdrop:false,
                duration: 5000
            });
            $scope.uid = uid;

            var getOrderList = function(status) {
                Order.getMyOrder(uid, {status:status}).then(function(json) {
                    var result = json.data;
                    $ionicLoading.hide();
                    $scope.list = result.data;
                });
            };

            $scope.orderType = 0;
            getOrderList($scope.orderType);

            $scope.orderBy = function(status) {
                $ionicLoading.show({
                    noBackdrop:false,
                    duration: 10000
                });
                $scope.orderType = status;
                getOrderList($scope.orderType);
            };

            $scope.payOrder = function(oid) {
                Order.getOrderDetail(oid).then(function(json) {
                    $ionicLoading.hide();

                    var result = json.data;
                    var orderInfo = $scope.order = result.data;
                    if(orderInfo.goods_amount > 0 && orderInfo.payment_id > 0 && orderInfo.status == '等待付款') {
                        $ionicLoading.show({
                            template:'启动支付...',
                            noBackdrop:false,
                            duration: 5000
                        });

                        var code = '';
                        var getCode = GlobalFun.GetQueryString('code');

                        if(orderInfo.payment_id === ENV.payList.weixin && getCode === null) {
                            var callback = ENV.H5Url+'#/tab/done/'+orderInfo.oid;
                            var callUrl = WeiXin.getOauthCodeUrl(callback, orderInfo.oid);
                            window.location = callUrl;
                            return false;
                        }
                        code = getCode;
                        var host = location.host;
                        Order.payCheck(uid, oid, orderInfo.payment_id, {host:host, code:code}).then(function(json) {
                            var res = json.data;

                            if(res.status == 'succ') {
                                var chkpay = '';
                                if(orderInfo.payment_id === ENV.payList.appAlipay) {
                                    chkpay = GlobalFun.checkPayment('appAlipay');
                                    if(chkpay) {
                                        msg(chkpay);
                                        return false;
                                    }
                                    //支付宝app支付
                                    Payment.alipayPay(oid, res.data.pay_data);
                                }else if(orderInfo.payment_id === ENV.payList.weixin) {
                                    chkpay = GlobalFun.checkPayment('weixin');
                                    if(chkpay) {
                                        msg(chkpay);
                                        return false;
                                    }
                                    res.data.pay_data.oid = oid;
                                    WeiXin.wapPay(res.data.pay_data);
                                }else if(orderInfo.payment_id === ENV.payList.wapAlipay) {
                                    chkpay = GlobalFun.checkPayment('wapAlipay');
                                    if(chkpay) {
                                        msg(chkpay);
                                        return false;
                                    }
                                    window.location = res.data.pay_data;
                                }
                            }else{
                                msg(res.msg);
                            }
                        });
                    }else{
                        $state.go('tab.orderdetail', {id:oid});
                    }
                });
            };

            $scope.doRefresh = function() {
                getOrderList($scope.orderType);
                $scope.$broadcast("scroll.refreshComplete");
            };
        });

    })

    //订单详情 v2
    .controller('OrderDetailCtrl', function($scope, User, msg, $state, $ionicLoading, Order, $stateParams, $ionicHistory, Payment, GlobalFun, ENV) {
        $scope.goBack = function() {
            $ionicHistory.goBack();
        };

        $scope.$on('$ionicView.afterEnter', function() {

            var user = User._User();
            var uid  = user.user_id;
            if(user === false) {
                $state.go('login');
                return false;
            }
            $ionicLoading.show({
                noBackdrop:false,
                duration: 5000
            });
            $scope.uid = uid;

            var oid = $stateParams.id;
            if(oid <=0) {
                $state.go('tab.myorder');
            }
            //订单详情
            Order.getOrderDetail(oid).then(function(json) {
                $ionicLoading.hide();
                var result = json.data;
                if(result.status == 'fail') {
                    $state.go('tab.myorder');
                }
                $scope.order = result.data;
            });

            //订单支付
            $scope.payOrder = function(oid) {
                var orderInfo = $scope.order;
                if(orderInfo.goods_amount > 0 && orderInfo.payment_id > 0 && orderInfo.status == '等待付款') {
                    $ionicLoading.show({
                        template:'启动支付...',
                        noBackdrop:false,
                        duration: 5000
                    });

                    var code = '';
                    var getCode = GlobalFun.GetQueryString('code');

                    if(orderInfo.payment_id === ENV.payList.weixin && getCode === null) {
                        var callback = ENV.H5Url+'#/tab/done/'+orderInfo.oid;
                        var callUrl = WeiXin.getOauthCodeUrl(callback, orderInfo.oid);
                        window.location = callUrl;
                        return false;
                    }
                    code = getCode;
                    var host = location.host;
                    Order.payCheck(uid, oid, orderInfo.payment_id, {host:host, code:code}).then(function(json) {
                        var res = json.data;

                        if(res.status == 'succ') {
                            var chkpay = '';
                            if(orderInfo.payment_id === ENV.payList.appAlipay) {
                                chkpay = GlobalFun.checkPayment('appAlipay');
                                if(chkpay) {
                                    msg(chkpay);
                                    return false;
                                }
                                //支付宝app支付
                                Payment.alipayPay(oid, res.data.pay_data);
                            }else if(orderInfo.payment_id === ENV.payList.weixin) {
                                chkpay = GlobalFun.checkPayment('weixin');
                                if(chkpay) {
                                    msg(chkpay);
                                    return false;
                                }
                                res.data.pay_data.oid = oid;
                                WeiXin.wapPay(res.data.pay_data);
                            }else if(orderInfo.payment_id === ENV.payList.wapAlipay) {
                                chkpay = GlobalFun.checkPayment('wapAlipay');
                                if(chkpay) {
                                    msg(chkpay);
                                    return false;
                                }
                                window.location = res.data.pay_data;
                            }
                        }else{
                            msg(res.msg);
                        }
                    });
                }else{
                    $state.go('tab.orderdetail', {id:oid});
                }
            };

            //取消订单
            $scope.cancelOrder = function() {
                Order.CancelOrder($scope.order.oid).then(function(json) {
                    var result = json.data;
                    msg(result.msg);
                    $state.go('tab.orderdetail', {id:$scope.order.oid}, {reload:true});
                });
            };
        });


    });


