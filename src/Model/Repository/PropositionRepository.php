<?php

namespace App\Model\Repository;

use App\Lib\ConnexionUtilisateur;
use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Proposition;
use App\Model\DataObject\Question;
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
            $objetFormatTableau['IDRESPONSABLE'],
            null,
            $objetFormatTableau['INTITULE'],
            $archive,
            (new GroupeAuteurRepository())->getIdAuteursProposition($objetFormatTableau['IDPROPOSITION'])
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
            "idResponsable",
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

    public function selectAllfromResponsable(string $id): ?array
    {
        $sql = "SELECT * FROM SOUVIGNETN.PROPOSITIONS WHERE idResponsable='" . $id ."'";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->query($sql);

        $propositions = [];
        foreach ($pdoStatement as $propositionTab){
            $propositions[] = $this->construire($propositionTab);
        }

        return $propositions;
    }


     public function sauvegarder(AbstractDataObject $proposition) : AbstractDataObject{
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $sql = "CALL creerProposition(:idQuestion, :idResponsable)";

        $pdoStatement = $pdo->prepare($sql);

        $params = ['idQuestion' => $proposition->getIdQuestion(),
            'idResponsable' => $proposition->getIdResponsable()];

        $pdoStatement->execute($params);

        $sqlIdP = "select propositions_seq.CURRVAL as id from DUAL";

         (new GroupeAuteurRepository())->sauvegarderGroupeProposition($proposition);

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

        (new GroupeAuteurRepository())->updateGroupeProposition($object);
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

    /*public function  getIntituleQuestion(string $idProposition): string
    {
        $sql = 'SELECT INTITULEQUESTION
                FROM SOUVIGNETN.QUESTIONS q
                JOIN SOUVIGNET.PROPOSITIONS p ON q.idQuestion = p.idQuestion
                WHERE idProposition = $idProposition';

        $pdo = DatabaseConnection::getInstance()::getPdo();
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute('id' => $idQuestion);

        return $arrayProposition;


        $sql = "SELECT * FROM vue_PhasesDetail WHERE idQuestion = :idQuestion";
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute(['idQuestion' => $idQuestio
    }*/

    public function selectAllWithScore(string $idPhase): array{ // forme [Proposition, score]
        $sql = 'SELECT p.idProposition, idResponsable, p.idQuestion, intitule, archive, score  
                FROM sessionVote sv
                JOIN Propositions p ON p.idProposition=sv.idProposition
                where idPhaseVote=:idPhase
                ORDER BY score DESC';
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $pdoStatement->execute(['idPhase'=>$idPhase]);

        $result = [];
        foreach ($pdoStatement as $infoProposition){
            $proposition = $this->construire([
                "IDPROPOSITION" => $infoProposition["IDPROPOSITION"],
                "IDQUESTION" => $infoProposition["IDQUESTION"],
                "IDRESPONSABLE" => $infoProposition["IDRESPONSABLE"],
                "INTITULE" => $infoProposition["INTITULE"],
                "ARCHIVE" => $infoProposition["ARCHIVE"]]);
            $result[] = [$proposition, $infoProposition['SCORE']];
        }

        return $result;
    }

    public function selectAllWithScoreForUser(string $idPhase, string $idUser){ // comme au dessus sauf que c'est pour un user (propal de score 0 si pas votée)
        $sql = 'SELECT vp.idProposition, idResponsable, intitule, archive, p.idQuestion, NVL(scoreVote, 0) as scoreVote
                FROM VotantProposition vp
                RIGHT JOIN Propositions p ON p.idProposition=vp.idProposition
                WHERE idPhaseVote=:idPhase AND idVotant=:idUser';

        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $pdoStatement->execute([
            'idPhase' => $idPhase,
            'idUser' => $idUser
        ]);

        $result = [];
        foreach ($pdoStatement as $infoProposition){
            $proposition = $this->construire([
                "IDPROPOSITION" => $infoProposition["IDPROPOSITION"],
                "IDQUESTION" => $infoProposition["IDQUESTION"],
                "INTITULE" => $infoProposition["INTITULE"],
                "ARCHIVE" => $infoProposition["ARCHIVE"],
                "IDRESPONSABLE" => $infoProposition["IDRESPONSABLE"]]);
            $result[] = [$proposition, $infoProposition['SCOREVOTE']];
        }

        return $result;
    }

    public function addAuteursProposition(array $users, Proposition $proposition){
        $sql = "INSERT INTO AuteurProposition(idAuteur, idProposition, idQuestion) VALUES (:idAuteur, :idProposition, :idQuestion)";
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $pdoStatement = $pdo->prepare($sql);
        $idProposition = $proposition->getId();
        $idQuestion = $proposition->getIdQuestion();
        foreach ($users as $idUser){
            $param = [
                'idAuteur' => $idUser,
                'idProposition' => $idProposition,
                'idQuestion' => $idQuestion
            ];
            $pdoStatement->execute($param);
        }
    }

    public function estAuteur(string $idUser, Proposition $proposition) : bool{
        $sql = "SELECT COUNT(idAuteur) FROM AUTEURPROPOSITION
                WHERE idAuteur=:idUser AND idProposition=:idProposition";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->prepare($sql);

        $parametres = [
            'idUser' => $idUser,
            'idProposition' => $proposition->getId()
        ];
        $pdoStatement->execute($parametres);

        $result = $pdoStatement->fetch()[0];
        return $result>0;
    }


}