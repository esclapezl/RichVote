<?php
namespace App\Controller;
use App\Model\Repository\DatabaseConnection as DataBaseConnection;
use mysql_xdevapi\DatabaseObject;

class Controller{
    public static function test()
    {
        echo "ah";
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
}
?>
