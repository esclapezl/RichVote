<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Demande;
use App\Model\DataObject\Proposition;
use App\Model\DataObject\Question;


abstract class DemandeRepository{
    protected abstract function getNomTable():string;
    protected abstract function getNomClePrimaire():string;
    public final function selectAllDemandeVoteQuestion(Question $question){
        $nomTable = $this->getNomTable();
        $sql = "SELECT * FROM $nomTable WHERE idQuestion=:idQuestion";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $idQuestion = $question->getId();
        $clePrimaire = $this->getNomClePrimaire();

        $param = [
            'idQuestion' => $idQuestion
        ];

        $pdoStatement->execute($param);

        $result = [];
        foreach ($pdoStatement as $id) {
            $demandeur = $this->constructDemandeur($id[strtoupper($clePrimaire)]);
            $result[] = new Demande('votant', $question, $demandeur, null);
        }
        return $result;
    }

    public final function selectAllDemandeAuteurProposition(Proposition $proposition) : array{
        $sql = "SELECT * FROM :nomTable WHERE idProposition=:idProposition";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $idProposition = $proposition->getId();
        $clePrimaire = $this->getNomClePrimaire();
        $nomTable = $this->getNomTable();

        $param = [
            'nomTable' => $nomTable,
            'idProposition' => $idProposition
        ];

        $pdoStatement->execute($param);

        $result = [];
        foreach ($pdoStatement as $id) {
            $demandeur = $this->constructDemandeur($id[strtoupper($clePrimaire)]);
            $question = (new QuestionRepository())->select($proposition->getIdQuestion());
            $result[] = new Demande('auteur', $question, $demandeur, $proposition);
        }
        return $result;
    }

    public abstract function sauvegarder(Demande $demande):bool;

    public final function selectAllDemandeDemandeur(string $idDemandeur){
        $nomTable = $this->getNomTable();
        $sql = "select * from $nomTable where :clePrimaire=:idDemandeur";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $param = [
            'clePrimaire' => $this->getNomClePrimaire(),
            'idDemandeur' => $idDemandeur
        ];

        $pdoStatement->execute($param);

        $demandeur = $this->constructDemandeur($idDemandeur);
        $result = [];
        foreach ($pdoStatement as $tab){
            $question = (new QuestionRepository())->select($tab['IDQUESTION']);
            $proposition = isset($tab['IDPROPOSITION']) ? (new PropositionRepository())->select($tab['IDPROPOSITION']) : null;
            $demande = new Demande($tab['ROLE'], $question, $demandeur, $proposition);
            $result[] = $demande;
        }
        return $result;
    }

    public final function delete(Demande $demande){
        $nomTable = $this->getNomTable();
        $sql = "delete from $nomTable where :clePrimaire=:idDemandeur AND idQuestion=:idQuestion AND idProposition=:idProposition";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $proposition = $demande->getProposition();
        $param=[
            'clePrimaire' => $this->getNomClePrimaire(),
            'idDemandeur' => $demande->getDemandeur()->getId(),
            'idQuestion' => $demande->getQuestion()->getId(),
            'idProposition' => $proposition!=null?$proposition->getId():null
        ];

        $pdoStatement->execute($param);
    }

    public abstract function constructDemandeur(string $idDemandeur):AbstractDataObject;
}