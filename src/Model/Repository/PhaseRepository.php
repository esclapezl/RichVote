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
        return ['IDPHASE',
                'DATEDEBUT',
                'DATEFIN',
                'TYPEPHASE'];
    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Phase($objetFormatTableau['IDPHASE'],
                        $objetFormatTableau['TYPEPHASE'],
                        date_create_from_format('d/m/Y',$objetFormatTableau['DATEDEBUT']),
                        date_create_from_format('d/m/Y',$objetFormatTableau['DATEFIN']));
    }

    public function getCurrentPhase(string $idQuestion) : AbstractDataObject{
        $sql = "SELECT * FROM PHASES
	            WHERE IDQUESTIONCONCERNE = $idQuestion
	            AND dateDebut<=SYSDATE AND dateFin>SYSDATE";
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $statement = $pdo->query($sql);
        $result = $statement->fetch();
        if(!isset($result['TYPEPHASE'])){
            return Phase::emptyPhase();
        }
        else{
            return $this->construire($result);
        }
    }

    public function endCurrentPhase(Phase $phase){ // à tester
        $sql = "CALL end_current_phase(" . $phase->getId() . ")";
        DatabaseConnection::getInstance()::getPdo()->query($sql);
    }
}