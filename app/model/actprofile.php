<?php
class AcProfile extends Model{
    protected $alias='profile';
    protected $columns=array(
        'auth_id'=>'INT',
        'pwd'=>'VARCHAR(128)',
        'longname'=>'VARCHAR(128)'
        'nickname'=>'VARCHAR(32)',
        'image'=>'VARCHAR(128)',
        'about'=>'TEXT',
    );

    protected $firstdata=array(
        array(
            'id'=>'1',
            'auth_id'=>'1',
            'longname'=>'administrator',
            'nickame'=>'admin',
            'about'=>'Ini adalah administrator',
        ),
    );
}
