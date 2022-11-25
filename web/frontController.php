<?php
namespace App\web;
use App\Controller\Controller as Controller;
use App\Controller\ControllerAdmin;
use App\Controller\ControllerUser;

require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

// instantiate the loader
$loader = new \App\Lib\Psr4AutoloaderClass();
// register the base directories for the namespace prefix
$loader->addNamespace('App', __DIR__ . '/../src');
// register the autoloader
$loader->register();


$os = array("Proposition", "Question", "Section", "User");

if(isset($_GET['controller'])){
    $controller = ucfirst($_GET['controller']);
}
else{
    $controller="User";
    $action="accueil";
}

$controllerClassName = "App\Controller\Controller". $controller;
$class_methods = get_class_methods($controllerClassName);
if(isset($_GET['action']) && in_array($controller, $os)) {
    foreach ($class_methods as $key) {
        if ($key == $_GET['action']) {
            $action=$_GET['action'];
        }
    }
}
if((isset($action))){
    $controllerClassName::$action();
}
else{
    ControllerUser::error();
}

/* VERSION OPTI AVEC GENERIC CONTROLLER (A TRANSFORMER POUR SAE) :


//GENERAL
    if(\App\Covoiturage\Lib\PreferenceControleur::existe()){

        $controller=ucfirst(\App\Covoiturage\Lib\PreferenceControleur::lire());
    }
    else {
        $controller="Voiture";
    }
    $controllerClassName = "App\Covoiturage\Controller\Controller" . $controller;
    $action="readAll";

    //SPECIFICATION
    if(isset($_GET['controller'])){
        $controller = ucfirst($_GET['controller']);
        $controllerClassName = "App\Covoiturage\Controller\Controller" . $controller;
    }
    elseif(isset($_GET['action'])){
        $controllerClassName ="App\Covoiturage\Controller\GenericController";
    }

    $check=false;
    $os = array("Utilisateur", "Voiture", "Generic", "Trajet");
    $class_methods = get_class_methods($controllerClassName);
    if(isset($_GET['action']) && in_array($controller, $os)) {
        foreach ($class_methods as $key) {
            if ($key == $_GET['action']) {
                $action=$_GET['action'];
                $check=true;
            }
        }
    }
    if($check || isset($_GET['controller']) || !isset($_GET['action'])){
        $controllerClassName::$action();
    }
    else{
        ControllerVoiture::error();
        //GenericController::error();

    }
*/




/* ANCIENNE VERSION
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
    ControllerUser::error();
}*/


?>
