<?php
class AcComment extends Model{
    protected $alias='comment';
    /*
    content_type 1 comment
                 2 reply
                 3 reply admin
                 10 deleted
     */
    protected $columns=array(
        'article_id'=>'INT',
        'comment_id'=>'INT DEFAULT 0',
        'email'=>'VARCHAR(128)',
        'nickname'=>'VARCHAR(128)',
        'content_type'=>'INT DEFAULT 1',
        'content_text'=>'VARCHAR(256)',
    );
}
