<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Proposition;
use App\Model\DataObject\Question;
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
            '"idUser"',
            "MDP",
            "PRENOMUSER",
            "NOMUSER",
            '"role"'
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

    public function setMdpHache(string $mdpClair): string
    {
        return hash('sha256', $mdpClair);
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

        //return var_dump($pdoStatement->fetch());


    }

    public function getRoleQuestion(string $user, string $question) : string{
        $sql = 'SELECT getRoleQuestion(:idUser, :idQuestion) FROM DUAL';
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute(
                ['idUser' => $user,
                'idQuestion' => $question]
        );

        $result = $pdoStatement->fetch()[0];
        return $result;
    }

    public function voter(User $user, Proposition $proposition, int $score){
        $sql = "CALL voter(:idVotant, :idProposition, :scoreVote)";
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $parametres = [
            'scoreVote' => $score,
            'idVotant' => $user->getId(),
            'idProposition' => $proposition->getId()
        ];

        $pdoStatement->execute($parametres);
    }

}