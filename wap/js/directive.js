var directiveMod=angular.module("starter.directive", []);

directiveMod.directive('showTabs', function ($rootScope) {
    return {
        restrict: 'A',
        link: function ($scope, $el) {
            $rootScope.hideTabs = false;
        }
    };
}).directive('hideTabs', function ($rootScope) {
    return {
        restrict: 'A',
        link: function ($scope, $el) {
            $rootScope.hideTabs = true;
        }
    };
});


