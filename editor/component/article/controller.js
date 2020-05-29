define([ 'system/request' ], function(){
    return ['$scope', '$auth','$window','Request',
    function($scope, $auth,$window, Request){
        $scope.modified=false;
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
            $scope.modified=false;
            $scope.editContent=0;
            $scope.article={
                title:'',
                preview:''
            };
            $scope.contents=[];
            if(val){
                $scope.article=val;
                $scope.article.human_date=Date.parse($scope.article.created).toString('dd MMM yyyy');
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
                return $scope.article;
            })
            .then( function(res){
                $scope.modified=true;
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
            $scope.content=val||{
                article_id:id,
                content_mime:mime,
                content_text:'',
                content_type:step
            }
            $scope.editContent=step;

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

        }

        $scope.getContents=function(){
            var data={
                article_id:{
                    equal:$scope.article.id
                },
                order:'id'
            }
            Request.Get('content',data)
            .then(function(res){
                $scope.contents=res.data;
                hljs.initHighlightingOnLoad();

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
    		mode: 'text/javascript'
    	};
        $scope.readonlyOptions = function(optmode){
            return {
                lineWrapping : true,
        		lineNumbers: false,
                readOnly: 'nocursor',
                showLineNumber:false,
                mode: optmode
            }
        };

        $scope.changeOptions=function(){
            $scope.editorOptions.mode = $scope.content.content_mime;
        }

        var saveImage=function(){
            Request.Post('upload',{image:$scope.cropImage.result})
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
        }
        $scope.video={url:''};

        var saveVideoId=function(){
            Request.Post('videoid',{url:$scope.video.url})
            .then(function(res){
                $scope.content.content_text=res.data;
                $scope.content.content_mime='embed/youtube';
                return Request.Post('content',$scope.content);
            })
            .then(function(res){
                $scope.getContents();
                $scope.editContent=0;
            });
        }
        $scope.saveContent=function(){
            /*khusus untuk image*/
            if($scope.content.content_type==3) return saveImage();
            if($scope.content.content_type==4) return saveVideoId();
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
            //return 'https://www.youtube.com/embed/'{{c.content_text}}
            return 'https://www.youtube.com/embed/'+val;
        }
        /*end controller*/
        }];
});
