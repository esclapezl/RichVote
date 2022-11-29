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

//    public function sauvegarder(Section $section){
//        $sql = "INSERT INTO Sections(idQuestion, intituleSection, descriptionSection) VALUES(:id, :intitule, :description)";
//        $pdo = DatabaseConnection::getInstance()::getPdo();
//
//        $pdoStatement = $pdo->prepare($sql);
//
//        $values = array(
//            'id' => $section->getIdQuestion(),
//            'intitule' => $section->getIntitule(),
//            'description' => $section->getDescription()
//        );
//
//        $pdoStatement->execute($values);
//    }

    protected function getIntitule(): string
    {
        return "intituleSection";
    }
}