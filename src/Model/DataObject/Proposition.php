<?php

namespace App\Model\DataObject;

use App\Model\DataObject\Section;

class Proposition extends AbstractDataObject
{
    private ?string $idProposition;
    private string $idQuestion;
    private ?array $sectionsTexte; // pour chaque idSection, une chaine de caractere sera associée

    public function __construct(
        ?string $idProposition,
        string $idQuestion,
        ?array $sections
    ){
        $this->idProposition = $idProposition;
        $this->idQuestion = $idQuestion;
        $this->sectionsTexte = $sections;
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
}