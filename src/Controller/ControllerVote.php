<?php

namespace App\Controller;

use App\Lib\ConnexionUtilisateur;
use App\Lib\MessageFlash;
use App\Model\HTTP\Session;
use App\Model\Repository\PropositionRepository;
use App\Model\Repository\QuestionRepository;
use App\Model\Repository\UserRepository;
use App\Model\Repository\VoteRepository;

class ControllerVote extends GenericController
{
    public static function scrutinMajoritaire() : void{
        $parametres = array(
            'pagetitle' => 'Scrutin Majoritaire',
            'cheminVueBody' => 'vote/scrutinMajoritaire.php',
        );
        self::afficheVue('view.php', $parametres);
    }

    public static function voterScrutinMajoritaire() : void
    {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $propositions = (new PropositionRepository())->selectAllForQuestion($question->getId());
        $parametres = array(
            'pagetitle' => 'Scrutin Majoritaire',
            'cheminVueBody' => 'vote/voter/scrutinMajoritaire.php',
            'question' => $question,
            'propositions' => $propositions
        );
        self::afficheVue('view.php', $parametres);
    }

    public static function voterScrutinMajoritairePlurinominal() : void
    {
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $propositions = (new PropositionRepository())->selectAllWithScoreForUser($question->getCurrentPhase()->getId(), ConnexionUtilisateur::getLoginUtilisateurConnecte());

        $propositionsPour = [];
        $propositionsContre = [];
        foreach ($propositions as $propositionWithScore){
            if($propositionWithScore[1] > 0){
                $propositionsPour[] = $propositionWithScore[0];
            }
            else{
                $propositionsContre[] = $propositionWithScore[0];
            }
        }

        $params =
            [
                'pagetitle' => 'vote plurinominal',
                'cheminVueBody' => '/vote/voter/scrutinMajoritairePlurinominal.php',
                'propositionsPour' => $propositionsPour,
                'propositionsContre' => $propositionsContre,
                'question' => $question
            ];
        self::afficheVue('view.php', $params);
    }

    public static function scrutinMajoritairePlurinominalVoted(){
        $user = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if($user == null){
            MessageFlash::ajouter('danger', 'vote refusé, vous n\'êtes pas connecté');
        }
        else if(isset($_POST['idPropositionPour'])){
            VoteRepository::voter($_POST['idPropositionPour'], $user, 1);

            MessageFlash::ajouter('success', 'Vous avez voté !');
        }
        else if(isset($_POST['idPropositionContre'])){
            VoteRepository::voter($_POST['idPropositionContre'], $user, 0);

            MessageFlash::ajouter('success', 'Vous avez voté !');
        }
        else{
            MessageFlash::ajouter('danger', "Votre vote n'est pas passé.");
        }
        self::voterScrutinMajoritairePlurinominal();
    }

    public static function scrutinMajoritaireVoted(){
        $user = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        if($user == null){
            MessageFlash::ajouter('danger', 'vote refusé, vous n\'êtes pas connecté');
        }
        else if(isset($_POST['idProposition'])){
            VoteRepository::voter($_POST['idProposition'], $user, 1);

            MessageFlash::ajouter('success', 'Vous avez voté !');
        }
        else{
            MessageFlash::ajouter('danger', "Votre vote n'est pas passé.");
        }
        ControllerQuestion::readAll();
    }
    public static function consultation() : void
    {

        $parametres = array(
            'pagetitle' => 'Consultation',
            'cheminVueBody' => 'vote/consultation.php'
        );

        self::afficheVue('view.php', $parametres);
    }

    public static function demandeAcces() : void{
        $idUser = ConnexionUtilisateur::getLoginUtilisateurConnecte();
        $idQuestion = $_GET['idQuestion'];

        if((new UserRepository())->demanderAccesVote($idUser, $idQuestion)){
            MessageFlash::ajouter('success', 'votre demande à bien été enregistrée');
        }
        else{
            MessageFlash::ajouter('failure', 'votre demande à échoué');
        }

        ControllerQuestion::readAll();
    }
}