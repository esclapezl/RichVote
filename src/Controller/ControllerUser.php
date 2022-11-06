<?php

namespace App\Controller;

class ControllerUser
{

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }

    public static function accueil()
    {
        self::afficheVue('view.php',[
            "pagetitle" => "Accueil",
            "cheminVueBody" => 'accueil.php'
        ]);
    }

    public static function error()
    {
        self::afficheVue('view.php',[
            "pagetitle" => "Erreur",
            "cheminVueBody" => 'error.php'
        ]);
    }

    public static function inscription()
    {

        self::afficheVue('view.php',[
            "pagetitle" => "Inscription",
            "cheminVueBody" => 'user/inscription.php'
        ]);
    }
}