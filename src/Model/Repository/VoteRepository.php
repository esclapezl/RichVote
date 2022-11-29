<?php

namespace App\Model\Repository;

use App\Model\DataObject\Proposition;
use App\Model\DataObject\User;

class VoteRepository
{
    public static function peutVoter(string $idUser, string $idQuestion) : bool{
        $sql = 'SELECT peutVoter(:idUser, :idQuestion) FROM DUAL';
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute([
            'idQuestion' => $idQuestion,
            'idUser' => $idUser
        ]);

        $result = $pdoStatement->fetch()[0];

        return $result == 1;
    }

    public static function voter(string $idProposition, string $idUser, int $score){
        $sql = "CALL voter(:idUser, :idProposition, :score)";
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $param = ['idUser' => $idUser,
            'idProposition' => $idProposition,
            'score' => $score];
        $pdoStatement->execute($param);
    }
}