<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Proposition;
use App\Model\DataObject\User;

class PropositionRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return 'SOUVIGNETN.PROPOSITIONS';
    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        $archive = false;
        if($objetFormatTableau['ARCHIVE'] == 'V'){$archive = true;}
        return new Proposition(
            $objetFormatTableau['IDPROPOSITION'],
            $objetFormatTableau['IDQUESTION'],
            null,
            $objetFormatTableau['INTITULE'],
            $archive
        );
    }

    protected function getNomClePrimaire(): string
    {
        return 'idProposition';
    }

    protected function getNomsColonnes(): array
    {
        return [
            "idProposition",
            "idQuestion",
            "intitule"
        ];

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



     public function sauvegarder(AbstractDataObject $proposition) : AbstractDataObject{
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $sql = "CALL creerProposition(:idQuestion)";

        $pdoStatement = $pdo->prepare($sql);

        $params = ['idQuestion' => $proposition->getIdQuestion()];

        $pdoStatement->execute($params);

        $sqlIdP = "select propositions_seq.CURRVAL as id from DUAL";

        return $this->select($pdo->query($sqlIdP)->fetch()['ID']);
    }

    public function update(AbstractDataObject $object): void
    {
        parent::update($object);

        $pdo = DatabaseConnection::getInstance()::getPdo();

        $sql = "update SOUVIGNETN.PROPOSERTEXTE SET texte = :texte WHERE idProposition = :idProposition AND idSection = :idSection";
        $pdoStatement = $pdo->prepare($sql);

        foreach($object->getSectionsTexte() as $key=>$text){
            $params = array(
                'idProposition' => $object->getIdProposition(),
                'idSection' => $key,
                'texte' => $text
            );
            $pdoStatement->execute($params);
        }
    }

    public function setScore(Proposition $proposition, int $score){
        //$sql = 'CALL voter(' . $idProposition . ", $score)"; la procédure ne marche pas
        $currentPhase = (new PhaseRepository())->getCurrentPhase($proposition->getIdQuestion());
        $idProposition = $proposition->getId();
        $sql = "UPDATE SESSIONVOTE sv set sv.score = $score where IDPROPOSITION = $idProposition AND sv.IDPHASEVOTE = "  . $currentPhase->getId();
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdo->query($sql);
    }

    protected function getIntitule(): string
    {
        return "intitule";
    }

    public function selectAllWithScore(string $idPhase): array{ // forme [Proposition, score]
        $sql = 'SELECT p.idProposition, p.idQuestion, intitule, archive, score  
                FROM sessionVote sv
                JOIN Propositions p ON p.idProposition=sv.idProposition
                where idPhaseVote=:idPhase
                ORDER BY score DESC';
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $pdoStatement->execute(['idPhase'=>$idPhase]);

        $result = [];
        foreach ($pdoStatement as $infoProposition){
            var_dump($infoProposition);
            $proposition = $this->construire([
                "IDPROPOSITION" => $infoProposition["IDPROPOSITION"],
                "IDQUESTION" => $infoProposition["IDQUESTION"],
                "INTITULE" => $infoProposition["INTITULE"],
                "ARCHIVE" => $infoProposition["ARCHIVE"]]);
            $result[] = [$proposition, $infoProposition['SCORE']];
        }

        return $result;
    }

    public function selectAllWithScoreForUser(string $idPhase, string $idUser){ // comme au dessus sauf que c'est pour un user (propal de score 0 si pas votée)
        $sql = 'SELECT vp.idProposition, intitule, archive, scoreVote
                FROM VotantProposition vp
                JOIN Questions q ON q.idProposition=vp.idProposition
                WHERE idPhaseVote=:idPhase AND idUser=:idUser
                UNION
                SELECT sv.idProposition, intitule, archive, 0
                FROM SESSIONVOTE sv
                JOIN PROPOSITIONS p ON p.idProposition=sv.idProposition
                WHERE idPhaseVote=:idPhase';

        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $pdoStatement->execute([
            'idPhase' => $idPhase,
            'idUser' => $idUser
        ]);

        $result = [];
        foreach ($pdoStatement as $infoProposition){
            var_dump($infoProposition);
            $proposition = $this->construire([
                "IDPROPOSITION" => $infoProposition["IDPROPOSITION"],
                "IDQUESTION" => $infoProposition["IDQUESTION"],
                "INTITULE" => $infoProposition["INTITULE"],
                "ARCHIVE" => $infoProposition["ARCHIVE"]]);
            $result[] = [$proposition, $infoProposition['SCORE']];
        }

        return $result;
    }
}