<?php
namespace App\Controller;
use App\Model\DataObject\Proposition;
use App\Model\DataObject\Section;
use App\Model\Repository\DatabaseConnection as DataBaseConnection;
use App\Model\Repository\PropositionRepository;
use App\Model\Repository\QuestionRepository;
use App\Model\Repository\SectionRepository;
use mysql_xdevapi\DatabaseObject;
use App\Model\DataObject\Question;

class ControllerPresentation{
    public static function accueil()
    {
        self::afficheVue('view.php',[
            "pagetitle" => "Accueil",
            "cheminVueBody" => 'accueil.php'
        ]);
    }

    public static function error()
    {
        self::afficheVue('view.php',[
            "pagetitle" => "Erreur",
            "cheminVueBody" => 'error.php'
        ]);
    }


    public static function inscription()
    {

        self::afficheVue('view.php',[
            "pagetitle" => "Inscription",
            "cheminVueBody" => 'user/inscription.php'
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

    public static function selectQuestion(){
        $idQuestion = $_GET['idQuestion'];

        $question = (new QuestionRepository())->select($idQuestion);

        $parametres = array(
            'pagetitle' => 'Vue question',
            'cheminVueBody' => 'vote/viewQuestion.php',
            'question' => $question
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function selectProposition(){
        $idProposition = $_GET['idProposition'];

        $proposition = (new PropositionRepository())->select($idProposition);

        $parametres = array(
            'pagetitle' => 'Vue proposition',
            'cheminVueBody' => 'vote/viewProposition.php',
            'proposition' => $proposition
        );

        self::afficheVue('view.php', $parametres);
    }

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
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
        $idQuestion = $_GET['id'];

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

        $question = new Question($_GET['id'], $titreQuestion, $descriptionQuestion);
        (new QuestionRepository())->update($question);

        $sections = array();
        foreach($_POST['intitule'] as $key=>$intitule){
            $sections[$key]['intitule'] = $intitule;
        }

        foreach($_POST['description'] as $key=>$description){
            $sections[$key]['description'] = $description;
        }

        foreach ($sections as $key=>$tabSection){
            $section = new Section($key, $_GET['id'], $tabSection['intitule'], $tabSection['description']);
            (new SectionRepository())->update($section);
        }

        self::readAll();
    }

    public static function viewQuestion(){
        $idQuestion = $_GET['id'];

        $question = (new QuestionRepository())->select($idQuestion);

        $parametres = array(
            'pagetitle' => 'Vue question',
            'cheminVueBody' => 'vote/viewQuestion.php',
            'question' => $question
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function viewProposition(){
        $idProposition = $_GET['id'];

        $proposition = (new PropositionRepository())->select($idProposition);

        $parametres = array(
            'pagetitle' => 'vue proposition',
            'cheminVueBody' => 'vote/viewProposition.php',
            'proposition' => $proposition
        );

        self::afficheVue('view.php', $parametres);
    }
}
?>