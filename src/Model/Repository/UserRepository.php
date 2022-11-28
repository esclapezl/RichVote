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
        return '"idUser"';
    }

    protected function getNomsColonnes(): array
    {
        return [
            "idUser",
            "MDPHache",
            "PRENOMUSER",
            "NOMUSER",
            "role"
        ];

    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new User(
            $objetFormatTableau['idUser'],
            $objetFormatTableau['MDP'],
            $objetFormatTableau['PRENOMUSER'],
            $objetFormatTableau['NOMUSER'],
            $objetFormatTableau['role']
        );
    }

    public function sauvegarder(User $user):void
    {
        $sql = 'INSERT INTO souvignetn.Users("idUser",MDP,PRENOMUSER,NOMUSER,"role") VALUES(:idUser, :mdp, :prenom, :nom,:role)';
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(array(
            'idUser' => $user->getId(),
            'mdp' => $user->getMdpHache(),
            'prenom' => $user->getPrenom(),
            'nom' => $user->getNom(),
            'role' => 'invité'
        ));
    }



    public function checkCmdp(string $mdp, string $cmdp):bool
    {
        if($mdp != $cmdp)
        {
            return false;
        }
        return true;
    }

    public function checkId(string $idUser):bool
    {

        $sql = "SELECT \"idUser\" FROM souvignetn.Users WHERE \"idUser\" = :idUser";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute(array(
            'idUser' => $idUser
        ));

        if ($pdoStatement->fetch()) {
            return false;
        }
        return true;
    }




}