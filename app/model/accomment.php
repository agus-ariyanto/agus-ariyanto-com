<?php
class AcComment extends Model{
    protected $alias='comment';
    protected $columns=array(
        'article_id'=>'INT',
        'email'=>'VARCHAR(128)',
        'nickname'=>'VARCHAR(128)',
        'content_type'=>'INT DEFAULT 1',
        'content_text'=>'VARCHAR(256)',
    );
}
