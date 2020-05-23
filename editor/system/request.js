define([ 'system/index' ], function(){
  alt.factory('Request', ['System',function(System){
    var api = System('api');
    // untuk request get
    api.Get=function(url,data){
        var d=data||{};
        var p=$.param(d);
      return this.connect(url+'&'+p,{},{method:'GET'});
    }
    // untuk request post
    api.Post=function(url,data){
      return this.connect(url,data);
    }
    // untuk request upload file
    api.Upload=function(url,data){
      return this.connect(url,data,{ismultipart:true});
    }

    

    return api;

    }]);
});
