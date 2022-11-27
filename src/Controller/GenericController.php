<?php

namespace App\Controller;

class GenericController
{
    protected static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }

    public static function error()
    {
        $parametres = array("pagetitle" => "Erreur","cheminVueBody" => 'error.php');
        self::afficheVue('view.php',$parametres);
    }

    public static function redirection(string $lienBase){
        header("Location: $lienBase");
        exit();
    }


}

