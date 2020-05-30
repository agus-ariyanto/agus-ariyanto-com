define([], function(){
    return ['$scope', '$auth','$window',
    function($scope, $auth,$window){
    /* awal controller */


    // menu perjadin
     $scope.menus=[
         {
             title:'Beranda',
             icon:'home',
             url:'home',
             active:false
         },
         {
             title:'Topics',
             icon:'list-alt',
             url:'topics',
             active:false
         },
         {
             title:'Articles',
             icon:'file',
             url:'articles',
             active:false
         },
         {
             title:'Comments',
             icon:'tasks',
             url:'comments',
             active:false
         },
         {
             title:'Profil',
             icon:'user',
             url:'profil',
             active:false
         }
     ];
//    $scope.profile=$auth.profile;


    $scope.goTo=function(val){
        $window.location.href=alt.baseUrl+val;
    }
    $scope.active=0;
    $scope.$watch('active',function(val){
        for(var i=0;i<$scope.menus.length;i++){
            $scope.menus[i].active=false;
        }
        $scope.menus[val].active=true;
    });

    $scope.logout=function(){
        $auth.logout();
        $window.location.href=alt.baseUrl+alt.defaultRoute;
    }


    /*akhir controller*/
    }];
});
