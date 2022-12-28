<?php

namespace App\Model\DataObject;

class Demande
{
    private string $role;
    private string $idQuestion;
    private string $idUser;
    private ?string $idProposition;

    public function __construct(
        string $type,
        string $idQuestion,
        string $idDemandeur,
        ?string $idProposition=null
    )
{
    $this->role = $type;
    $this->idQuestion=$idQuestion;
    $this->idUser=$idDemandeur;
    $this->idProposition=$idProposition;
}

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
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
    public function getIdUser(): string
    {
        return $this->idUser;
    }

    /**
     * @return string|null
     */
    public function getIdProposition(): ?string
    {
        return $this->idProposition;
    }

}