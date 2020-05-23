define([ 'system/request' ], function(){
    return ['$scope', '$auth','$window','Request',
    function($scope, $auth,$window, Request){
        $scope.navs=[];
        var init=function(){
            Request.Get('category'),then(function(res){
                $scope.navs=res.data;
            });
        }

/*end controller*/
        }];
});
