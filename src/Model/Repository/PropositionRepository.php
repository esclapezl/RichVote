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

    protected function getNomId(): string
    {
        return 'idProposition';
    }


    public function select(string $idProposition) : AbstractDataObject{
        $proposition = parent::select($idProposition);

        // on structure le tableau des sections avec leur texte de proposition
        $sql = 'select * from SOUVIGNETN.PROPOSERTEXTE where idproposition= :id';
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(array('id' => $proposition->getIdProposition()));
        $sectionsTexte = array();

        foreach ($pdoStatement as $section){
            $sectionsTexte[$section['IDSECTION']] = $section['TEXTE']; // jdois faire un fichier proposerTexte.php?
        }

        $proposition->setSectionsTexte($sectionsTexte);

        return $proposition;
    }


    public function sauvegarder(Proposition $proposition){
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $sql = 'INSERT INTO SOUVIGNETN.PROPOSITIONS(idQuestion) VALUES ('.$proposition.');
                SELECT MAX(idProposition) as maxi FROM SOUVIGNETN.PROPOSITIONS';
        $pdoStatement = $pdo->query($sql);

        $idProposition = $pdoStatement->fetch()['maxi'];

        $sql2 = "insert into SOUVIGNETN.PROPOSERTEXTE(idProposition, idSection, texte) VALUES (:idPropostion, :idSection, :texte)";
        $pdoStatement2 = $pdo->prepare($sql2);
        foreach($proposition->getSectionsTexte() as $key=>$text){
            $params = array(
                'idProposition' => $idProposition,
                'idSection' => $key,
                'texte' => $text
            );
            $pdoStatement2->execute($params);
        }
    }

    public function update(Proposition $proposition){
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $sql = "UPDATE SOUVIGNETN.PROPOSERTEXTE SET texte = :texte WHERE idProposition = :idProposition AND idSection = :idSection";
        $pdoStatement = $pdo->prepare($sql);
        foreach($proposition->getSectionsTexte() as $key=>$text){
            $params = array(
                'idProposition' => $proposition->getIdProposition(),
                'idSection' => $key,
                'texte' => $text
            );
            $pdoStatement->execute($params);
        }
    }

}