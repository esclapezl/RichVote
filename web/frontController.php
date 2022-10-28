<?php
namespace App\web;
use App\Controller\Controller as Controller;

require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

// instantiate the loader
$loader = new \App\Lib\Psr4AutoloaderClass();
// register the base directories for the namespace prefix
$loader->addNamespace('App', __DIR__ . '/../src');
// register the autoloader
$loader->register();

$action = 'accueil';
if(isset($_GET['action'])) {
    $action = $_GET['action'];
}
Controller::$action(); // Appel de la méthode statique $action de Controller
?>
