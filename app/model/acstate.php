<?php
class AcState extends Model{
    protected $alias='state';
    protected $columns=array(
        'title'=>'VARCHAR(16)'
    );
    protected $firstdata=array(
        array(
            'id'=>'1',
            'title'=>'Draft'
        ),
        array(
            'id'=>'2',
            'title'=>'Published'
        )
    );
}
