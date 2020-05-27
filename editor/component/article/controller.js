define([ 'system/request' ], function(){
    return ['$scope', '$auth','$window','Request',
    function($scope, $auth,$window, Request){

        $scope.navs=[]
        Request.Get('nav').then(function(res){
            $scope.navs=res.data;
        });
        $scope.topics=[];
        $scope.changeNav=function(){
            Request.Get('topic',{
                nav_id:{
                    equal:$scope.article.nav_id
                }
            }).then(function(res){
                $scope.topics=res.data;
            });
        }
        $scope.article={}
        $scope.editArticle=0;
        $scope.init=function(val){
            $scope.editContent=0;
            $scope.article={};
            if(val) {
                $scope.article=val;
                $scope.changeNav();
                $scope.editArticle=0;
                $scope.getContents();
                return;
            }
            $scope.editArticle=1;
        }
        $scope.saveArticle=function(){
            Request.Post('article',$scope.article).then(function(res){
                $scope.article=res.data;
                $scope.article.human_date=Date.parse($scope.article.created).toString('dd MMM yyyy');
                $scope.editArticle=0;
            });
        }
        $scope.back=function(val){
            if(val && val.id){
                $scope.editContent=0;
                $scope.editArticle=0;
                return;
            }
            $scope.$parent.step=1;
        }

        $scope.editContent=0;
        $scope.showContentEditor=function(step,val){
            var id=$scope.article.id;
            $scope.cropImage={
                source:'',
                result:''
            }
            var a=['','text/html','text/javascript','image/jpeg','embed/video'];
            var mime=a[step];
            $scope.content={
                article_id:id,
                content_mime:mime,
                content_text:'',
                content_type:step
            }
            if(val) $scope.content=val;
            /*
            step 1 : htnkedit
                 2 : code
                 3 : image
                 4 : video
            */
            if(step==3){
                $('#upload-image').click();
                return;
            }
            $scope.editContent=step;
        }

        $scope.getContents=function(){
            var data={
                article_id:{
                    equal:$scope.article.id
                }
            }
            Request.Get('content',data)
            .then(function(res){
                $scope.contents=res.data;
            });
        }




        $scope.cmcodes=[
            {name: "C", mime: "text/x-csrc"},
            {name: "CSS", mime: "text/css"},
            {name: "HTML", mime: "text/html"},
            {name: "Java", mime: "text/x-java"},
            {name: "JavaScript", mime: "text/javascript"},
            {name: "Markdown", mime: "text/x-markdown"},
            {name: "MySQL", mime: "text/x-mysql"},
            {name: "Pascal", mime: "text/x-pascal"},
            {name: "PHP", mime: "text/x-php"},
            {name: "Python", mime: "text/x-python"},
            {name: "XML", mime:"text/xml"}
        ];
        $scope.editorOptions = {
    		lineWrapping : true,
    		lineNumbers: true,
    		mode: 'xml',
    	};
        $scope.readonlyOptions = function(optmode){
            let v=$scope.editorOptions;
            v.readOnly= 'nocursor';
            v.mode= optmode;
            return v;
        };
        $scope.changeOptions=function(){
            $scope.editorOptions.mode = $scope.content.content_mime;
        }

        var saveImage=function(){
            Request.Post('upload',{image:$scope.$scope.cropImage.result})
            .then(function(res){
                return res;
            })
            .then(function(res){
                $scope.content.content_text=res.data;
                return Request.Post('content',$scope.content);
            })
            .then(function(res){
                $scope.getContents();
                $scope.editContent=0;
            });
            return true;
        }
        $scope.saveContent=function(){
            /*khusus untuk image*/
            if($scope.content.content_type==3)
                return saveImage();
                
            /*bukan image*/
            $scope.content_text=$scope.cropImage.result;
            Request.Post('content',$scope.content)
            .then(function(res){
                $scope.getContents();
                $scope.editContent=0;
            });
        }

        $scope.cropImage={
            source:'',
            result:''
        }


        $scope.cropOptions={
            size : 'medium',
            type : 'rectangle',
            types : ['circle','square','rectangle'],
            resBlob : {},
            urlBlob : {},
            resImgFormat : 'image/jpeg',
            resImgQuality : 1,
            selMinSize : 200,
            selInitSize : {w: 200, h: 200},
            resImgSize : 'max',
            changeOnFly:true,
        }
        var uploadOnChange=function(e){
              var file=e.target.files[0];
              var reader = new FileReader();
              reader.onload = function (ee) {
                $scope.cropImage.source=ee.target.result;
                $scope.$apply();
              };
              $scope.editContent=3;
              reader.readAsDataURL(file);
        }
        angular.element(document.querySelector('#upload-image')).on('change', uploadOnChange);

        $scope.videoId=function(val){
            return Request.Get('videoid',{url:val})
            .then(
                function(res){
                    return res.data;
            });
        }
        /*end controller*/
        }];
});
