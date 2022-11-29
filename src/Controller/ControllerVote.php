<?php

namespace App\Controller;

use App\Model\Repository\PropositionRepository;
use App\Model\Repository\QuestionRepository;
use App\Model\Repository\UserRepository;

class ControllerVote extends GenericController
{
    public static function scrutinMajoritaire() : void
    {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $propositions = (new PropositionRepository())->selectAllForQuestion($question->getId());
        $parametres = array(
            'pagetitle' => 'Scrutin Majoritaire',
            'cheminVueBody' => 'vote/scrutinMajoritaire.php',
            'question' => $question,
            'propositions' => $propositions
        );
        self::afficheVue('view.php', $parametres);
    }

    public static function scrutinMajoritaireVoted(){
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $user = (new UserRepository())->select($_GET['idUser']);
        $scoreVote = $_GET['score'];

        var_dump($proposition->getId() . "  " . $user->getId() . "  " . $scoreVote);
        (new PropositionRepository())->voter($proposition, $user, $scoreVote);
    }
    public static function consultation() : void
    {

        $parametres = array(
            'pagetitle' => 'Consultation',
            'cheminVueBody' => 'vote/consultation.php'
        );

        self::afficheVue('view.php', $parametres);
    }
}