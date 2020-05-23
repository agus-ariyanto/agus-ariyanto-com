define([], function(){
    return ['$scope', '$auth','$window',
    function($scope, $auth,$window){
    /* awal controller */


    // menu perjadin
     $scope.menus=[
         {
             title:'Beranda',
             icon:'home',
             url:'home'
         },
         {
             title:'Data Pegawai',
             icon:'user',
             url:'pegawai'
         },
         {
             title:'Plafon Biaya',
             icon:'credit-card',
             url:'plafon'
         },
         {
             title:'Disposisi',
             icon:'tasks',
             url:'tugas'
         },
         {
             title:'Profil',
             icon:'user',
             url:'profil'
         }
     ];
    $scope.profile=$auth.profile; 


    $scope.goto=function(val){
        $window.location.href=alt.baseUrl+val;
    }
    $scope.logout=function(){
        $auth.logout();
        $window.location.href=alt.baseUrl+alt.defaultRoute;
    }


    /*akhir controller*/
    }];
});
