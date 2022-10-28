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
        $pdo = DataBaseConnection::getInstance()::getPdo();

        $sql = "SELECT * FROM testRepSec";

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute();
        $var = $pdoStatement->fetch();

        $idPrevQ = 0;
        foreach ($var as $currentQuestion){
            if($idPrevQ != $currentQuestion[idQues])
            echo "id Question: $currentQuestion[idQuestion]"
        }

        echo "je test, l'id de la question: $var[idQuestion], sa section: $var[idSection]";
    }

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }
}
?>
