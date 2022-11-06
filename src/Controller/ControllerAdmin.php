<?php

namespace App\Controller;

use App\Model\Repository\QuestionRepository;

class ControllerAdmin
{

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }

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

    public static function readAll(){
        $arrayQuestion = (new QuestionRepository)->selectAll();

        $parametres = array(
            'pagetitle' => 'liste des questions',
            'cheminVueBody' => 'vote/listQuestion.php',
            'questions' => $arrayQuestion
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function delete() : void
    {

        $question = (new QuestionRepository())->select($_GET['id']);
        if($question==null){
            self::error();
        }
        else{
            (new QuestionRepository())->delete($_GET['id']);
            static::afficheVue('view.php',[
                "pagetitle"=> "Question supprimée",
                "cheminVueBody" => "vote/deleted.php",
                "question" => $question,
                "questions" => (new QuestionRepository())->selectAll()]);
        }
    }
}