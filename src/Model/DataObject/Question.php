<?php

namespace App\Model\DataObject;

class Question extends AbstractDataObject
{
    private string $id;
    private string $intitule;
    private string $description;

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
        string $id,
        string $intitule,
        string $description
    )
    {
        $this->id = $id;
        $this->intitule = $intitule;
        $this->description = $description;
    }
}