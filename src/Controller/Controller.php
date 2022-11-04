<?php
namespace App\Controller;
use App\Model\DataObject\Section;
use App\Model\Repository\DatabaseConnection as DataBaseConnection;
use App\Model\Repository\QuestionRepository;
use App\Model\Repository\SectionRepository;
use mysql_xdevapi\DatabaseObject;
use App\Model\DataObject\Question;

class Controller{
    public static function accueil()
    {

        self::afficheVue('view.php',[
            "pagetitle" => "Accueil",
            "cheminVueBody" => 'vote/accueil.php'
        ]);
    }

    public static function createQuestion()
    {

        self::afficheVue('view.php',[
            "pagetitle" => "créer une question",
            "cheminVueBody" => 'vote/createQuestion.php'
        ]);
    }

    public static function questionCreated(){
        $intitule = $_POST['titreQuestion'];
        $nbSections = $_POST['nbSections'];

        $question = new Question(null, $intitule, 'description');
        $question = (new QuestionRepository)->sauvegarder($question);
        for($i=0; $i<$nbSections; $i++){
            $section = new Section($question->getId(), "section n°$i", 'description');
            (new SectionRepository())->sauvegarder($section);
        }

        self::readAll();
    }


    public static function inscription()
    {

        self::afficheVue('view.php',[
            "pagetitle" => "Inscription",
            "cheminVueBody" => 'vote/inscription.php'
        ]);
    }

    public static function readAll(){
        $arrayQuestion = (new QuestionRepository)->selectAll();

        foreach ($arrayQuestion as $question){
            $intitule = $question->getIntitule();
            $description = $question->getDescription();
            echo "je suis une question: $intitule           $description <br>";
        }
    }

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }
}
?>
