<?php

namespace App\Model\DataObject;
use App\Model\Repository\DatabaseConnection;

class Section extends AbstractDataObject
{
    private string $intitule;
    private string $description;
    private string $idQuestion;
    private ?string $idSection;

    public function getId(): ?string
    {
        return $this->idSection;
    }

    /**
     * @return string
     */
    public function getIdSection(): string
    {
        return $this->idSection;
    }

    /**
     * @return string
     */
    public function getIntitule(): string
    {
        return $this->intitule;
    }

    /**
     * @param string $intitule
     */
    public function setIntitule(string $intitule): void
    {
        $this->intitule = $intitule;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getIdQuestion(): string
    {
        return $this->idQuestion;
    }

    public function __construct(
        ?string $idSection,
        string $idQuestion,
        string $intitule,
        string $description
    )
    {
        $this->idSection = $idSection;
        $this->idQuestion = $idQuestion;
        $this->intitule = $intitule;
        $this->description = $description;
    }

    public function formatTableau(): array
    {
        return array(
            "intituleSectionTag" => $this->getIntitule(),
            "descriptionSectionTag" => $this->getDescription(),
            "idQuestionTag" => $this->getIdQuestion(),
            "idSectionTag" => $this->getIdSection()
        );
    }

    public function getNbLikes():string
    {
        $sql = "SELECT COUNT(*) FROM souvignetn.likesSections WHERE IDSECTION = '".$this->getIdSection()."'";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);
        $pdoStatement->execute();
        $p = $pdoStatement->fetch()[0];
        return $p;
    }
}