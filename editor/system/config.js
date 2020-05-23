alt.application = 'aac';
alt.title = 'Editor Agus-Ariyanto,com';
alt.description = '';
alt.version = '0.2.0';
alt.environment = 'development';

alt.serverUrl = '../?u=';

var d=Date.today().toString('yyyy.MM.dd');
alt.urlArgs = '_v=' + alt.version+'&t='+d;
alt.defaultRoute = 'signin';
alt.secure = {};

/* module disini */
alt.module('ngSanitize');
alt.module('ui.codemirror');
alt.module('ngImgCrop');
alt.module('simditor');
// set window title
document.title = alt.title;

// advanced configuration
alt.run(['$log', '$q', '$rootScope', '$route', '$window', '$auth',  '$timeout',
function($log, $q, $rootScope, $route, $window, $auth,  $timeout){
    $rootScope.$auth = $auth;

    $rootScope.$on('$routeChangeStart', function(event, currRoute, prevRoute){
      /*paksa kembali ke login bila belum login */
      if(currRoute.params.altaction!=alt.defaultRoute){
        //  if(!$auth.islogin()) $window.location.href = alt.baseUrl + alt.defaultRoute;
      }
    });
 }]);
