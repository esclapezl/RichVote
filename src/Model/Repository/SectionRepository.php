<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Proposition;
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
            $objetFormatTableau['IDSECTION'],
            $objetFormatTableau['IDQUESTION'],
            $objetFormatTableau['INTITULESECTION'],
            $objetFormatTableau['DESCRIPTIONSECTION']
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
            $sections[] = $this->construire($section);
        }
        return $sections;
    }

    public function getSectionsProposition(string $idProposition): array{
        $sql = "SELECT s.idSection, s.idQuestion, intituleSection, descriptionSection, COUNT(s.idSection) as nbLike, texte
                FROM SECTIONS s
                JOIN PROPOSITIONS p ON p.idQuestion=s.idQuestion
                JOIN PROPOSERTEXTE pt ON pt.idProposition=p.idProposition
                WHERE p.idProposition=:idProposition
                GROUP BY (s.idSection, s.idQuestion, intituleSection, descriptionSection, texte)"; // ca compte pas le nb de like idiot
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute(['idProposition' => $idProposition]);

        $result = [];
        foreach ($pdoStatement as $infos){
            $section = $this->construire($infos);
            $result[] = ['section' => $section,
                        'nbLike' => $infos['NBLIKE'],
                        'texte' => $infos['TEXTE']];
        }
        return $result;
    }


    protected function getIntitule(): string
    {
        return "intituleSection";
    }

    public function userALike(int $idSection, string $idUser,int $idProposition):bool
    {
        $sql = "SELECT COUNT(*) FROM souvignetn.likesSections WHERE IDSECTION = ".$idSection." AND IDUSER = :IDUSER AND IDPROPOSITION = ".$idProposition;
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute(['IDUSER' => $idUser]);



        if($pdoStatement->fetch()[0] == 0)
         return false;
        else return true;
    }

    public function liker(int $idSection, string $idUser,int $idProposition):void
    {
        $sql = "INSERT INTO SOUVIGNETN.LIKESSECTIONS VALUES(".$idSection.",:IDUSER,".$idProposition.")";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute(['IDUSER' => $idUser]);
    }

    public function deliker(int $idSection, string $idUser,int $idProposition):void
    {
        $sql = "DELETE FROM SOUVIGNETN.LIKESSECTIONS WHERE IDSECTION=".$idSection." AND IDUSER = :IDUSER AND IDPROPOSITION=".$idProposition;
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute(['IDUSER' => $idUser]);
    }

    public function getNbLikes(int $idSection): string
    {
        $sql = "SELECT COUNT(*) FROM souvignetn.likesSections WHERE IDSECTION = '" . $idSection . "'";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute();
        $p = $pdoStatement->fetch()[0];
        return $p;
    }
}