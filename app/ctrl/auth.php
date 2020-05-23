<?php
/*
class auth dibuat untuk dapat dipanggil
dari kelas controller lain
return untuk kelas lain,
sedangkan untuk handle request
memakai $this->data
*/
class Auth extends Base{
    function __construct($mode=JSON){
        global $prefix;
        parent::__construct();
        $auth=ucfirst(strtolower($prefix)).'Auth';
        $this->auth=new $auth;
        $this->mode=$mode;
    }

    /*
        interface untuk class controller lainnya
        check otentifikasi dari request header
    */
    function check(){
    //    return $this->auth->select(3);
        $this->data(0);
        $header=getallheaders();
        if(empty($header['Authorization'])) return 0;
        $token=str_replace('Bearer ','',$header['Authorization']);
        $this->auth->andWhere('token',trim($token));
        $this->auth->limit(1);
        $data=$this->auth->select();

        /*chek user ada*/
        if($this->auth->num_rows<1) return 0;/*takada user*/
        unset($data[0]['pwd']);
        if($this->mode===NONE) return $data[0];
        $this->data($data[0]);
    }


    /*generate dan simpan token*/
    protected function saveToken($id){
        $token=sha1(uniqid());
        $this->auth->colVal('token',$token);
        $this->auth->save($id);
        return $token;
    }

    function signin(){
        $this->auth->andWhere('email',$this->params->key('email'));
        $this->auth->andWhere('pwd',sha1($this->params->key('password')));
        $data=$this->auth->select();
        if($this->auth->num_rows>0){
            $token=$this->saveToken($data[0]['id']);
            if($this->mode===NONE) return $token;
            return $this->data($token);
        }
        if($this->mode===NONE) return 0;
        $this->data(0);
    }


    function checkcount(){
        $c=$this->auth->countRec('1=1');
        if($this->mode===NONE) return $c;
        $this->data($c);
    }

    function forget(){
        // return url;
    }

    function profile(){
        $params=new Params;
        $this->auth->andWhere('token',$params->key('token'));
        $this->auth->limit(1);
        $data=$this->auth->select()[0];
        if($this->mode===NONE) return $data;
        $this->data($data);
    }

}
