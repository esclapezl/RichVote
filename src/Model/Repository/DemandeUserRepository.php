<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Demande;

class DemandeUserRepository extends DemandeRepository
{
    protected function getNomTable(): string
    {
        return 'view_demandes';
    }

    protected function getNomClePrimaire(): string
    {
        return 'idUser';
    }

    public function constructDemandeur(string $idDemandeur): AbstractDataObject
    {
        return (new UserRepository())->select($idDemandeur);
    }

    public function sauvegarder(Demande $demande): bool{
        $sql = "call sauvegarderDemande(:typeDemande, :idUser, :idQuestion, :idProposition)";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $idProposition = $demande->getProposition()!=null?$demande->getProposition()->getId():null;
        $params = [
            'typeDemande' => $demande->getRole(),
            'idUser' => $demande->getDemandeur()->getId(),
            'idQuestion' => $demande->getQuestion()->getId(),
            'idProposition' => $idProposition
        ];

        return $pdoStatement->execute($params);
    }

    public function aDejaDemande($idUser,$idQuestion):bool
    {
        $sql = "SELECT IDUSER FROM  SOUVIGNETN.VIEW_DEMANDES WHERE IDUSER = :IDUSER AND IDQUESTION = ".$idQuestion;
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $params = [
            'IDUSER' => $idUser
        ];

        $pdoStatement->execute($params);

        if(!$pdoStatement->fetch())
        {
            return false;
        }
        else
            return true;
    }
}