define([
], function(){
    alt.factory('System', ['$log', '$api',  '$rootScope',  function($log, $api,  $rootScope ){
        return function(url){
            //pkey = pkey || '';
            return $api(url, '', {
                connect: function(params){
                    //$loading.show();
                },
                success: function(response){
                    //$loading.close();
                },
                error: function(error, params, deferred, iscancelled){
                    //$loading.close();
                    if(iscancelled) return true;

                    //error.message = error.message || (error.data ? error.data.message : 'Error tidak diketahui');
                    // if(typeof error.message === "string"){
                    //     $alert.add(error.message, $alert.danger);
                    // }else if(error.message.length){
                    //     angular.forEach(error.message, function(message){
                    //         $alert.add(message, $alert.danger);
                    //     });
                    // }
                }
            });
        };
    }]);
});
