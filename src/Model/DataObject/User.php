<?php

namespace App\Model\DataObject;

class User extends AbstractDataObject
{
    private string $id;
    private string $mdp;
    private string $prenom;
    private string $nom;

    /**
     * @param string $id
     * @param string $mdp
     * @param string $prenom
     * @param string $nom
     */
    public function __construct(string $id, string $mdp, string $prenom, string $nom)
    {
        $this->id = $id;
        $this->mdp = $mdp;
        $this->prenom = "testPrenom" /*$prenom*/;
        $this->nom = "testNom"/*$nom*/;

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




    public function formatTableau(): array
    {
        return array(
            "idTag" => $this->getId(),
            "mdpTag" => $this->getMdp(),
            "prenomTag" => $this->getPrenom(),
            "nomTag" => $this->getNom()
        );
    }
}