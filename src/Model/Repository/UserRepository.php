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
            $objetFormatTableau['mdp'],
            $objetFormatTableau['nom'],
            $objetFormatTableau['prenom']);
    }

    public function sauvegarder(User $user):void
    {
        $sql = 'INSERT INTO souvignetn.Users("idUser",MDP,PRENOMUSER,NOMUSER) VALUES(:idUser, :mdp, :prenom, :nom)';
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(array(
            'idUser' => $user->getId(),
            'mdp' => $user->getMdp(),
            'prenom' => $user->getPrenom(),
            'nom' => $user->getNom()
        ));
    }

    /*
    public function checkMdp(string $mdp):string
    {
        $erreursMdp = '';

        $mdpLong = false;
        if(strlen($mdp) >= 8)
        {
            $mdpLong = true;
        }
        else
        {
            $erreursMdp .= 'Longueur de 8 caractères minimum requise <br>';
        }


        $alphabet = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $contientMaj = false;
        foreach ($alphabet as &$lettre)
        {
            if(strpos($mdp, $lettre) !== false)
            {
                $contientMaj = true;
            }
        }

        if(!$contientMaj) {
            $erreursMdp .= 'Au moins une majuscule requise <br>';
        }

        $chiffres = [0,1,2,3,4,5,6,7,8,9];
        $contientChiffre = false;
        foreach ($chiffres as &$chiffre)
        {
            if(strpos($mdp, $chiffre) !== false)
            {
                $contientChiffre = true;
            }
        }

        if(!$contientChiffre)
        {
            $erreursMdp .= 'Au moins un chiffre requis <br>';
        }

        $specialChars = ['&','"','#','~','\'','{','(','[','-','|','è','`','_','^','à','@',')',']','=','}','+','°','^','$','*','¨','$','£','€','µ','%','ù','!','§','/',';','.',',','?'];

        $contientSpecialChar = false;
        foreach ($specialChars as &$speChar)
        {
            if(strpos($mdp, $speChar) !== false)
            {
                $contientChiffre = true;
            }
        }

        if(!$contientSpecialChar)
        {
            $erreursMdp .= 'Au moins un caractère special requis <br>';
        }


        if($mdpLong && $contientMaj && $contientChiffre && $contientSpecialChar)
        {
            return 'true';
        }
        else
        {
            return $erreursMdp;
        }

    }
    */

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

        //return var_dump($pdoStatement->fetch());


    }




}