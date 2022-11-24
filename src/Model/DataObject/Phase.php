<?php

namespace App\Model\DataObject;

use DateTime;

class Phase extends AbstractDataObject
{
    private string $id;
    private string $type;
    private DateTime $dateDebut;
    private DateTime $dateFin;

    public function __construct(string $id, string $type, DateTime $dateDebut, DateTime $dateFin)
    {
        $this->id = $id;
        $this->type = $type;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return DateTime
     */
    public function getDateDebut(): DateTime
    {
        return $this->dateDebut;
    }

    /**
     * @return DateTime
     */
    public function getDateFin(): DateTime
    {
        return $this->dateFin;
    }

    public function formatTableau(): array
    {
        return ['idPhaseTag' => $this->id,
            'dateDebutTag' => $this->dateDebut->format('d/m/Y'),
            'dateFin' => $this->dateFin->format('d/m/Y'),
            'typePhasetag' => $this->type];
    }


    public function getId(): ?string
    {
        return $this->id;
    }


}