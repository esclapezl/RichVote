<?php

namespace App\Model\Repository;

use App\Lib\MessageFlash;
use App\Model\DataObject\Proposition;
use App\Model\DataObject\User;

class VoteRepository
{
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