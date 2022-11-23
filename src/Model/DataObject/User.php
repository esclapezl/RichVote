<?php

namespace App\Model\DataObject;

class User extends AbstractDataObject
{
    private string $id;
    private string $mdp;
    private string $prenom;
    private string $nom;
    private string $role;

    /**
     * @param string $id
     * @param string $mdp
     * @param string $prenom
     * @param string $nom
     */
    public function __construct(string $id, string $mdp, string $prenom, string $nom, string $role)
    {
        $this->id = $id;
        $this->mdp = $mdp;
        $this->prenom =$prenom;
        $this->nom = $nom;
        $this->role = $role;

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
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    public function getRole(): string
    {
        return $this->role;
    }




    public function formatTableau(): array
    {
        return array(
            "idTag" => $this->getId(),
            "mdpTag" => $this->getMdp(),
            "prenomTag" => $this->getPrenom(),
            "nomTag" => $this->getNom(),
        );
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }


}