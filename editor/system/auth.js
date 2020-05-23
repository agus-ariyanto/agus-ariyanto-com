alt.modules.auth = angular.module('alt-auth', [])
    .factory('$auth', ['$log', '$window',  function($log, $window){
        // mengambil data token yang disimpan di lokal
        store.set(alt.application + '_token', store.get(alt.application + '_token') || '0');
        store.set(alt.application + '_profile', store.get(alt.application + '_profile') || {});
        // nilai default token 0 bila belum login

        return {
            token: '0',
            profile:{},
            set_token: function(token){
                this.token = token;
                store.set(alt.application + '_token', this.token);
            },
            setProfile: function(profile){
                this.profile = profile;
                store.set(alt.application + '_profile', this.profile);
            },

            login: function(data){
                this.set_token(data);
            },
            logout: function(){
                this.token = '0';
                store.set(alt.application + '_token', '0');
            },
            islogin: function(){
                return this.token != '0';
            }
        };
    }])
    .config(['$provide', '$httpProvider', function($provide, $httpProvider){
        $provide.factory('authHttpInterceptor', ['$auth', '$log', '$q', '$window', function($auth, $log, $q, $window){
            return {
                request: function(config){
                    if($auth.islogin) config.headers['Authorization']='Bearer '+$auth.token;
                    return config;
                }
            };
        }]);

        $httpProvider.interceptors.reverse().push('authHttpInterceptor');
        $httpProvider.interceptors.reverse();
    }])
    .run(['$auth', '$log', function($auth, $log){
        var token = store.get(alt.application + '_token');
        if(token) $auth.login(store.get(alt.application + '_token'));
    }]);

alt.module('alt-auth', alt.modules.auth);
