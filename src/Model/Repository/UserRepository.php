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
            '"role"',
            "ESTADMIN",
            "EMAIL"
        ];

    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new User(
            $objetFormatTableau['idUser'],
            $objetFormatTableau['MDP'],
            $objetFormatTableau['PRENOMUSER'],
            $objetFormatTableau['NOMUSER'],
            $objetFormatTableau['role'],
            $objetFormatTableau['ESTADMIN'],
            $objetFormatTableau['EMAIL']
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

    public function checkEmail(string $email):bool
    {

        $sql = "SELECT * FROM souvignetn.Users WHERE EMAIL = :email";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute(array(
            'email' => $email
        ));

        if ($pdoStatement->fetch()) {
            return false;
        }
        return true;

        //return var_dump($pdoStatement->fetch());
    }

    public function getRoleQuestion(string $user, string $question) : ?string{
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

    public function getRole(string $id) : string{
        $sql = 'SELECT "role" FROM souvignetn.Users WHERE "idUser" = :id';
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute(array(
            'id' => $id
        ));

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

    protected function getIntitule(): string
    {
        return '"idUser"';
    }

    public function sauvegarder(AbstractDataObject $object): void
    {
        $colonnes = "";
        foreach ($this->getNomsColonnes() as $colonne) {
            if(!$colonnes =="") {
                $colonnes .= ',';
            }
            $colonnes .= $colonne;
        }

        $values = "";
        foreach ($this->getNomsColonnes() as $value) {
            if(!$values =="") {
                $values .= ',';
            }
            $values .= ":".$value;
        }

        $sql = 'INSERT INTO '.$this->getNomTable().'('.$colonnes.') VALUES('.$values.')';
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute($object->formatTableau());
    }


    public function update(AbstractDataObject $object): void{
        $txtsql="";
        $nomColonnes = $this->getNomsColonnes();
        foreach ($nomColonnes as $nomColonne){
            if($nomColonne!=$nomColonnes[0]){
                $txtsql .= ', ';
            }
            if($this->getNomTable()=='SOUVIGNETN.USERS')
            {
                $txtsql .= "$nomColonne = :$nomColonne";
            }
            else
            {
                $txtsql .= "$nomColonne = :$nomColonne".'Tag';
            }

        }

        if($this->select($object->getId()) != null)
        {
            $sql = "UPDATE ".$this->getNomTable() ." SET ".$txtsql." WHERE " . $this->getNomClePrimaire() . "='".$object->getId()."'";
        }
        else
        {
            $sql = "UPDATE ".$this->getNomTable() ." SET ".$txtsql." WHERE EMAIL='".$object->getEmail()."'";
        }

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $pdoStatement->execute($object->formatTableau());
    }

    public function getDemandeVote(string $idQuestion) : array{
        $sql = "SELECT * FROM Votants v WHERE idQuestion=:idQuestion AND demande='V'";
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $pdoStatement = $pdo->prepare($sql);

        $users = [];
        if($pdoStatement->execute(['idQuestion' => $idQuestion])) {
            foreach ($pdoStatement as $votant) {
                $users[] = (new UserRepository())->select($votant['idUser']);
            }
        }
        return $users;
    }

    public function demanderAccesVote(string $idUser, string $idQuestion) : bool{
        $sql = "CALL demandeAccesVote(:idUser, :idQuestion)";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $reussite = $pdoStatement->execute([
            'idQuestion' => $idQuestion,
            'idUser' => $idUser
        ]);

        return $reussite;
    }

    public function validerUser(User $user)
    {
        $sql = "DELETE FROM SOUVIGNETN.EMAILUSERSINVALIDE WHERE IDUSER = :idUser";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $values = array(
            "idUser" => $user->getId()
        );
        $pdoStatement->execute($values);
    }

    public function mailDeValidation(User $user)
    {
        if($user->getNonce() == null)
        {
            $user->setNonce($this->genererNonce());
        }
        VerificationEmail::envoiEmailValidation($user);

    }

    function genererNonce():string {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, 9)];
        }
        return $randomString;
    }





}