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
            $objetFormatTableau['IDQUESTION'],
            null
        );
    }

    protected function getNomClePrimaire(): string
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

    //retourne toutes les propositions en lien avec une question
    public function selectAllForQuestion(string $idQuestion) : array{
        $sql = 'SELECT * FROM SOUVIGNETN.PROPOSITIONS WHERE idQuestion = :id';

        $pdo = DatabaseConnection::getInstance()::getPdo();
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute(array('id' => $idQuestion));

        $arrayProposition = [];
        foreach ($pdoStatement as $propositionFormatTab){
            $arrayProposition[] = $this->select($propositionFormatTab['IDPROPOSITION']);
        }
        return $arrayProposition;
    }


    public function sauvegarder(Proposition $proposition){
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $sql = 'INSERT INTO SOUVIGNETN.PROPOSITIONS(idQuestion) VALUES ('.$proposition->getIdQuestion().')';
        $sql1 = 'SELECT MAX(idProposition) as maxi FROM SOUVIGNETN.PROPOSITIONS';

        $pdoStatement = $pdo->query($sql);
        $pdoStatement1 = $pdo->query($sql1);

        $pdoStatement->execute(); // ajoute une nouvelle proposition
        $idProposition = $pdoStatement1->fetch()['MAXI']; // récupère le dernier id de proposition ajouté

        $sqlCreerTextes = "call creerSectionReponse(" . $idProposition . ")";
        $pdo->query($sqlCreerTextes);

        return $this->select($idProposition);
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