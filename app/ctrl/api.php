<?php
class Api extends Base{
    function __construct(){
		parent::__construct();
        $this->model=$this->query[0];
        $this->id=$this->parseId();
        $this->mode=JSON;
	}

    function parseId(){
        if(!empty($this->query[1])&&is_numeric($this->query[1]))
            return $this->query[1];

        $id=$this->params->key('id');
        if(!empty($id)&&is_numeric($id)) return $id;
        return '';
    }

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

     function select(){
         $db=new DbJoin;
         $this->data( $db->select($this->model) );
     }


     function save(){
         $model=$this->model;
         $this->addModel($model);
         if(!empty($this->id)) $this->params->set('id',$this->id);
         $id=$this->$model->savePost($this->params->all());
         $this->data($this->$model->select($id));
     }

     function remove(){
         if(empty($this->id)) return $this->notfound();
         $model=$this->model;
         $this->addModel($model);
         $count=$this->$model->delete($this->id);
         $success=$count>0;
         $id=$this->id;
         $this->data( array( 'deleted'=>$success, 'id'=>$id ) );
     }


     protected function isLogin(){
         //return true;
         $auth=new Auth(NONE);
         $this->auth=$auth->check();
         return $this->auth!==0;
     }


/*end controller*/
}
