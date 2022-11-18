<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\User;

class UserRepository extends AbstractRepository
{
    function getNomTable(): string
    {
        return 'SOUVIGNETN.USERS';
    }

    protected function getNomClePrimaire(): string
    {
        return 'idUser';
    }

    protected function getNomsColonnes(): array
    {
        return [
            "",
            "",
            ""
        ];

    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new User(
            $objetFormatTableau['idUser'],
            $objetFormatTableau['mdp']);
    }

    public function sauvegarder(User $user):void
    {
        $sql = 'INSERT INTO souvignetn.Users("idUser",MDP) VALUES(:idUser, :mdp)';
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(array(
            'idUser' => $user->getId(),
            'mdp' => $user->getMdp()
        ));
    }

    public function check(string $mdp, string $cmdp):bool
    {
        if($mdp != $cmdp)
        {
            return false;
        }
        return true;
    }




}