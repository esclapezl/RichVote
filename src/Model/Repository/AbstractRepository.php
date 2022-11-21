<?php
namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use mysql_xdevapi\DatabaseObject;

abstract class AbstractRepository{
    protected abstract function getNomTable() : string;

    protected abstract function getNomClePrimaire() : string;

    protected abstract function getNomsColonnes(): array;

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
        $nomId = $this->getNomClePrimaire();

        $sql = 'SELECT * FROM '.$nomTable.' WHERE '.$nomId.' = '.$id;

        $pdostatement = $pdo->query($sql);

        $objectTab = $pdostatement->fetch();

        $object = $this->construire($objectTab);

        return $object;
    }

    public function delete(string $valeurClePrimaire): void
    {
        $sql = "DELETE FROM ". $this->getNomTable() ." WHERE ". $this->getNomClePrimaire()." = :objetTag";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $values = array(
            "objetTag" => $valeurClePrimaire,
        );
        $pdoStatement->execute($values);
    }


    public function update(AbstractDataObject $object): void{
        $txtsql="";
        $nomColonnes = $this->getNomsColonnes();
        foreach ($nomColonnes as $nomColonne){
            if($nomColonne!=$nomColonnes[0]){
                $txtsql .= ', ';
            }
            $txtsql .= "$nomColonne = :$nomColonne" . 'Tag';
        }

        $id = $object->getId();
        $sql = "UPDATE ".$this->getNomTable() ." SET ".$txtsql." WHERE " . $this->getNomClePrimaire() . "=$id";

        var_dump($sql);
        var_dump($object->formatTableau());
        var_dump($txtsql);

        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
        $values = $object->formatTableau();
        $pdoStatement->execute($values);
    }

//    public function sauvegarder(AbstractDataObject $object): bool
//    {
//        $txtsqlcol="";
//        $txtsqlvalues="";
//        foreach ($this->getNomsColonnes() as $i){
//            if($i==$this->getNomsColonnes()[sizeof($this->getNomsColonnes())-1]){
//                $txtsqlvalues = $txtsqlvalues . ":" . $i . "Tag " ;
//                $txtsqlcol = $txtsqlcol . $i . " ";
//
//            }
//            else{
//                $txtsqlvalues = $txtsqlvalues . ":" . $i . "Tag" .", ";
//                $txtsqlcol = $txtsqlcol . $i .", ";
//            }
//        }
//        $sql = "INSERT INTO ". $this->getNomTable() ." (".$txtsqlcol.") VALUES (".$txtsqlvalues.")";
//
//        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);
//        $values = $object->formatTableau();
//        return $pdoStatement->execute($values);
//
//
//
//    }
}