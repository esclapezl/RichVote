<?php

namespace App\Controller;

use App\Model\DataObject\Proposition;
use App\Model\Repository\PropositionRepository;
use App\Model\Repository\QuestionRepository;

class ControllerProposition extends GenericController
{
    public static function readAll() : void
    {
        $idQuestion = $_GET['id'];

        $listePropositions = (new PropositionRepository())->selectAllForQuestion($idQuestion);

        $parametres = array(
            'pagetitle' => 'Liste Propositions',
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
            'pagetitle' => 'Détail Proposition',
            'cheminVueBody' => 'proposition/detail.php',
            'proposition' => $proposition
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function update(){
        $idProposition = $_GET['id'];

        $proposition = (new PropositionRepository())->select($idProposition);

        $parametres = array(
            'pagetitle' => 'Modifier Proposition',
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
        $proposition->setIntitule($_POST['intitule']);

        (new PropositionRepository())->update($proposition);

        $parametres = array(
            'pagetitle' => 'Détail Proposition',
            'cheminVueBody' => 'question/detail.php',
            'question' => (new QuestionRepository())->select($proposition->getIdQuestion())
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function create(){
        $idQuestion = $_GET['id'];

        $proposition = (new PropositionRepository())->sauvegarder(new Proposition(null, $idQuestion, null, null));

        $parametres = array(
            'pagetitle' => 'Créer Proposition',
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
            'pagetitle' => 'Proposition Supprimée',
            'cheminVueBody' => 'question/detail.php',
            'question' => (new QuestionRepository())->select($proposition->getIdQuestion())
        );

        self::afficheVue('view.php', $parametres);
    }
}