<?php

$prefixe = 'forteroche\\';
$className = 'forteroche\vendor\model\PostManager';
$_fileExtension = '.php';
$_namespace = 'forteroche\model';
$_includePath = __DIR__.'/../vendor/model';
$_namespaceSeparator = '\\';  
var_dump($_namespace.$_namespaceSeparator);
var_dump(substr($className, 0, strlen($_namespace.$_namespaceSeparator)));
if (null === $_namespace || $_namespace.$_namespaceSeparator === substr($className, 0, strlen($_namespace.$_namespaceSeparator))) {
    $fileName = '';
    $namespace = '';
    if (false !== ($lastNsPos = strripos($className, $_namespaceSeparator))) {
        $namespace = substr($className, strlen($prefixe), $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace($_namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . $_fileExtension;

    var_dump($fileName);
    var_dump($className);
    var_dump($_includePath . DIRECTORY_SEPARATOR . $fileName);
    return ($_includePath !== null ? $_includePath . DIRECTORY_SEPARATOR : '') . $fileName;
}
    var_dump($fileName);
    var_dump($className);
