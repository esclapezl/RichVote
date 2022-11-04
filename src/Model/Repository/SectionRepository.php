<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Section;
use App\Model\Repository\DatabaseConnection;

class SectionRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return "souvignetn.sections";
    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Section(
            $objetFormatTableau['idQuestion'],
            $objetFormatTableau['intitule'],
            $objetFormatTableau['description']
        );
    }

    public function getSectionsQuestion(string $idQuestion): array{
        $sql = "SELECT * FROM getNomTable() WHERE idQuestion = :id";
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(array('id' => $idQuestion));

        $sections = array();
        foreach ($pdoStatement as $section){
            $sections[] = $this->construire(array(
                'intitule' => $section['intituleSection'],
                'description' => $section['descriptionSection']
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

}