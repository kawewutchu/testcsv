<?php
require_once ('AbstractModel.php');

class ApiModel extends AbstractModel
{

    public $object;

    public function __construct()
    {
        parent::__construct();
    }

    public function has_required_error()
    {
        return False;
    }

    public function has_datatype_error()
    {
        return False;
    }

    public function has_value_error()
    {
        return False;
    }

    public function exec(&$result)
    {
        // ignore
    }
    
    public function download()
    {
        // ignore
    }
}