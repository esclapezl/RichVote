<?php

namespace App\Model\DataObject;

class Section extends AbstractDataObject
{
    private string $intitule;
    private string $description;
    private int $idQuestion;
    private int $idSection;

    /**
     * @return int
     */
    public function getIdSection(): int
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
     * @return int
     */
    public function getIdQuestion(): int
    {
        return $this->idQuestion;
    }

    public function __construct(
        int $idSection,
        int $idQuestion,
        string $intitule,
        string $description
    )
    {
        $this->idSection = $idSection;
        $this->idQuestion = $idQuestion;
        $this->intitule = $intitule;
        $this->description = $description;
    }
}