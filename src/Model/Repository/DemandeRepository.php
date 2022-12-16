<?php

namespace App\Model\Repository;

use App\Model\DataObject\Demande;
use App\Model\DataObject\Proposition;
use App\Model\DataObject\Question;

class DemandeRepository
{
    public static function getDemandeVoteQuestion(Question $question):array{
        $sql = "SELECT idUser FROM VOTANTS WHERE idQuestion=:idQuestion AND demande='V'";
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

    public static function sauvegarder(Demande $demande){
        $sql = "call sauvegarderDemande(:typeDemande, :idUser, :idQuestion, :idProposition)";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $params = [
            'typeDemande' => $demande->getType(),
            'idUser' => $demande->getIdDemandeur(),
            'idQuestion' => $demande->getIdQuestion(),
            'idProposition' => $demande->getIdProposition()
        ];

        $pdoStatement->execute($params);
    }
}