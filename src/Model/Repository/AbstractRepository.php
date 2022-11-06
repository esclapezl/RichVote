<?php
namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use mysql_xdevapi\DatabaseObject;

abstract class AbstractRepository{
    protected abstract function getNomTable() : string;

    protected abstract function getNomId() : string;

    protected abstract function construire(array $objetFormatTableau) : AbstractDataObject;

    public function selectAll(): array{
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $nomTable = $this->getNomTable();

        $pdoStatement = $pdo->query('SELECT * FROM '.$nomTable);

        $tabRepo = array();
        foreach($pdoStatement as $objetFormatTab){
            $tabRepo[] = $this->construire($objetFormatTab);
        }

        return $tabRepo;
    }

    public function select(string $id) : AbstractDataObject{
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $nomTable = $this->getNomTable();
        $nomId = $this->getNomId();

        $sql = 'SELECT * FROM '.$nomTable.' WHERE '.$nomId.' = '.$id;

        $pdostatement = $pdo->query($sql);

        $objectTab = $pdostatement->fetch();

        $object = $this->construire($objectTab);

        return $object;
    }
}