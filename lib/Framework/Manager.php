<?php
namespace framework;

abstract class Manager 
{
    public $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public abstract function getList($debut, $limit, $filters = []);

    public abstract function count();

    public function exists($id)
    {
        $className = get_class($this);
        $lastNsPos = strripos($className, '\\') + 1;
        $table = lcfirst(substr($className, $lastNsPos, -7)) . 's';
        $sql = 'SELECT COUNT(*) FROM ' . $table;

        if ($this->dao->query($sql)->fetchColumn() > 0) {
            return true ;
        }
        else {
            return false ;
        }

    }

}
