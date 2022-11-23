<?php

namespace App\Model\DataObject;

class User extends AbstractDataObject
{
    private string $id;
    private string $mdp;
    private string $nom;
    private string $prenom;

    /**
     * @param string $id
     * @param string $mdp
     */
    public function __construct(string $id, string $mdp, string $nom, string $prenom)
    {
        $this->id = $id;
        $this->mdp = $mdp;
        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMdp(): string
    {
        return $this->mdp;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }


    public function formatTableau(): array
    {
        return array(
            "idTag" => $this->getId(),
            "mdpTag" => $this->getMdp(),
            "nomTag" => $this->getNom(),
            "prenomTag" => $this->getPrenom()
        );
    }
}