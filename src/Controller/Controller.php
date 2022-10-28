<?php
namespace App\Controller;
use App\Model\Repository\DatabaseConnection as DataBaseConnection;
use mysql_xdevapi\DatabaseObject;

class Controller{
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
}
?>
