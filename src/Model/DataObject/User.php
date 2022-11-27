<?php

namespace App\Model\DataObject;

class User extends AbstractDataObject
{
    private string $id;
    private string $mdpHache;
    private string $prenom;
    private string $nom;
    private string $role;


    /**
     * @param string $id
     * @param string $mdpHache
     * @param string $prenom
     * @param string $nom
     */
    public function __construct(string $id, string $mdp, string $prenom, string $nom, string $role)
    {
        $this->id = $id;
        $this->mdpHache = $this->setMdpHache($mdp);
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
    public function getMdpHache(): string
    {
        return $this->mdpHache;
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

    /**
     * @param string $mdpHache
     */
    public function setMdpHache(string $mdpClair): string
    {
        return hash('sha256', $mdpClair);
    }




    public function formatTableau(): array
    {
        return array(
            '"idUser"' => $this->getId(),
            'MDP' => $this->getMdpHache(),
            'PRENOMUSER' => $this->getPrenom(),
            'NOMUSER' => $this->getNom(),
            '"role"' => 'invité'
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