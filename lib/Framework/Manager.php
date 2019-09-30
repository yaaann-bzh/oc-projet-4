<?php
namespace framework;

abstract class Manager 
{
    public $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public abstract function getList($debut, $fin);

    public abstract function count();

}
