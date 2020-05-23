<?php
class DbJoin extends DbSQL{
    function __construct(){
        $this->connected=$this->connect()!==false;
    }

    function model($model){
        global $prefix;
        return ucfirst(strtolower($prefix)).ucfirst(strtolower($model));
    }

    function init($model){
        $class=$this->model($model);
        $table=new $class;
        $tableName=$table->tableName();
        $tableAlias=$table->tableAlias();
        $columns=array();
        $statementjoin=array();
        foreach($table->colNames() as $key=>$value) $columns[$key]=$tableAlias.'.'.$key;
        $join=array();
        if(!empty($table->join)){
            $var=explode(',',$table->join);
            foreach ($var as $value) {
                $class=$this->model($value);
                $jointable=new $class;
                $name=$jointable->tableName();
                $alias=$jointable->tableAlias();
                $cols=$jointable->colNames();
                unset($cols['id']);
                foreach($cols as $_key => $_val) {
                    $s=$alias.'_'.$_key;
                    $columns[$s]=$alias.'.'.$_key;
                }
                $join[]=$jointable;
            }
        }
         $init = new stdClass;
         $init->table=$table;
         $init->columns=$columns;
         $init->join=$join;
        return $init;

    }
    function querySelect($model,$params,$optand=''){
        global $db;
        /*
        gt : greater than
        gte : greater than or equal
        lt : less than
        lte : less than or equal
        */
        $oprs=array(
            'like'=>'like',
            'equal'=>'=',
            'notequal'=>'<>',
            'in'=>'in',
            'lt'=>'<',
            'lte'=>'<=',
            'gt'=>'>',
            'gte'=>'>=',
            'is'=>'is',
            'bw'=>'BETWEEN',
        );

        //if(!empty($params['id'])&&is_numeric($params['id'])) $id=$params['id'];
        $init=$this->init($model);
        $statementjoin=array();
        foreach($init->join as $value)
        $statementjoin[]=' LEFT JOIN '.$value->tableName().' '.$value->tableAlias().' ON '.$init->table->tableAlias().'.'.$value->tableAlias().'_id = '.$value->tableAlias().'.id';

        foreach ($init->columns as $key => $value) $cols[]=$value.' AS '.$key;

        if(!empty($id)) return  'SELECT '.implode(',',$cols).' FROM '.$init->table->tableName().
                ' '.$init->table->tableAlias(). implode(' ',$statementjoin).' WHERE '.
                $init->table->tableAlias().
                '.id = '.$this->sanitize($id).'  ';

         //$where='1=1';
         $qr=array();
         foreach($init->columns as $key=>$value){
             foreach($params as $paramkey=>$paramvalue){
                 if($key==$paramkey){
                     foreach($oprs as $opr=>$opvalue){
                         if(isset($params[$key][$opr])){
                             /*null mendapat perlakuan khusus*/
                             $stat=trim($params[$key][$opr]);
                             if(strtolower($stat)!='null'){
                                 $a=explode('+',$stat);
                                 $b=array();
                                 foreach ($a as $i) $b[]=$this->sanitize( str_replace('*','%',$i) );
                                 $stat=implode(' AND ',$b);
                             }
                             /*in mendapat perlakuan khusus*/
                             if($opr=='in'){
                                 // stat direset lagi ke nilai awal
                                 $stat=trim($params[$key][$opr]);
                                 $a=explode(',',$params[$key][$opr]);
                                 $b=array();
                                 foreach ($a as $i) $b[]=$this->sanitize($i);
                                 $stat='('.implode(',',$b).')';
                             }
                             // range untuk kolom sama belum nemu

                             $qr[]=$value.' '.$opvalue.' '.$stat;
                         }
                     }
                 }
             }

             /*foreach($oprs as $opr){

                 if(isset)
                 //if(isset($params[$opr]))
             }*/
         }

         //$glue=empty($params['and']) ? 'OR':'AND';
         //$where=count($qr)>0?implode(' '.$glue.' ',$qr):' 1=1 ';
         $glue=empty($params['and']) ? 'OR':'AND';
         $where = count($qr)>0 ? implode(' '.$glue.' ',$qr) : ' 1=1 ';
         if(!empty($optand)) $where = $where. ' AND '.$optand;

         $lim=isset($params['limit']) ?  $params['limit'] : $db['offset'];
         $of=isset($params['offset']) ? $params['offset']:'0';
         $of=isset($params['page']) ? ($params['page'] - 1) * $lim : $of;
         $order=empty($params['order']) ? '':' ORDER BY '.$params['order'];
         $group=empty($params['group']) ? '':' GROUP BY '.$params['group'];
         $limit = ' LIMIT '.$of.','.$lim;
         $result['limit']=$lim;
         $result['offset']=$of;
         $result['page']=isset($params['page']) ? $params['page'] : 0;

        $result['qry'] = 'SELECT '.implode(',',$cols).' FROM '.$init->table->tableName().
                         ' '.$init->table->tableAlias().implode(' ',$statementjoin).
                         ' WHERE '.$where.$order.$group.$limit;
        $result['countqry']= 'SELECT count(*) AS c '.' FROM '.
                              $init->table->tableName().' '.$init->table->tableAlias().
                              implode(' ',$statementjoin).' WHERE '.$where;
        return $result;
    }

    function select($model,$optand=''){

        $params=new Params;
        //$result=$this->querySelect($model,$params->all());
        $result=$this->querySelect($model,$params->all(),$optand);
        $result['count']=$this->query($result['countqry'])[0]['c'];

        $result['data']=$this->query($result['qry']);
        return $result;
    }

    function id($model,$id=''){

         $qry=$this->querySelect($model,array(),$id);
         $result=$this->query($qry);
        // return $result[0];

         if(count($result)>0){
            $record=$result[0];
            $init=$this->init($model);
            $child=$init->table->child;
            if(empty($child)) return $record;
            $ex=explode(',',$init->table->child);
            $model_id=$init->table->tableAlias().'_id';
            $param=array( $model_id=>$record['id']);
            foreach($ex as $value) $record[$value]=$this->query($this->querySelect($value,$param));
            return $record;
         }
        return 0;
    }

    function queryInsert($model,$params){
        $class=$this->model($model);
        $class=new $class;
        // $id=$class->savePost($params->all());
        // return $class->select($id);

        $colkeys=array();
        $colvals=array();
        foreach($class->colNames() as $key => $value)
            foreach ($params as $parkey => $parvalue) {
                if($key==$parkey) {
                    $colkeys[]=$parkey;
                    $colvals[]=$this->sanitize($parvalue);
                }
            }
        return  'INSERT IGNORE INTO '.$class->tableName().
                ' ('.implode(',',$colkeys).
                ')VALUES('.implode(',',$colvals).')';
    }
    /*dirubah methodnya untuk insert custom_id*/
    function insert($model,$params){
        return $this->query($this->queryInsert( $model,$params->all()));
    }

    function update($model,$id=''){
        $params=new Params;
        if(!empty($id)) $params->set('id',$id);
        $class=$this->model($model);
        $class=new $class;
        $id=$class->savePost($params->all());
        return $class->select($id);
    }

    function search($model){
        //$params=new Params;
        //belum
        return $this->select($model);
    }

}
