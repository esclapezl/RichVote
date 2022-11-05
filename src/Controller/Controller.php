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

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }
}
?>
