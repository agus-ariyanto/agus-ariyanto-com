<?php
class AcTag extends Model{
    protected $alias='tag';
    protected $columns=array(
        'article_id'=>'INT',
        'title'=>'VARCHAR(32)',
    );
}
