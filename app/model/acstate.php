<?php
class AcState extends Model{
    protected $alias='state';
    protected $columns=array(
        'title'=>'VARCHAR(16)'
    );
    protected $firstdata=array(
        array(
            'id'=>'0',
            'title'=>'Draft'
        ),
        array(
            'id'=>'1',
            'title'=>'Published'
        ),
        array(
            'id'=>'2',
            'title'=>'Hidden'
        )
    );
}
