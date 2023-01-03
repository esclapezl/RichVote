<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Demande;

class DemandeGroupeRepository extends DemandeRepository{
    protected function getNomTable(): string
    {
        return 'DEMANDEPARTICIPATIONGROUPE';
    }

    protected function getNomClePrimaire(): string
    {
        return 'NOMGROUPE';
    }

    public function constructDemandeur(string $idDemandeur): AbstractDataObject
    {
        return (new GroupeRepository())->select($idDemandeur);
    }

    public function sauvegarder(Demande $demande): bool{
        $sql = "insert into demandeParticipationGroupe(nomGroupe, idQuestion, role, idProposition) values(:nomGroupe, :idQuestion, :role, :idPropositoin)";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $idProposition = $demande->getProposition()!=null?$demande->getProposition()->getId():null;
        $params = [
            'role' => $demande->getRole(),
            'nomGroupe' => $demande->getDemandeur()->getId(),
            'idQuestion' => $demande->getQuestion()->getId(),
            'idProposition' => $idProposition
        ];

        return $pdoStatement->execute($params);
    }
}