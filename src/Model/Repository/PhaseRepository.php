<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Phase;

class PhaseRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return 'vue_PhasesDetail';
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
                'TYPEPHASE',
                'NBDEPLACES'];
    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Phase($objetFormatTableau['IDPHASE'],
                        $objetFormatTableau['TYPEPHASE'],
                        date_create_from_format('d/m/Y',$objetFormatTableau['DATEDEBUT']),
                        date_create_from_format('d/m/Y',$objetFormatTableau['DATEFIN']),
                        $objetFormatTableau['NBDEPLACES']);
    }

    public function getCurrentPhase(string $idQuestion) : ?AbstractDataObject{
        $sql = "SELECT * FROM vue_PhasesDetail
	            WHERE idQuestion = $idQuestion
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

    public function endPhase(String $phase) : void{
        $sql = "CALL end_phase(" . $phase . ")";
        DatabaseConnection::getInstance()::getPdo()->query($sql);
    }

    public function startPhase(String $phase) : void{
        $sql = "CALL start_phase(" . $phase . ")";
        DatabaseConnection::getInstance()::getPdo()->query($sql);
    }

    public function getPhasesIdQuestion(string $idQuestion) : array{
        $sql = "SELECT * FROM vue_PhasesDetail WHERE idQuestion = :idQuestion";
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute(['idQuestion' => $idQuestion]);

        $result = [];
        foreach ($pdoStatement as $formatTableau){
            $result[] = $this->construire($formatTableau);
        }
        return $result;
    }

    protected function getIntitule(): string
    {
        return "";
    }
}