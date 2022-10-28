<?php
namespace App\Controller;
use App\Model\Repository\DatabaseConnection as DataBaseConnection;
use mysql_xdevapi\DatabaseObject;

class Controller{
    public static function accueil()
    {
        echo "Accueil";
        self::afficheVue('view.php',[
            "pagetitle" => "Accueil",
            "cheminVueBody" => 'vote/accueil.php'
        ]);
    }

    public static function readAll(){
        echo "read All";
        DataBaseConnection::getInstance()::getPdo();
    }

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }
}
?>
