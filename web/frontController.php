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

/*
if(isset($_GET['action'])) {
    $action = $_GET['action'];
}
Controller::$action(); // Appel de la méthode statique $action de Controller
*/


$controller = ucfirst($_GET['controller']);
$controllerClassName = "App\Controller\Controller" . $controller;
//echo $controller;
$check=false;
$class_methods = get_class_methods($controllerClassName);
foreach ($class_methods as $key){
    if($key==$_GET['action']){
        $check=true;
    }
}

if(isset($_GET['action']) && $check){
    $action = $_GET['action'];
    // Appel de la méthode statique $action de ControllerVoiture
    $controllerClassName::$action();
}
else{

    $controllerClassName::error();
}

?>
