<?php

namespace App\Model\DataObject;

use App\Model\Repository\UserRepository;
use http\QueryString;

class User extends AbstractDataObject
{
    private string $id;
    private string $mdpHache;
    private string $prenom;
    private string $nom;
    private string $role;
    private string $email;
    private bool $estAdmin;

    private string $emailAValider;
    private string $nonce;


    /**
     * @param string $id
     * @param string $mdp
     * @param string $prenom
     * @param string $nom
     * @param string $role
     * @param bool $Admin
     */
    public function __construct(string $id, string $mdp, string $prenom, string $nom, string $role, bool $Admin, string $email)
    {
        $this->id = $id;
        $this->mdpHache = $mdp;
        $this->prenom =$prenom;
        $this->nom = $nom;
        $this->role = $role;
        $this->estAdmin = $Admin;
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->estAdmin;
    }


    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setAdmin(bool $admin)
    {
        $this->estAdmin = $admin;
        if($admin)$admin=1;
        else $admin=0;
        query('UPDATE SOUVIGNETN.USERS SET ESTADMIN='.$admin.'WHERE "idUser"='.$this->getId().';');
    }



    /**
     * @return string
     */
    public function getId(): string
    {
        return htmlspecialchars($this->id);
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
        return htmlspecialchars($this->prenom);
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return htmlspecialchars($this->nom);
    }

    public function getRole(): string
    {
        return htmlspecialchars($this->role);
    }

    /**
     * @param string $mdpHache
     */


    public function setMdp(string $mdp): void
    {
        $this->mdpHache = (new UserRepository())->setMdpHache($mdp);
    }




    public function formatTableau(): array
    {
        if($this->isAdmin()) {$bool = 1;}
        else{$bool = 0;}

        return array(
            '"idUser"' => $this->getId(),
            'MDP' => $this->getMdpHache(),
            'PRENOMUSER' => $this->getPrenom(),
            'NOMUSER' => $this->getNom(),
            '"role"' => 'invité',
            'ESTADMIN' =>$bool,
            'EMAIL' => $this->getEmail()
        );
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }



    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }




}