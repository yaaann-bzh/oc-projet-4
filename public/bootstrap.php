<?php
const DEFAULT_app = 'Frontend';
 
// Si l'application n'est pas valide, on va charger l'application par défaut qui se chargera de générer une erreur 404
if (!isset($_GET['app']) || !file_exists(__DIR__.'/../app/')) $_GET['app'] = DEFAULT_app;
 
// On commence par inclure la classe nous permettant d'enregistrer nos autoload
require __DIR__.'/../lib/framework/SplClassLoader.php';
 
// On va ensuite enregistrer les autoloads correspondant à chaque vendor (framework, app, Model, etc.)
$frameworkLoader = new SplClassLoader('framework', __DIR__.'/../lib');
$frameworkLoader->register();
 
$appLoader = new SplClassLoader('forteroche\app', __DIR__.'/..');
$appLoader->register();
 
$modelLoader = new SplClassLoader('forteroche\vendor\model', __DIR__.'/..');
$modelLoader->register();
 
$entityLoader = new SplClassLoader('forteroche\vendor\entity', __DIR__.'/..');
$entityLoader->register();

$appClass = 'forteroche\\app\\'.$_GET['app'];

$app = new $appClass;
$app->run();

