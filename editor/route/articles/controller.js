define([ 'system/request' ], function(){
    return ['$scope', '$auth','$window','Request',
    function($scope, $auth,$window, Request){
        /*begin controller*/
        $scope.sidebar={}
        $scope.article={}
        $scope.articles=[];
        $scope.searchText='';
        $scope.step=1;
        $scope.newArticle=function(){
            $scope.article.init();
            $scope.step=2;
        }
        $scope.editArticle=function(val){
            $scope.article.init(val);
            $scope.step=2;
        }
        $scope.init=function(){
            var data={order:'id DESC'}
            if($scope.searchText!==''){
                var s='*'+$scope.searchText+'*';
                data.title={
                    like:s
                }
            }
            Request.Get('article',data).then(function(res){
                $scope.articles=res.data;
            });
        }

        $scope.changeState=function(val,state){

            state=state||1;
            if(val.state_id==1) state=2;

            Request.Post('article/'+val.id,{state_id:state}).then(function(res){
                return Request.Get('article/'+val.id);
            })
            .then(function(res){
                var i=$scope.articles.map(function(v){
                    return v.id;
                }).indexOf(val.id);
                $scope.articles[i]=res.data;
            });
        }

        $scope.$watch('article.modified', function(val){
            if(val===true){

                Request.Get('article/'+$scope.article.article.id)
                .then(function(res){
                    if(res.data.id){
                        var i=$scope.articles.map(function(v){return v.id;}).indexOf(res.data.id);
                        if(i>-1) $scope.articles[i]=res.data;
                            else $scope.articles.unshift(res.data);
                    }
                });
            }
        });

        $scope.init();


        /*end controller*/
        }];
});
