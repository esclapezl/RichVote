<?php
namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use mysql_xdevapi\DatabaseObject;

abstract class AbstractRepository{
    protected abstract function getNomTable() : string;

    protected abstract function construire(array $objetFormatTableau) : AbstractDataObject;

    public function selectAll(): array{
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $nomTable = $this->getNomTable();

        $pdoStatement = $pdo->query("SELECT * FROM $nomTable");

        $tabRepo = array();
        foreach($pdoStatement as $objetFormatTab){
            $tabRepo[] = $this->construire($objetFormatTab);
        }

        return $tabRepo;
    }
}