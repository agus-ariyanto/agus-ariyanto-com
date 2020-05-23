<?php
class AcArticle extends Model{
    protected $alias='article';
    protected $columns=array(
        'profile_id'=>'INT',
        'nav_id'=>'INT',
        'topic_id'=>'INT',
        'state_id'=>'INT DEFAULT 0',
        'title'=>'VARCHAR(128)',
        'preview'=>'TEXT',
        'created'=>'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'published'=>'TIMESTAMP',
        'hits'=>'INT DEFAULT 0'
    );
    public $child='content,tag';
    public $join='profile,nav,topic,state';

    // custem function on spec model
    function addHits($id=''){
        if(empty($id)) return;
        $this->hits='hits + '.'1';
        $this->save($id);
    }
}
