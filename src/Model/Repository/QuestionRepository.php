<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Phase;
use App\Model\DataObject\Question;
use App\Model\DataObject\Section;

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
            $objetFormatTableau['INTITULEQUESTION'],
            $objetFormatTableau['DESCRIPTIONQUESTION'],
            date_create_from_format('d/m/Y',$objetFormatTableau['DATECREATION']),
            date_create_from_format('d/m/Y',$objetFormatTableau['DATEFERMETURE']),
            $currentPhase
        );
    }

    public function creerQuestion(Question $question, int $nbSections){ // tentative pour réduire le temps d'attente apres la creatioin d'une question
        $intitule = $question->getIntitule();
        $description = $question->getDescription();
        $dateCreation = $question->getDateCreation()->format('d/m/Y');
        $dateFermeture = $question->getDateFermeture()->format('d/m/Y');
        $sql = "call CREERQUESTION(:intitule, :description, :dateCreation, :dateFermeture, :nbSections)";

        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdostatement = $pdo->prepare($sql);

        $parametres = [
            'intitule' => $intitule,
            'description' => $description,
            'dateCreation' => $dateCreation,
            'dateFermeture' => $dateFermeture,
            'nbSections' => $nbSections
        ];
        $pdostatement->execute($parametres);

        $sqlId = "SELECT MAX(idQuestion) FROM QUESTIONS";
        $pdoStatementId = $pdo->query($sqlId);

        $id = $pdoStatementId->fetch()[0];

        $question = $this->select($id);
        return $question;
    }

    public function select(string $id) : AbstractDataObject
    {
        $question = parent::select($id);
        $question->setSections((new SectionRepository)->getSectionsQuestion($id));
        return $question;
    }

    public function update(AbstractDataObject $object): void
    {
        parent::update($object); // TODO: Change the autogenerated stub
        foreach($object->getSections() as $section){
            (new SectionRepository())->update($section);
        }
    }


}