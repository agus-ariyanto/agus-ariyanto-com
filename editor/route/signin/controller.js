define([ 'system/user' ], function(){
    return ['$scope', '$auth','$window','User',
    function($scope, $auth,$window, User){


      // menampilkan halaman default  login step=1
      $scope.step=1;
      // data yang dikirim untuk login
      $scope.data={email:'',password:''}
      $scope.signin=function(){
        User.signin($scope.data).then(
          function(res){
            if(res.data==0 || res.data==undefined){
              // gagal melakukan login res.data bernilai 0;
              // lihat file api/ctrl/user.php
              $window.alert('username dan password yang anda masukkan salah');
              return;
            }
            // res.data adalah token
            // token disimpan
            $auth.login(res.data);
            User.profile(res.data).then(
                function(val){
                    $auth.setProfile(val);
                    $window.location.href=alt.baseUrl+'home';
            });
         //   $window.alert('Success melakukan Login');
        });
      }

      $scope.forget=function(){
        // belum dibuat
        $window.alert('belum dibuat');
      }

/*end controller*/
        }];
});
