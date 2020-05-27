<?php
class Api extends Base{
    function __construct(){
		parent::__construct();
        $this->model=$this->query[0];
        $this->id=$this->parseId();
        $this->mode=JSON;
	}

    protected function isLogin(){
        //return true;
        $auth=new Auth(NONE);
        $this->auth=$auth->check();
        return $this->auth!==0;
    }

    protected function parseId(){
        if(!empty($this->query[1])&&is_numeric($this->query[1]))
            return $this->query[1];

        $id=$this->params->key('id');
        if(!empty($id)&&is_numeric($id)) return $id;
        return '';
    }

    /*
    request dengan standar restfull
        ?u=api/model/id
    */

     function index(){
         // if($this->isLogin()===false)
         //     return $this->forbidden();
         if(empty($this->model))
             return  $this->notfound();

         if($this->params->isGet){
             if(!empty($this->id)){
                 $this->id();
                 return;
             }
             $this->select();
             return;
         }
         if($this->params->isPost){
             $this->save();
             return;
         }
         $this->remove();
     }

     protected function id(){
         $model=$this->model;

         if($this->addModel($model)){
             $join=explode(',',$this->$model->join);

             // master joined table
            foreach($join as $value){
                if($this->addModel($value)) $this->$model->join($this->$value);
            }
            $data=$this->$model->select($this->id);
            if(count($data)<1) return $this->notfound();

            //child table
            $child=explode(',',$this->$model->child);
            foreach($child as $value){
                if($this->addModel($value)) {
                    $child_join=explode(',',$this->$value->join);
                    foreach ($child_join as $chvalue) {
                        if($this->addModel($chvalue))
                           $this->$value->join($this->$chvalue);
                    }
                    $this->$value->andWhere($this->$model->tableAlias().'_id',$id);
                    $data[$value]=$this->$value->select();
                }
            }
            $this->data($data);
        }
    }

     protected function select(){
         $db=new DbJoin;
         $this->data( $db->select($this->model) );
     }


     protected function save(){
         $model=$this->model;
         $this->addModel($model);
         if(!empty($this->id)) $this->params->set('id',$this->id);
         $id=$this->$model->savePost($this->params->all());
         $this->data($this->$model->select($id));
     }

     protected function remove(){
         if(empty($this->id)) return $this->notfound();
         $model=$this->model;
         $this->addModel($model);
         $count=$this->$model->delete($this->id);
         $success=$count>0;
         $id=$this->id;
         $this->data( array( 'deleted'=>$success, 'id'=>$id ) );
     }


     /*
         custom request untuk post imageuri ke file
         return alamat uri relatif file tsb
         mis
            ?u=api/upload/data/png
            maka file tersebut disimpan dalam upload/data/ dengan extensi png
     */

     function upload(){
         $dir=empty($this->query[0]) ? 'image' : $this->query[0];
         $ext=empty($this->query[1]) ? '.jpg' : '.'.$this->query[1];
         $file=uniqid().$ext;
         $img=str_replace('data:image/jpeg;base64,','',$this->params->key('image'));
         $data=base64_decode($img);
         file_put_contents(ROOT_DIR.DS.'upload'.DS.$dir.DS.$file,$data);
         $this->data('upload/'.$dir.'/'.$file);
     }

     function videoid(){
         /*baru youtube saja*/
         $url=$this->params->key('url');
         // http://youtu.be/dQw4w9WgXcQ
         // http://www.youtube.com/embed/dQw4w9WgXcQ
         // http://www.youtube.com/watch?v=dQw4w9WgXcQ
         // http://www.youtube.com/?v=dQw4w9WgXcQ
         // http://www.youtube.com/v/dQw4w9WgXcQ
         // http://www.youtube.com/e/dQw4w9WgXcQ
         // http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ
         // http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ
         // http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ
         // http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ

         // It also works on the youtube-nocookie.com URL with the same above options.
         // It will also pull the ID from the URL in an embed code (both iframe and object tags)
         preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
         $youtube_id = $match[1];
         $this->data($video_id);
     }





/*end controller*/
}
