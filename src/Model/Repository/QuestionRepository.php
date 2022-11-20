<?php

namespace App\Model\Repository;

use App\Model\DataObject\AbstractDataObject;
use App\Model\DataObject\Question;

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
            "IDQUESTION",
            "INTITULEQUESTION",
            "DESCRIPTIONQUESTION",
            "DATECREATION",
            "DATEFERMETURE"
        ];

    }

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Question(
            $objetFormatTableau['IDQUESTION'],
            $objetFormatTableau['INTITULEQUESTION'],
            $objetFormatTableau['DESCRIPTIONQUESTION'],
            date_create_from_format('d/m/Y',$objetFormatTableau['DATECREATION']),
            date_create_from_format('d/m/Y',$objetFormatTableau['DATEFERMETURE'])
        );
    }

    public function sauvegarder(Question $question) : Question{ // faudrait l'enlever
        $sql = "INSERT INTO souvignetn.Questions(intituleQuestion, descriptionQuestion) VALUES(:intitule, :description)";
        $sqlId = "SELECT MAX(idQuestion) FROM QUESTIONS"; // on récupère l'id de la question vu qu'on ne l'a pas
        // y a un risque pour qu'on récupère pas le bon id si les deux requetes ne sont pas éxécutées en meme temps (et qu'un autre créer une question en meme temps)

        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo -> prepare($sql);
        $pdoStatementId = $pdo->prepare($sqlId);

        $pdoStatement->execute(array(
            'intitule' => $question->getIntitule(),
            'description' => $question->getDescription()
        ));

        $pdoStatementId->execute();

        $id = $pdoStatementId->fetch()[0];

        $question = new Question($id, $question->getIntitule(), $question->getDescription());
        return $question;
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

    public function update(Question $question){
        $sql = "update SOUVIGNETN.QUESTIONS SET intituleQuestion = :intitule, descriptionQuestion = :description where idQuestion = :id";

        $pdo = DatabaseConnection::getInstance()::getPdo();

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->execute(
            array(
                'intitule' => $question->getIntitule(),
                'description' => $question->getDescription(),
                'id' => $question->getId()
            )
        );
    }


}