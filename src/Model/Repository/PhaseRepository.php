<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Phase;

class PhaseRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return 'Phases';
    }

    protected function getNomClePrimaire(): string
    {
        return 'idPhase';
    }

    protected function getNomsColonnes(): array
    {
        return ['idPhase',
                'dateDebut',
                'dateFin',
                'typePhase'];
    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Phase($objetFormatTableau['idPhase'],
                        $objetFormatTableau['dateDebut'],
                        $objetFormatTableau['dateFin'],
                        $objetFormatTableau['typePhase']);
    }

    public function getCurrentTypePhase(string $idQuestion){
        $sql = 'SELECT * FROM PHASES WHERE idQuestionConcerne=:idQuestion AND dateDebut<=SYSDATE AND dateFin>SYSDATE';
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $statement = $pdo->prepare($sql);
        $statement->execute(['idQuestion' => $idQuestion]);
        $type = $statement->fetch()['TYPEPHASE'];
        if(!$type){
            return 'consultation';
        }
        else{
            return $type;
        }
    }

    public function selectUnvaulted(string $idQuestion){

    }
}