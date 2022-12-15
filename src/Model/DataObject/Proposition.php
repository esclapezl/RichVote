<?php

namespace App\Model\DataObject;

use App\Lib\ConnexionUtilisateur;
use App\Model\DataObject\Section;
use App\Model\Repository\DatabaseConnection;
use App\Model\Repository\UserRepository;

class Proposition extends AbstractDataObject
{
    private ?string $idProposition;
    private string $idQuestion;
    private ?array $sectionsTexte; // pour chaque idSection, une chaine de caractere sera associée
    private ?string $intitule;
    private string $idResponsable;
    private bool $archive;

    public function __construct(
        ?string $idProposition,
        string $idQuestion,
        string $idResponsable,
        ?array $sections,
        ?string $titre,
        bool $archive
    ){
        $this->idProposition = $idProposition;
        $this->idQuestion = $idQuestion;
        $this->idResponsable = $idResponsable;
        $this->sectionsTexte = $sections;
        $this->intitule = $titre;
        $this->archive = $archive;
    }

    /**
     * @return bool
     */
    public function estArchive(): bool
    {
        return $this->archive;
    }


    public function getId(): ?string
    {
        return $this->idProposition;
    }


    public function setTexte(string $idSection, string $texte){
        if(in_array($idSection, $this->sectionsTexte)){
            $this->sectionsTexte[$idSection] = $texte;
        }
    }

    public function getTexte(string $idSection):?string{
        if(in_array($idSection, $this->sectionsTexte)){
            return $this->sectionsTexte[$idSection];
        }
        else{
            return null;
        }
    }

    public function getIntitule() :?string{
        return $this->intitule;
    }

    public function setIntitule(string $intitule){
        $this->intitule = $intitule;
    }

    /**
     * @return array
     */
    public function getSectionsTexte(): array
    {
        return $this->sectionsTexte;
    }

    /**
     * @param array $sectionsTexte
     */
    public function setSectionsTexte(array $sectionsTexte): void
    {
        $this->sectionsTexte = $sectionsTexte;
    }

    /**
     * @return string
     */
    public function getIdProposition(): string
    {
        return $this->idProposition;
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
    public function getIdResponsable(): string
    {
        return $this->idResponsable;
    }


    public function formatTableau(): array
    {
        return array(
            "idPropositionTag" => $this->getId(),
            "idQuestionTag" => $this->getIdQuestion(),
            "idResponsableTag" => $this->getIdResponsable(),
            "intituleTag" => $this->getIntitule()
        );
    }



    public function getAllCommentaires(): void
    {
        $sql = "SELECT * FROM souvignetn.commentaires WHERE IDPROPOSITION = '".$this->idProposition."'";
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute();
    }

}