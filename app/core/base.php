<?php

/*
* Kelas BaseCtrl
* semua controller harap diturunkan dari kelas ini
* kelas post,get diganti dengan class params
*/

define('NONE',0);
define('HTML',1);
define('JSON',2);


class Base{
    function __construct(){
        global $query;
        global $method;
        global $controller;

        $this->query=$query;
        $this->params=new Params;
        $this->status=200;
        $this->vars=array();
        $this->mode=HTML;
        $this->method=strtolower($method);
        $this->controller=strtolower($controller);
    }

    /* akhir merender format json variabel $result */
    function __destruct(){
        if($this->status=200){
            if($this->mode=HTML){
                //render html
                //header('Content-Type: text/html');
                include(ROOT_DIR.DS.'view'.DS.$this->controller.DS.$this->method.'.php');
            }
            if($this->mode=JSON){
                //render html
                header('Content-Type: application/json');
                echo json_encode($this->vars);
            }
        }
        http_response_code($this->status);
    }

    function data($value){
        $this->vars=$value;
    }
    function addModel($model){
        global $prefix;
        $tbl=ucfirst(strtolower($prefix)).ucfirst(strtolower($model));
        if(!class_exists($tbl)) return false;
        if(isset($this->$model)) return true;
        $this->$model=new $tbl;
        return true;
    }
    function addClass($class){
        $class=ucfirst(strtolower($class));
        if(!class_exists($class)) return false;
        if(isset($this->$class)) return true ;
        $this->$class=new $class;
        return true;
    }

    function notfound(){
        $this->status=404;
        $this->data(array('notfound'=>true));
        return false;
    }
    function forbidden(){
        $this->status=403;
        $this->data(array('needlogin'=>true));
        return false;
    }

}
