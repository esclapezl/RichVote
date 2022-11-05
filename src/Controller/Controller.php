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
            $section = new Section(null, $question->getId(), "section n°$i", 'description');
            (new SectionRepository())->sauvegarder($section);
        }

        $parametres = array(
            'pagetitle' => 'continuer la création de la question',
            'cheminVueBody' => 'vote/modifyQuestion.php',
            'question' => (new QuestionRepository())->select($question->getId())
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function modifyQuestion(){
        $idQuestion = $_GET['idQuestion'];

        $parametres = array(
            'pagetitle' => 'modifier une question',
            'cheminVueBody' => 'vote/modifyQuestion.php',
            'question' => (new QuestionRepository())->select($idQuestion)
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function questionModified(){
        $titreQuestion = $_POST['titreQuestion'];
        $descriptionQuestion = $_POST['descriptionQuestion'];

        $question = new Question($_GET['idQuestion'], $titreQuestion, $descriptionQuestion);
        (new QuestionRepository())->update($question);

        $sections = array();
        foreach($_POST['intitule'] as $key=>$intitule){
            $sections[$key]['intitule'] = $intitule;
        }

        foreach($_POST['description'] as $key=>$description){
            $sections[$key]['description'] = $description;
        }

        foreach ($sections as $key=>$tabSection){
            $section = new Section($key, $_GET['idQuestion'], $tabSection['intitule'], $tabSection['description']);
            (new SectionRepository())->update($section);
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

        $parametres = array(
            'pagetitle' => 'liste des questions',
            'cheminVueBody' => 'vote/listQuestion.php',
            'questions' => $arrayQuestion
        );

        self::afficheVue('view.php', $parametres);
    }

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }
}
?>
