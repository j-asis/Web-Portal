<?php
class Comment extends AppModel
{
    public $validation = array(
        'body' => array(
            'length' => array(
                'validate_between', 1, 200,
                ),
            ),
        );
}
