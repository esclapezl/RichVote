<?php

namespace App\Model\DataObject;

class Groupe extends AbstractDataObject
{
    private string $nomGroupe;
    private ?string $idResponsable;
    private array $idMembres;

    public function formatTableau(): array
    {
        return ['nomGroupeTag' => $this->nomGroupe,
            'idResponsableTag' => $this->idResponsable,
            'idMembresTag' => $this->idMembres];
    }

    public function getId(): ?string
    {
        return $this->nomGroupe;
    }

    public function ajouterIdMembre(string $id){
        $this->idMembres[] = $id;
    }

    public function getIdMembres():array{
        return $this->idMembres;
    }

    public function __construct(
        string $nomGroupe,
        ?string $idResponsable,
        ?array $idMembres=[]
    )
    {
        $this->nomGroupe = $nomGroupe;
        $this->idResponsable = $idResponsable;
        $this->idMembres = $idMembres;
    }


}