<?php

namespace App\Controller;

use App\Lib\MessageFlash;
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
        if($proposition==null){
            MessageFlash::ajouter('danger', "La question avec l'id suivant : " . $_GET['id'] . "n'existe pas");
            self::redirection('frontController.php?controller=question&action=readAll');
        }
        else {

            $sectionsText = [];

            foreach ($_POST['texte'] as $idSection => $text) {
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

        if($proposition==null){
            MessageFlash::ajouter('danger', "La proposition n'existe pas");
            self::redirection('frontController.php?controller=question&action=readAll');
        }
        else {
            (new PropositionRepository())->delete($idProposition);
            MessageFlash::ajouter('success', 'La proposition : "' . $proposition . '" a bien été suprimée');
            self::redirection('frontController.php?controller=proposition&action=readAll&id='. $proposition->getIdQuestion());
        }
//        $parametres = array(
//            'pagetitle' => 'Proposition Supprimée',
//            'cheminVueBody' => 'question/detail.php',
//            'question' => (new QuestionRepository())->select($proposition->getIdQuestion())
//        );
//
//        self::afficheVue('view.php', $parametres);
    }

    public static function voter(){
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        (new PropositionRepository())->setScore($proposition, $_GET['score']);
    }
}