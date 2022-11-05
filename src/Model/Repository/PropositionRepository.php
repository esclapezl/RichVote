<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Proposition;

class PropositionRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return 'SOUVIGNETN.PROPOSITIONS';
    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Proposition(
            $objetFormatTableau['IDPROPOSITION'],
            $objetFormatTableau['IDPROPOSITION'],
            null
        );
    }


    public function select(string $idProposition){

        $sql = 'select * from SOUVIGNETN.PROPOSITIONS where idProposition= :id';
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute(array('id' => $idProposition));

        $propositionTab = $pdoStatement->fetch();
        $proposition = $this->construire($propositionTab);


        // on structure le tableau des sections avec leur texte de proposition
        $sql = 'select * from SOUVIGNETN.PROPOSERTEXTE where idproposition= :id';
        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(array('id' => $proposition->getIdProposition()));
        $sectionsTexte = array();

        foreach ($pdoStatement as $section){
            $sectionsTexte[$section['IDSECTION']] = $section['TEXTE']; // jdois faire un fichier proposerTexte.php?
        }

        $proposition->setSectionsTexte($sectionsTexte);

        return $proposition;
    }

}