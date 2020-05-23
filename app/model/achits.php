<?php
class AcHits extends Model{
    protected $alias='hits';
    protected $columns=array(
        'first_view'=>'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'first_view'=>'TIMESTAMP DEFAULT ON UPDATE CURRENT_TIMESTAMP',
        'counter'=>'INT DEFAULT 1',
        'req_uri'=>'VARCHAR(256)',
        'user_agent'=>'VARCHAR(256)',
    );
    function createTable(){
        $fields=array( 'id VARCHAR(32) PRIMARY KEY UNIQUE' );
        foreach($this->columns as $key=>$value)
            array_push($fields,$key.' '.$value);
        $query='CREATE TABLE IF NOT EXISTS '.$this->tableName().
                '( '.implode(', ',$fields).')'.
                'ENGINE=InnoDb DEFAULT CHARSET=utf8';
        $this->query($query);
    }
    function save(){
        $query= 'INSERT INTO '.$this->tableName().
                '(id,req_uri,user_agent)VALUES( '.
                $this->sanitize($_SERVER['REMOTE_ADDR']).','.
                $this->sanitize($_SERVER['REQUEST_URI']).','.
                $this->sanitize($_SERVER['HTTP_USER_AGENT']).' )'.
                ' ON DUPLICATE KEY UPDATE '.
                'req_uri = '.$this->sanitize($_SERVER['REQUEST_URI']).','.
                'user_agent = '.$this->sanitize($_SERVER['HTTP_USER_AGENT']).','.
                'counter = counter + 1';
        return $this->query($query);
    }
    
}
