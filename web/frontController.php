<?php
namespace App\web;
use App\Controller\Controller as Controller;
use App\Controller\ControllerAdmin;

require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

// instantiate the loader
$loader = new \App\Lib\Psr4AutoloaderClass();
// register the base directories for the namespace prefix
$loader->addNamespace('App', __DIR__ . '/../src');
// register the autoloader
$loader->register();


$controllerClassName='App\Controller\ControllerUser';
if(isset($_GET['controller'])) {
    $controller = ucfirst($_GET['controller']);
    $controllerClassName = "App\Controller\Controller" . $controller;
}

$action='accueil';
if(isset($_GET['action'])){
    $action = $_GET['action'];
}

$check=false;
$class_methods = get_class_methods($controllerClassName);
foreach ($class_methods as $key){
    if($key==$action){
        $check=true;
    }
}

if($check){
    $controllerClassName::$action();
}
else{
    $controllerClassName::error();
}

?>
