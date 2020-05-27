define([ 'system/request' ], function(){
    return ['$scope', '$auth','$window','Request',
    function($scope, $auth,$window, Request){
        /*begin controller*/
        $scope.sidebar={}
        $scope.article={}
        $scope.articles=[];
        $scope.step=1;
        $scope.newArticle=function(){
            $scope.article.init();
            $scope.step=2;
        }
        $scope.editArticle=function(val){
            console.log(val);
            $scope.article.init(val);
            $scope.step=2;
        }

        Request.Get('article').then(function(res){
         $scope.articles=res.data;
        });

        /*end controller*/
        }];
});
