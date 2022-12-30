<?php

namespace App\Model\Repository;

use App\Model\DataObject\Demande;
use App\Model\DataObject\Proposition;
use App\Model\DataObject\Question;
use App\Model\DataObject\User;

class DemandeRepository
{
    public static function getDemandeVoteQuestion(Question $question):array{
        $sql = "SELECT idUser FROM DemandeVotant WHERE idQuestion=:idQuestion";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $idQuestion = $question->getId();
        $pdoStatement->execute(['idQuestion' => $idQuestion]);

        $result = [];
        foreach ($pdoStatement as $id) {
            $user = (new UserRepository())->select($id['IDUSER']);
            $result[] = new Demande('votant', $question, $user, null);
        }
        return $result;
    }

    public static function getDemandeAuteurProposition(Proposition $proposition) : array{
        $sql = "SELECT idUser FROM DEMANDEAUTEUR WHERE idProposition=:idProposition";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $idproposition = $proposition->getId();
        $pdoStatement->execute(['idProposition' => $idproposition]);

        $result = [];
        foreach ($pdoStatement as $id) {
            $user = (new UserRepository())->select($id['IDUSER']);
            $question = (new QuestionRepository())->select($proposition->getIdQuestion());
            $result[] = new Demande('auteur', $question, $user, $proposition);
        }
        return $result;
    }

    public static function sauvegarder(Demande $demande): bool{
        $sql = "call sauvegarderDemande(:typeDemande, :idUser, :idQuestion, :idProposition)";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $idProposition = $demande->getProposition()!=null?$demande->getProposition()->getId():null;
        $params = [
            'typeDemande' => $demande->getRole(),
            'idUser' => $demande->getUser()->getId(),
            'idQuestion' => $demande->getQuestion()->getId(),
            'idProposition' => $idProposition
        ];

        return $pdoStatement->execute($params);
    }

    public static function getDemandeUtilisateur(User $user): array{
        $sql = 'select * from view_demandes where idUser=:idUser';
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute(['idUser'=>$user->getId()]);

        $result = [];
        foreach ($pdoStatement as $tab){
            $question = (new QuestionRepository())->select($tab['IDQUESTION']);
            $proposition = isset($tab['IDPROPOSITION']) ? (new PropositionRepository())->select($tab['IDPROPOSITION']) : null;
            $demande = new Demande($tab['ROLE'], $question, $user, $proposition);
            $result[] = $demande;
        }
        return $result;
    }

    public static function delete(Demande $demande){
        $sql = 'delete from view_demandes where idUser=:idUser AND idQuestion=:idQuestion AND idProposition=:idProposition';
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $proposition = $demande->getProposition();
        $param=[
            'idUser' => $demande->getUser()->getId(),
            'idQuestion' => $demande->getQuestion()->getId(),
            'idProposition' => $proposition!=null?$proposition->getId():null
        ];

        $pdoStatement->execute($param);
    }
}