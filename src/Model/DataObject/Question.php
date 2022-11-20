<?php

namespace App\Model\DataObject;

use Cassandra\Date;
use DateTime;

class Question extends AbstractDataObject
{
    private ?string $id;
    private string $intitule;
    private string $description;
    private array $sections;
    private DateTime $dateCreation;
    private DateTime $dateFermeture;

    /**
     * @return DateTime
     */
    public function getDateCreation(): DateTime
    {
        return $this->dateCreation;
    }

    /**
     * @return DateTime
     */
    public function getDateFermeture(): DateTime
    {
        return $this->dateFermeture;
    }

    /**
     * @return array
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @param array $sections
     */
    public function setSections(array $sections): void
    {
        $this->sections = $sections;
    }

    /**
     * @param string $intitule
     */
    public function setIntitule(string $intitule): void
    {
        $this->intitule = $intitule;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
    public function getIntitule(): string
    {
        return $this->intitule;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function __construct(
        ?string $id,
        string $intitule,
        string $description,
        DateTime $dateCreation,
        DateTime $dateFermeture
    )
    {
        $this->id = $id;
        $this->intitule = $intitule;
        $this->description = $description;
        $this->dateCreation = $dateCreation;
        $this->dateFermeture = $dateFermeture;
    }

    public function ajouterSection(Section $section){
        $this->sections[] = $section;
    }

    public function formatTableau(): array
    {
        return array(
            "idTag" => $this->getId(),
            "intituleTag" => $this->getIntitule(),
            "descriptionTag" => $this->getDescription(),
            "sectionsTag" => $this->getSections(),
            "dateCreationTag" => $this->dateCreation,
            "dateFermetureTag" => $this->dateFermeture
        );


    }
}