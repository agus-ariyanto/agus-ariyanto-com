<?php
class AcContent extends Model{
    protected $alias='content';
    protected $columns=array(
        'article_id'=>'INT',
        'created'=>'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'content_mime'=>'VARCHAR(64)',
        'content_text'=>'TEXT',
    );
}
