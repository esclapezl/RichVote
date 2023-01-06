<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Phase;
use App\Model\DataObject\Question;
use App\Model\DataObject\Section;
use App\Lib\ConnexionUtilisateur;

class QuestionRepository extends AbstractRepository
{
    protected function getNomTable(): string
    {
        return "SOUVIGNETN.QUESTIONS";
    }

    protected function getNomClePrimaire(): string
    {
        return 'idQuestion';
    }

    protected function getNomsColonnes(): array
    {
        return [
            "idQuestion",
            "idOrganisateur",
            "intituleQuestion",
            "descriptionQuestion",
            "dateCreation",
            "dateFermeture"
        ];
    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        $currentPhase = (new PhaseRepository())->getCurrentPhase($objetFormatTableau['IDQUESTION']);
        return new Question(
            $objetFormatTableau['IDQUESTION'],
            $objetFormatTableau['IDORGANISATEUR'],
            $objetFormatTableau['INTITULEQUESTION'],
            $objetFormatTableau['DESCRIPTIONQUESTION'],
            date_create_from_format('d/m/Y',$objetFormatTableau['DATECREATION']),
            date_create_from_format('d/m/Y',$objetFormatTableau['DATEFERMETURE']),
            $currentPhase
        );
    }

    public function creerQuestion(Question $question, $nbSections, int $nbPhases)
    { // tentative pour réduire le temps d'attente apres la creatioin d'une question
        $intitule = $question->getIntitule();
        $idOganisateur = $question->getIdOrganisateur();
        $description = $question->getDescription();
        $dateCreation = $question->getDateCreation()->format('d/m/Y');
        $dateFermeture = $question->getDateFermeture()->format('d/m/Y');
        $sql = "call CREERQUESTION(:idOrganisateur, :intitule, :description, :dateCreation, :dateFermeture, :nbSections, :nbPhases)";

        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdostatement = $pdo->prepare($sql);

        $parametres = [
            'idOrganisateur' => $idOganisateur,
            'intitule' => $intitule,
            'description' => $description,
            'dateCreation' => $dateCreation,
            'dateFermeture' => $dateFermeture,
            'nbSections' => $nbSections,
            'nbPhases' => $nbPhases
        ];
        $pdostatement->execute($parametres);

        $sqlId = "SELECT questions_seq.CURRVAL FROM DUAL";
        $pdoStatementId = $pdo->query($sqlId);

        $id = $pdoStatementId->fetch()[0];

        $question = $this->select($id);
        return $question;
    }

    public function select(string $id) : AbstractDataObject
    {
        $question = parent::select($id);
        $question->setSections((new SectionRepository)->getSectionsQuestion($id));
        $question->setPhases((new PhaseRepository())->getPhasesIdQuestion($id));
        return $question;
    }

    public function update(AbstractDataObject $object): void
    {
        parent::update($object);
        foreach($object->getSections() as $section){
            (new SectionRepository())->update($section);
        }
    }

    public function addOrganisateurs(array $users){
        $sql = 'UPDATE SOUVIGNETN.users SET "role"=:role WHERE "idUser"=:idUser';
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $pdoStatement = $pdo->prepare($sql);
        foreach ($users as $idUser){
            $param = [
                'idUser' => $idUser,
                'role' => 'organisateur'
            ];
            $pdoStatement->execute($param);
        }
    }

    public function addUsersQuestion(array $users, string $idQuestion, string $role){
        $sql = "CALL setRoleQuestion(:idUser, :role, :idQuestion)";
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $pdoStatement = $pdo->prepare($sql);
        foreach ($users as $idUser){
            $param = [
                'idUser' => $idUser,
                'idQuestion' => $idQuestion,
                'role' => $role
            ];
            $pdoStatement->execute($param);
        }
    }

    public function addGroupesQuestion(array $idGroupes, string $idQuestion, string $role){
        $sql = 'CALL setRoleQuestionGroupe(:idGroupe, :role, :idQuestion)';
        $pdo = DatabaseConnection::getInstance()::getPdo();
        $pdoStatement = $pdo->prepare($sql);
        foreach ($idGroupes as $id){
            $param = [
                'idGroupe' => $id,
                'role' => $role,
                'idQuestion' => $idQuestion
            ];
            $pdoStatement->execute($param);
        }
    }


    public function getIntitule(): string
    {
        return "intituleQuestion";
    }



    public function selectAllfromOrganisateur(string $id): ?array
    {
        $sql = "SELECT * FROM SOUVIGNETN.QUESTIONS WHERE idOrganisateur='" . $id ."'";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->query($sql);

        $questions = [];
        foreach ($pdoStatement as $questionTab){
            $questions[] = $this->construire($questionTab);
        }

        return $questions;
    }




    public function selectAllClosed(): array
    {
        $sql = "SELECT * FROM QUESTIONS q WHERE to_date(datefermeture, 'DD/MM/YY') <= SYSDATE";
        $pdoStatement = DatabaseConnection::getInstance()::getPdo()->query($sql);

        $questions = [];
        foreach ($pdoStatement as $questionTab){
            $questions[] = $this->construire($questionTab);
        }
        return $questions;
    }



    public function estFini(String $idQuestion) : bool{
        $sql = 'SELECT question_est_archive(:idQuestion) FROM DUAL';
        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute(
            ['idQuestion' => $idQuestion]
        );

        return $pdoStatement->fetch()[0]=='1';
    }
}