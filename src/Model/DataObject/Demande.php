<?php

namespace App\Model\DataObject;

class Demande
{
    private string $type;
    private string $idQuestion;
    private string $idDemandeur;
    private string $idValideur;
    private ?string $idProposition;

    public function __construct(
        string $type,
        string $idQuestion,
        string $idDemandeur,
        string $idValideur,
        ?string $idProposition=null
    )
{
    $this->type = $type;
    $this->idQuestion=$idQuestion;
    $this->idDemandeur=$idDemandeur;
    $this->idValideur=$idValideur;
    $this->idProposition=$idProposition;
}

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getIdQuestion(): string
    {
        return $this->idQuestion;
    }

    /**
     * @return string
     */
    public function getIdDemandeur(): string
    {
        return $this->idDemandeur;
    }

    /**
     * @return string
     */
    public function getIdValideur(): string
    {
        return $this->idValideur;
    }

    /**
     * @return string|null
     */
    public function getIdProposition(): ?string
    {
        return $this->idProposition;
    }

}