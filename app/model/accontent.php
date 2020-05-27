<?php

/*
contnet_type
1 : Htmltext,
3 : Code,
3 : image,
4 : Video,
content_mime untuk coddemirror

*/
class AcContent extends Model{
    protected $alias='content';
    protected $columns=array(
        'article_id'=>'INT',
        'created'=>'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'content_type'=>'INT DEFAULT 1',
        'content_mime'=>'VARCHAR(128)',
        'content_text'=>'TEXT',
    );
}
