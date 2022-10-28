<?php
namespace App\Controller;
use App\Model\Repository\DatabaseConnection as DataBaseConnection;
use mysql_xdevapi\DatabaseObject;

class Controller{
    public static function accueil()
    {

        self::afficheVue('view.php',[
            "pagetitle" => "Accueil",
            "cheminVueBody" => 'vote/accueil.php'
        ]);
    }

    public static function createQuestion()
    {

        self::afficheVue('view.php',[
            "pagetitle" => "créer une question",
            "cheminVueBody" => 'vote/createQuestion.php'
        ]);
    }


    public static function inscription()
    {

        self::afficheVue('view.php',[
            "pagetitle" => "Inscription",
            "cheminVueBody" => 'vote/inscription.php'
        ]);
    }

    public static function readAll(){
        $pdo = DataBaseConnection::getInstance()::getPdo();

        $sql = "SELECT * FROM testRepSec";

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute();

        $idPrevQ = 0;
        foreach ($pdoStatement as $currentQuestion){
            if($idPrevQ != $currentQuestion['idQuestion']){
                $idPrevQ = $currentQuestion['idQuestion'];
                echo "autre question: $currentQuestion[intituleQuestion]<br>";
            }
            echo "Section: $currentQuestion[idSection] intitule :$currentQuestion[intituleSection] $currentQuestion[descriptionSection] <br>";
        }
    }

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }
}
?>
