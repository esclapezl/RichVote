<?php

namespace App\Controller;

use App\Model\DataObject\Proposition;
use App\Model\Repository\PropositionRepository;
use App\Model\Repository\QuestionRepository;

class ControllerProposition
{
    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }

    public static function readAll() : void
    {
        $idQuestion = $_GET['id'];

        $listePropositions = (new PropositionRepository())->selectAllForQuestion($idQuestion);

        $parametres = array(
            'pagetitle' => 'liste des propositions pour la question',
            'cheminVueBody' => 'proposition/list.php',
            'propositions' => $listePropositions
        );

        self::afficheVue('view.php',$parametres);
    }

    public static function read() : void
    {
        $idProposition = $_GET['id'];

        $proposition = (new PropositionRepository())->select($idProposition);

        $parametres = array(
            'pagetitle' => 'vue proposition',
            'cheminVueBody' => 'proposition/detail.php',
            'proposition' => $proposition
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function update(){
        $idProposition = $_GET['id'];

        $proposition = (new PropositionRepository())->select($idProposition);

        $parametres = array(
            'pagetitle' => 'modifier proposition',
            'cheminVueBody' => 'proposition/update.php',
            'proposition' => $proposition
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function updated() : void
    {
        $idProposition = $_GET['id'];

        $proposition = (new PropositionRepository())->select($idProposition);

        $sectionsText = [];

        foreach ($_POST['texte'] as $idSection=>$text){
            $sectionsText[$idSection] = $text;
        }

        $proposition->setSectionsTexte($sectionsText);
        $proposition->setIntitule($_GET['intitule']);

        (new PropositionRepository())->update($proposition);

        // la je fais exactement ce qui est fait dans vueProposition sans passer par $_GET
        $proposition = (new PropositionRepository())->select($idProposition);

        $parametres = array(
            'pagetitle' => 'vue proposition',
            'cheminVueBody' => 'proposition/detail.php',
            'proposition' => $proposition
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function create(){
        $idQuestion = $_GET['id'];

        $proposition = (new PropositionRepository())->sauvegarder(new Proposition(null, $idQuestion, null, null));

        $parametres = array(
            'pagetitle' => 'personnalisez votre proposition',
            'cheminVueBody' => 'proposition/update.php',
            'proposition' => $proposition
        );
        self::afficheVue('view.php', $parametres);
    }

    public static function delete(){
        $idProposition = $_GET['id'];
        $proposition = (new PropositionRepository())->select($idProposition);

        (new PropositionRepository())->delete($idProposition);
        $parametres = array(
            'pagetitle' => 'proposition supprimée',
            'cheminVueBody' => 'question/detail.php',
            'question' => (new QuestionRepository())->select($proposition->getIdQuestion())
        );

        self::afficheVue('view.php', $parametres);
    }
}