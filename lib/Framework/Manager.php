<?php
namespace framework;

abstract class Manager 
{
    public $dao;
    protected $entities;
    protected $table;

    public function __construct($dao)
    {
        $this->dao = $dao;
        $this->table = $this->getTable($this->entities);
    }

    private function getTable($entities)
    {
        $xml = new \DOMDocument;
        $xml->load(__DIR__.'/../../config/tables.xml');
        
        $elements = $xml->getElementsByTagName('define');
        $tables = [];

        foreach ($elements as $element)
        {
            $tables[$element->getAttribute('var')] = $element->getAttribute('value');
        }
        
        if (isset($tables[$entities]))
        {
            return $tables[$entities];
        }

        return null;
    }

    public abstract function getList($debut, $limit, $filters = []);

    public abstract function count();

    public function exists($id)
    {
        $className = get_class($this);
        $lastNsPos = strripos($className, '\\') + 1;
        $sql = 'SELECT COUNT(*) FROM ' . $this->table;

        if ($this->dao->query($sql)->fetchColumn() > 0) {
            return true ;
        }
        else {
            return false ;
        }

    }

}
