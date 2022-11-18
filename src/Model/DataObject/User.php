<?php

namespace App\Model\DataObject;

class User extends AbstractDataObject
{
    private string $id;
    private string $mdp;

    /**
     * @param string $id
     * @param string $mdp
     */
    public function __construct(string $id, string $mdp)
    {
        $this->id = $id;
        $this->mdp = $mdp;
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


    public function formatTableau(): array
    {
        return array(
            "idTag" => $this->getId(),
            "mdpTag" => $this->getMdp()
        );
    }
}