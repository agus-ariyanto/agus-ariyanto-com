<?php
class AcNav extends Model{
    protected $alias='nav';
    protected $columns=array(
        'title'=>'VARCHAR(128)',
        'description'=>'TEXT',
    );
    protected $firstdata=array(
        array(
            'title'=>'Blog',
            'description'=>'-',
        ),
        array(
            'title'=>'Basic',
            'description'=>'-',
        ),
        array(
            'title'=>'Snippet',
            'description'=>'-',
        ),
        array(
            'title'=>'Framework',
            'description'=>'-',
        ),
    );

}
