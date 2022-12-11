<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Groupe;

class GroupeRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return 'GROUPEUSERS';
    }

    protected function getNomClePrimaire(): string
    {
        return 'nomGroupe';
    }

    protected function getIntitule(): string
    {
        return '';
    }

    protected function getNomsColonnes(): array
    {
        return [
            'nomGroupe',
            'idUserResponsable'
        ];
    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        $groupe = new Groupe(
            $objetFormatTableau['NOMGROUPE'],
            $objetFormatTableau['IDUSERRESPONSABLE'],
            $this->getIdMembres($objetFormatTableau['NOMGROUPE']));

        return $groupe;
    }

    public function getIdMembres(string $nomGroupe) : array{
        $sql = 'SELECT idUser FROM AppartientGroupe WHERE nomGroupe=:nomGroupe';
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $pdoStatement->execute(['nomGroupe'=>$nomGroupe]);

        $result = [];
        foreach ($pdoStatement as $idUser){
            $result[] = $idUser['IDUSER'];
        }
        return $result;
    }

    public function sauvegarder(Groupe $groupe){
        $sql = "INSERT INTO GROUPEUSERS(nomGroupe, idUserResponsable) VALUES(:nomGroupe, :idUser)";

        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $pdoStatement->execute(['nomGroupe' => $groupe->getId(),
            'idUser' => $groupe->getIdResponsable()]);

        // insertions des membres
        $this->insertMembres($groupe->getId(), $groupe->getIdMembres());
    }

    private function insertMembres(string $nomGroupe, array $idMembres){
        $sql = "INSERT INTO APPARTIENTGROUPE(idUser, nomGroupe) VALUES(:idUser, :nomGroupe) WHERE NOT EXISTS(SELECT idUser FROM APPARTIENTGROUPE WHERE idUser=:idUser AND nomGroupe=:nomGroupe)";

        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        foreach ($idMembres as $idMembre){
            $pdoStatement->execute(['idUser'=>$idMembre, 'nomGroupe'=>$nomGroupe]);
        }
    }

}