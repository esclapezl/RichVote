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

    protected function construire(array $objetFormatTableau): AbstractDataObject
    {
        return new Question(
            $objetFormatTableau['IDQUESTION'],
            $objetFormatTableau['INTITULEQUESTION'],
            $objetFormatTableau['DESCRIPTIONQUESTION']
        );
    }

    public function sauvegarder(Question $question) : Question{
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
}