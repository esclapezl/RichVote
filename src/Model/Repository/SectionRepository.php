<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Section;
use App\Model\Repository\DatabaseConnection;

class SectionRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return "SOUVIGNETN.SECTIONS";
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
        $sql = "SELECT * FROM SOUVIGNETN.SECTIONS WHERE idQuestionConstitue = :id";
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(array(
            'id' => $idQuestion
        ));

        $sections = array();
        foreach ($pdoStatement as $section){
            $sections[] = $this->construire(array(
                'idSection' => $section['IDSECTION'],
                'idQuestion' => $section['IDQUESTIONCONSTITUE'],
                'intitule' => $section['INTITULESECTION'],
                'description' => $section['DESCRIPTIONSECTION']
            ));
        }
        return $sections;
    }

    public function sauvegarder(Section $section){
        $sql = "INSERT INTO Sections(idQuestionConstitue, intituleSection, descriptionSection) VALUES(:id, :intitule, :description)";
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $values = array(
            'id' => $section->getIdQuestion(),
            'intitule' => $section->getIntitule(),
            'description' => $section->getDescription()
        );

        $pdoStatement->execute($values);
    }

    public function update(Section $section){
        $sql = "update SOUVIGNETN.SECTIONS set descriptionSection = :description, intituleSection = :intitule where idSection = :id";

        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(array(
            'description' => $section->getDescription(),
            'intitule' => $section->getIntitule(),
            'id' => $section->getIdSection()
        ));
    }

    public function selectSection(string $idSection){
        $sql = "SELECT * FROM SOUVIGNETN.SECTIONS WHERE idSection = :id";
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(array(
            'id' => $idSection
        ));

        $section = $pdoStatement->fetch();
        return $this->construire(array(
            'idSection' => $section['IDSECTION'],
            'idQuestion' => $section['IDQUESTIONCONSTITUE'],
            'intitule' => $section['INTITULESECTION'],
            'description' => $section['DESCRIPTIONSECTION']
        ));
    }

}