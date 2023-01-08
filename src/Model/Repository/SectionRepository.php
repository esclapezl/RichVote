<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Section;
use App\Model\Repository\DatabaseConnection;

class SectionRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return 'SOUVIGNETN.SECTIONS';
    }

    protected function getNomClePrimaire(): string
    {
        return 'idSection';
    }

    protected function getNomsColonnes(): array
    {
        return [
            "idSection",
            "idQuestion",
            "intituleSection",
            "descriptionSection"
        ];

    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Section(
            $objetFormatTableau['idSection'],
            $objetFormatTableau['idQuestion'],
            $objetFormatTableau['intitule'],
            $objetFormatTableau['description']
        );
    }

    public function getSectionsQuestion(string $idQuestion): array{
        $sql = "SELECT * FROM SOUVIGNETN.SECTIONS WHERE idQuestion = :id";
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(array(
            'id' => $idQuestion
        ));

        $sections = array();
        foreach ($pdoStatement as $section){
            $sections[] = $this->construire(array(
                'idSection' => $section['IDSECTION'],
                'idQuestion' => $section['IDQUESTION'],
                'intitule' => $section['INTITULESECTION'],
                'description' => $section['DESCRIPTIONSECTION']
            ));
        }
        return $sections;
    }


    protected function getIntitule(): string
    {
        return "intituleSection";
    }

    public function userALike(int $idSection, string $idUser):bool
    {
        $sql = "SELECT COUNT(*) FROM souvignetn.likesSections WHERE IDSECTION = ".$idSection." AND IDUSER = :IDUSER";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute(['IDUSER' => $idUser]);



        if($pdoStatement->fetch()[0] == 0)
         return false;
        else return true;
    }

    public function liker(int $idSection, string $idUser):void
    {
        $sql = "INSERT INTO SOUVIGNETN.LIKESSECTIONS VALUES(".$idSection.",:IDUSER)";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute(['IDUSER' => $idUser]);
    }

    public function deliker(int $idSection, string $idUser):void
    {
        $sql = "DELETE FROM SOUVIGNETN.LIKESSECTIONS WHERE IDSECTION=".$idSection." AND IDUSER = :IDUSER";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute(['IDUSER' => $idUser]);
    }
}