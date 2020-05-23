<?php
class AcAuth extends Model{
    protected $alias='auth';
    protected $columns=array(
        'email'=>'VARCHAR(128)',
        'pwd'=>'VARCHAR(128)',
        'token'=>'VARCHAR(128)',
        'created'=>'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'lastlogin'=>'DATETIME ON UPDATE CURRENT_TIMESTAMP',
        'usergroup'=>'INT DEFAULT 1',
    );

    /*password admin d033e22ae348aeb5660fc2140aec35850c4da997*/
    protected $firstdata=array(
        array(
            'id'=>'1',
            'email'=>'admin@email.com',
            'pwd'=>'d033e22ae348aeb5660fc2140aec35850c4da997',
            'token'=>'d033e22ae348aeb5660fc2140aec35850c4da997',
        ),
    );
}
