define([ 'system/request' ], function(){
    return ['$scope', '$auth','$window','Request',
    function($scope, $auth,$window, Request){
        /*begin controller*/
        $scope.editorOptions = function(mime){
            return {
                lineWrapping : true,
                lineNumbers: true,
                matchBrackets: true,
                mode:mime,
            }
        }

        $scope.article={}
        $scope.editArticle=0;
        $scope.contents=[]
        $scope.content={}
        $scope.saveArticle=function(){
            Request.Post('article',$scope.article).then(function(res){
                $scope.step=1;
            });
        }
        $scope.saveContent=function(){
            Request.Post($scope.content).then(function(res){
                if($scope.content.id){
                    var i=$scope.contents.map(function(val){
                        return val.id;
                    }).indexOf($scope.content.id);
                    $scope.contents[i]=res.data;
                    return;
                }
                $scope.contents.push(res.data);
            });
        }
        $scope.init=function(val){
            var v=val||{};
            $scope.article=v;
            if(val){
                $scope.editArticle=1;
                return;
            }
            $scope.editArticle=0;
        }
        $scope.getContents=function(val){
            Request.Get('content',{
                article_id:{
                    equal:val.id
                }
            })
            .then(function(res){
                $scope.contents=res.data;
            });
        }


        /*end controller*/
        }];
});
