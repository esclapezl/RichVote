<?php

namespace App\Model\Repository;

use App\Model\DataObject\Demande;
use App\Model\DataObject\Proposition;
use App\Model\DataObject\Question;

class DemandeRepository
{
    public static function getDemandeVoteQuestion(Question $question):array{
        $sql = "SELECT idUser FROM DemandeVotant WHERE idQuestion=:idQuestion";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $idQuestion = $question->getId();
        $pdoStatement->execute(['idQuestion' => $idQuestion]);

        $idOrganisateur = $question->getIdOrganisateur();

        $result = [];
        foreach ($pdoStatement as $id) {
            $result[] = new Demande('vote', $idQuestion, $id['IDUSER'], $idOrganisateur, null);
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
            $result[] = new Demande('auteur', $proposition->getIdQuestion(), $id['IDUSER'], $proposition->getIdResponsable(), $proposition->getId());
        }
        return $result;
    }

    public static function sauvegarder(Demande $demande): bool{
        $sql = "call sauvegarderDemande(:typeDemande, :idUser, :idQuestion, :idProposition)";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $params = [
            'typeDemande' => $demande->getRole(),
            'idUser' => $demande->getIdUser(),
            'idQuestion' => $demande->getIdQuestion(),
            'idProposition' => $demande->getIdProposition()
        ];

        return $pdoStatement->execute($params);
    }

    public static function getDemandeUtilisateur(string $idUser): array{
        $sql = 'select * from view_demandes where idUser=:idUser';
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute(['idUser'=>$idUser]);

        $result = [];
        foreach ($pdoStatement as $tab){
            $demande = new Demande($tab['ROLE'], $tab['IDQUESTION'], $tab['IDUSER'], $tab['IDPROPOSITION']);
            $result[] = $demande;
        }
        return $result;
    }
}