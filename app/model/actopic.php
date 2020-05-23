<?php
class AcTopic extends Model{
    protected $alias='topic';
    protected $columns=array(
        'nav_id'=>'INT',
        'title'=>'VARCHAR(128)',
        'description'=>'TEXT',
    );
    public $join=array('nav');

}
