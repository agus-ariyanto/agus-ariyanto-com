define(['system/index'], function(){
    alt.factory('User', ['System',function(System){
        var api = System('auth');
        api.signin=function(data){
            return this.connect('signin',data);
        }
        api.forget=function(data){
            return this.connect('forget',data);
        }
        api.profile=function(token){
            return this.connect('profile',{token:token});
        }
        return api;
    }]);
});
