define([ 'system/request' ], function(){
    return ['$scope', '$auth','$window','Request',
    function($scope, $auth,$window, Request){
        /*begin controller*/
        $scope.sidebar={}
        $scope.nav=[];
        $scope.topics=[];
        $scope.data={
            id:'',
            title:'',
            description:''
        }
        $scope.dialogs=0;

        $scope.getNav=function(){
            $scope.nav=[];
            Request.Get('nav',{order:'id'})
            .then(function(res){
                $scope.nav=res.data;
                $scope.getTopics($scope.nav[0].id);
            });
        }
        $scope.addNav=function(){
            $scope.data={
                id:'',
                title:'',
                description:''
            }
            $scope.dialogs=0;
            $('#modal-dialog').modal('show');
        }
        $scope.editNav=function(val){
            $scope.data=val;
            $scope.dialogs=0;
            $('#modal-dialog').modal('show');
        }

        $scope.getTopics=function(idnav){
            $scope.topics=[];
            Request.Get('topic',{order:'id',nav_id:{equal:idnav}})
            .then(function(res){
                $scope.topics=res.data;
                $scope.nav_active=idnav;
            });
        }

        $scope.addTopic=function(idnav){
            $scope.data={
                nav_id:$scope.nav_active,
                id:'',
                title:'',
                description:''
            }
            $scope.dialogs=1;
            $('#modal-dialog').modal('show');
        }
        $scope.editTopic=function(val){
            $scope.data=val;
            $scope.dialogs=1;
            $('#modal-dialog').modal('show');
        }

        $scope.save=function(){
            $('#modal-dialog').modal('hide');
            if($scope.dialogs==0){
                return Request.Post('nav',$scope.data)
                .then(function(){
                    $scope.getNav();
                    $scope.nav_active=$scope.nav[0].id;
                    return true;
                });
            }
            Request.Post('topic',$scope.data)
            .then(function(res){
                $scope.getTopics($scope.data.nav_id);
            });
        }

        $scope.getNav();
        /*end controller*/
        }];
});
