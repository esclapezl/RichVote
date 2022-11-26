<?php

namespace App\Controller;

use App\Model\DataObject\Question;
use App\Model\DataObject\Section;
use App\Model\Repository\QuestionRepository;
use App\Model\Repository\SectionRepository;

class ControllerQuestion extends GenericController
{
    public static function readAll() : void
    {
        $arrayQuestion = (new QuestionRepository)->selectAll();

        $parametres = array(
            'pagetitle' => 'Liste Questions',
            'cheminVueBody' => 'question/list.php',
            'questions' => $arrayQuestion
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function read() : void
    {
        $idQuestion = $_GET['id'];

        $question = (new QuestionRepository())->select($idQuestion);

        $parametres = array(
            'pagetitle' => 'Détail Question',
            'cheminVueBody' => 'question/detail.php',
            'question' => $question
        );

        self::afficheVue('view.php', $parametres);
    }


    public static function create() : void
    {
        self::afficheVue('view.php',[
            "pagetitle" => "Créer Question",
            "cheminVueBody" => 'question/create.php'
        ]);
    }

    public static function created() : void
    {
        $intitule = $_POST['titreQuestion'];
        $nbSections = $_POST['nbSections'];
        $dateCreation = date_create();
        $dateFermeture = date_create($_POST['dateFermeture']);
        $question = new Question(null, $intitule, 'description', $dateCreation, $dateFermeture);
        $question = (new QuestionRepository())->creerQuestion($question, $nbSections);

        $parametres = array(
            'pagetitle' => 'Ajuster Question',
            'cheminVueBody' => 'question/update.php',
            'question' => $question
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function update() : void
    {
        $idQuestion = $_GET['id'];

        $parametres = array(
            'pagetitle' => 'Modifier Question',
            'cheminVueBody' => 'question/update.php',
            'question' => (new QuestionRepository())->select($idQuestion)
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function updated() : void
    {
        $titreQuestion = $_POST['titreQuestion'];
        $descriptionQuestion = $_POST['descriptionQuestion'];

        $question = (new QuestionRepository())->select($_GET['id']);
        $question->setIntitule($titreQuestion);
        $question->setDescription($descriptionQuestion);
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

        static::afficheVue('view.php',[
                "pagetitle"=> "Liste Questions",
                "cheminVueBody" => "question/created.php",
                "questions" => (new QuestionRepository())->selectAll()]
        );

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
                "pagetitle"=> "Question Supprimée",
                "cheminVueBody" => "question/deleted.php",
                "question" => $question,
                "questions" => (new QuestionRepository())->selectAll()]);
        }
    }

    public static function voter():void{
        $question = (new QuestionRepository())->select($_GET['id']);
        if($question->getCurrentPhase() == 'vote'){
            $parametres = [
                'pagetitle' => 'vote la con de toi',
                'cheminVueBody' => 'vote/Vote.php',
                'question' => $question
            ];
            self::afficheVue('view.php', $parametres);
        }
    }

}